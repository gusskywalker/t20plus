import { Component, computed, effect, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { Modal } from '../../../shared/modal/modal';
import { ApiService, Power } from '../../../api.service';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { UseCharacter } from '../../../shared/hooks/use-character';
import { CharacterDraft } from '../character-draft';
import { buildCharacterPayload } from '../character-payload';

// The "Salvando..." modal stays up at least this long even if the request
// resolves faster, so it doesn't just flash on screen.
const MIN_SAVING_MS = 4000;

interface LevelPowerRow {
  /** Index into orderedClassIds/classPowerIds — same index means same level. */
  index: number;
  /** The character's overall level at this row (1-based). */
  characterLevel: number;
  classId: number;
  className: string;
  /** How many levels of this specific class the character has had up to and including this row (class-relative, not character-relative — matters for multiclassing). */
  classLevel: number;
}

@Component({
  selector: 'app-character-creation-step-9',
  imports: [CardHeader, SearchableDropdown, Modal],
  templateUrl: './character-creation-step-9.html',
  styleUrl: './character-creation-step-9.scss',
})
export class CharacterCreationStep9 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);
  private apiService = inject(ApiService);
  private useCharacter = inject(UseCharacter);

  constructor() {
    // Reset classPowerIds whenever orderedClassIds actually changes (a
    // level added/removed, or which class occupies a level) — a prior
    // level's class pick could otherwise silently point at the wrong
    // level, same reasoning/pattern as classSkillChoicesSourceKey in step
    // 6. Keyed on the whole ordered list, not just its length, since
    // inserting/removing a level anywhere shifts every later index's
    // meaning.
    effect(() => {
      const orderedClassIds = this.draft.orderedClassIds();
      const key = orderedClassIds.join(',');
      if (this.draft.classPowerIdsSourceKey() === key) {
        return;
      }
      this.draft.classPowerIdsSourceKey.set(key);
      this.draft.classPowerIds.set(orderedClassIds.map(() => null));
    });
  }

  // Every level that offers a class-power choice: class-relative level 2
  // onwards only — a class's own first level never offers one, it only
  // bakes in whatever's class_granted (e.g. Ataque Especial's tiers are
  // all automatic, never picked here). class-relative, not character-
  // relative, level is what gates this — the class's own 2nd level still
  // grants a pick even when reached via multiclassing partway through the
  // character's career.
  protected readonly levelPowerRows = computed<LevelPowerRow[]>(() => {
    const orderedClassIds = this.draft.orderedClassIds();
    const classLevelCounts = new Map<number, number>();
    const rows: LevelPowerRow[] = [];

    orderedClassIds.forEach((classId, index) => {
      if (classId === null) {
        return;
      }
      const classLevel = (classLevelCounts.get(classId) ?? 0) + 1;
      classLevelCounts.set(classId, classLevel);
      if (classLevel < 2) {
        return;
      }
      const className = this.staticRegistry.classes.find((c) => c.id === classId)?.name ?? '';
      rows.push({ index, characterLevel: index + 1, classId, className, classLevel });
    });

    return rows;
  });

  protected levelLabel(row: LevelPowerRow): string {
    return `Nível ${row.characterLevel} - ${row.className} ${row.classLevel}`;
  }

  // Every power choosable at THIS row's level-up: 'class' powers whose
  // prerequisites name this row's class (not 'class_granted', which is
  // auto-only and never shown here), 'general'/'tormenta'/'group' powers
  // (no type-restriction), and 'races' powers whose prerequisites name the
  // draft's current race — minus whatever's already been picked at any
  // OTHER level (a power picked once shouldn't be offered again), except
  // this row's own current pick, which has to stay in its own list or the
  // dropdown would show a blank label for a value it can't find.
  // 'resting' deliberately excluded — not meant to be player-picked here.
  //
  // Matching the type is only the first gate — every OTHER prerequisite
  // entry on the power (character_level, power chains) still has to be
  // satisfied at this specific row too, using that row's own
  // characterLevel (not draft.totalLevel() — an earlier row's level is
  // lower than the character's eventual total, e.g. Nível 2's dropdown
  // must only offer patamar-Iniciante tiers, not every tier up to
  // whatever level the character ends up at).
  protected availablePowerItems(row: LevelPowerRow): Power[] {
    const raceId = this.draft.raceId();
    const allPicked = this.draft.classPowerIds();
    const pickedElsewhere = new Set(allPicked.filter((id, i) => id !== null && i !== row.index));
    const hasPower = (powerId: number | undefined) =>
      powerId !== undefined && allPicked.includes(powerId);

    return this.staticRegistry.powers.filter((power) => {
      if (pickedElsewhere.has(power.id)) {
        return false;
      }

      const typeMatches =
        power.type === 'general' || power.type === 'tormenta' || power.type === 'group'
          ? true
          : power.type === 'class'
            ? (power.prerequisites ?? []).some(
                (prerequisite) =>
                  prerequisite.type === 'class' &&
                  (prerequisite.class_ids ?? []).includes(row.classId) &&
                  // class prerequisites' min_level is THAT class's own
                  // relative level (row.classLevel), never character level
                  // — a power requiring "Guerreiro 6" only ever shows up
                  // on the row where this class's own count hits 6.
                  row.classLevel >= (prerequisite.min_level ?? 0),
              )
            : power.type === 'races'
              ? raceId !== null &&
                (power.prerequisites ?? []).some(
                  (prerequisite) =>
                    prerequisite.type === 'race' && (prerequisite.race_ids ?? []).includes(raceId),
                )
              : false;
      if (!typeMatches) {
        return false;
      }

      return (power.prerequisites ?? []).every((prerequisite) => {
        switch (prerequisite.type) {
          case 'character_level':
            return row.characterLevel >= (prerequisite.min ?? 0);
          case 'power':
            return hasPower(prerequisite.power_id);
          default:
            // attribute/skill_trained/god/power_type/class/race aren't
            // checked here — class/race already gated type membership
            // above, and the rest aren't modeled at this screen yet.
            return true;
        }
      });
    }).sort((a, b) => a.name.localeCompare(b.name, 'pt-BR'));
  }

  protected classPowerIdAt(index: number): number | null {
    return this.draft.classPowerIds()[index] ?? null;
  }

  protected setClassPowerIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.classPowerIds()];
    current[index] = (value as number | null) ?? null;
    this.draft.classPowerIds.set(current);
  }

  protected readonly saving = signal(false);

  back(): void {
    this.router.navigate(['/character-creation-step-8']);
  }

  continue(): void {
    const payload = buildCharacterPayload(
      this.draft,
      this.staticRegistry.origins,
      this.staticRegistry.classes,
      this.staticRegistry.complications,
    );

    const startedAt = Date.now();
    this.saving.set(true);

    // Waits out whatever's left of MIN_SAVING_MS before closing the modal,
    // so a fast response doesn't just flash it. success only redirects —
    // an error just closes the modal and leaves the player here to retry,
    // rather than navigating away from a save that didn't happen.
    const closeSavingModal = (onClosed: () => void) => {
      const remaining = Math.max(0, MIN_SAVING_MS - (Date.now() - startedAt));
      setTimeout(() => {
        this.saving.set(false);
        onClosed();
      }, remaining);
    };

    this.apiService.createCharacter(payload).subscribe({
      next: () => {
        this.useCharacter.invalidate();
        closeSavingModal(() => this.router.navigate(['/player']));
      },
      error: (err) => {
        console.error('Failed to create character', err);
        closeSavingModal(() => {});
      },
    });
  }
}
