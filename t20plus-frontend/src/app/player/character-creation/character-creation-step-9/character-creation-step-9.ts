import { Component, computed, effect, inject, signal } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { Modal } from '../../../shared/modal/modal';
import { ApiService, Power, Prerequisite } from '../../../api.service';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { UseCharacter } from '../../../shared/hooks/use-character';
import { calculateStatBonus } from '../../../shared/helpers/calculate-stat-bonus/calculate-stat-bonus';
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

    // Clear the bonus Poder Geral whenever Nenhuma is (re-)picked back on
    // step 7 — the power only means anything alongside a real complication,
    // and its own section here is hidden once that's true anyway (see the
    // @if in the template), so a stale value should be cleared too.
    effect(() => {
      if (this.draft.generalComplicationId() === null) {
        this.draft.generalComplicationPowerId.set(null);
      }
    });

    // Clear the picked bonus power if it stops being a valid option — e.g.
    // the player goes back and picks it from the origin/god screens
    // instead, after already having it selected here.
    effect(() => {
      const powerId = this.draft.generalComplicationPowerId();
      if (powerId === null) {
        return;
      }
      const origin = this.staticRegistry.origins.find((o) => o.id === this.draft.originId());
      const originChoices = this.draft.originChoices();
      const grantedByOrigin = (origin?.grants ?? []).some((group, gi) =>
        (originChoices[gi] ?? []).some((optionIndex) => group.options[optionIndex]?.power_id === powerId),
      );
      const grantedByGod = this.draft.godPowerIds().includes(powerId);
      if (grantedByOrigin || grantedByGod) {
        this.draft.generalComplicationPowerId.set(null);
      }
    });
  }

  // Shared by every power-picking dropdown on this screen (the two general-
  // power sections below, and each level-up row's own dropdown) — checks
  // every prerequisite entry EXCEPT 'class'/'race', which are the type-
  // membership gate each dropdown already applies before ever calling this
  // (see availablePowerItems' typeMatches). 'attribute' resolves against the
  // draft's CURRENT stats via calculateStatBonus (CharacterDraft satisfies
  // the same StatBonusSource shape a real Character does — see its own
  // base_*/active_effects/level getters), so a mid-creation Aumento de
  // Atributo pick is already accounted for, not just the raw step-2 input.
  private checkPrerequisites(power: Power, characterLevel: number): boolean {
    const granted = this.draft.grantedPowerIds();
    return (power.prerequisites ?? []).every((prerequisite: Prerequisite) => {
      switch (prerequisite.type) {
        case 'attribute':
          return (
            prerequisite.attribute !== undefined &&
            calculateStatBonus(this.draft, prerequisite.attribute, this.staticRegistry.powers) >= (prerequisite.min ?? 0)
          );
        case 'character_level':
          return characterLevel >= (prerequisite.min ?? 0);
        case 'power':
          return prerequisite.power_id !== undefined && granted.has(prerequisite.power_id);
        default:
          // class/race are gated by typeMatches at the call site before this
          // ever runs; skill_trained/god/power_type aren't modeled yet.
          return true;
      }
    });
  }

  // Bonus Poder Geral list (Complicação) — moved here from step 7 so every
  // power pick lives on one screen. Every 'general' power minus whatever's
  // already on the draft from any source (draft.grantedPowerIds), minus
  // anything whose prerequisites the draft doesn't meet, except this
  // dropdown's own current pick, which has to stay in its own list or the
  // dropdown would show a blank label for a value it can't find.
  protected readonly generalPowerItems = computed(() => {
    const granted = this.draft.grantedPowerIds();
    const ownPick = this.draft.generalComplicationPowerId();
    return this.staticRegistry.powers.filter(
      (p) => p.source === 'general' && (!granted.has(p.id) || p.id === ownPick) && this.checkPrerequisites(p, this.draft.totalLevel()),
    );
  });

  // Same idea as generalPowerItems, but for Adulto's own mandatory pick —
  // a separate dropdown/draft field, so it needs its own "own pick" carve-
  // out (a general-complication power and Adulto's power could both be in
  // play on the same draft at once).
  protected readonly adultoPowerItems = computed(() => {
    const granted = this.draft.grantedPowerIds();
    const ownPick = this.draft.adultoPowerId();
    return this.staticRegistry.powers.filter(
      (p) => p.source === 'general' && (!granted.has(p.id) || p.id === ownPick) && this.checkPrerequisites(p, this.draft.totalLevel()),
    );
  });

  protected get draftGeneralComplicationId() {
    return this.draft.generalComplicationId;
  }

  protected get draftGeneralComplicationPowerId() {
    return this.draft.generalComplicationPowerId;
  }

  protected get draftAgeBracket() {
    return this.draft.ageBracket;
  }

  protected get draftAdultoPowerId() {
    return this.draft.adultoPowerId;
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

  // Golpe Pessoal is the one power the rulebook explicitly lets you pick
  // more than once ("outras vezes para golpes diferentes") — every other
  // power is a one-time fact, hence the granted-exclusion below. Hardcoded
  // exception, same convention as powerPickHints/ataqueEspecialPowerIds.
  private readonly repeatablePowerIds = new Set([115]); // Golpe Pessoal

  // Every power choosable at THIS row's level-up: 'class' powers whose
  // prerequisites name this row's class (not 'class_granted', which is
  // auto-only and never shown here), 'general'/'tormenta'/'group' powers
  // (no type-restriction), and 'races' powers whose prerequisites name the
  // draft's current race — minus whatever's already on the draft from any
  // source (draft.grantedPowerIds — origin/god/complication/age-bracket/
  // starting-class proficiencies/other level-up picks alike), except this
  // row's own current pick (has to stay in its own list or the dropdown
  // would show a blank label for a value it can't find) and except any
  // repeatablePowerIds entry, which stays pickable everywhere regardless
  // of already being granted elsewhere.
  // Every *_granted/origin_granted/'specific' source is deliberately
  // excluded — not meant to be player-picked here (the typeMatches
  // allowlist below only names 'general'/'tormenta'/'group'/'class'/
  // 'races', so anything else is excluded by default, no explicit check
  // needed).
  //
  // Matching the source is only the first gate — every OTHER prerequisite
  // entry on the power (character_level, power chains) still has to be
  // satisfied at this specific row too, using that row's own
  // characterLevel (not draft.totalLevel() — an earlier row's level is
  // lower than the character's eventual total, e.g. Nível 2's dropdown
  // must only offer patamar-Iniciante tiers, not every tier up to
  // whatever level the character ends up at).
  protected availablePowerItems(row: LevelPowerRow): Power[] {
    const raceId = this.draft.raceId();
    const granted = this.draft.grantedPowerIds();
    const ownPick = this.draft.classPowerIds()[row.index] ?? null;

    return this.staticRegistry.powers.filter((power) => {
      if (granted.has(power.id) && power.id !== ownPick && !this.repeatablePowerIds.has(power.id)) {
        return false;
      }

      const typeMatches =
        power.source === 'general' || power.source === 'tormenta' || power.source === 'group'
          ? true
          : power.source === 'class'
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
            : power.source === 'races'
              ? raceId !== null &&
                (power.prerequisites ?? []).some(
                  (prerequisite) =>
                    prerequisite.type === 'race' && (prerequisite.race_ids ?? []).includes(raceId),
                )
              : false;
      if (!typeMatches) {
        return false;
      }

      return this.checkPrerequisites(power, row.characterLevel);
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

  // Hardcoded per-power hint shown under a level row's dropdown once that
  // power is picked — same hardcode-the-exception convention as
  // ataqueEspecialPowerIds/Golpe Pessoal's own menu ids, just for a UI
  // nudge instead of a mechanic. Golpe Pessoal itself needs no build UI
  // here (that lives on the character sheet, see golpe-pessoal-modal
  // plans) — this just tells the player where to go.
  private readonly powerPickHints: Record<number, string> = {
    115: 'Customize na página do personagem', // Golpe Pessoal
  };

  protected powerPickHint(index: number): string | null {
    const powerId = this.classPowerIdAt(index);
    return powerId !== null ? (this.powerPickHints[powerId] ?? null) : null;
  }

  // Same two gates step 7 used to enforce before its own dropdowns moved
  // here — only required when the thing granting them is actually in play
  // (a real complication picked, or Adulto as the age bracket) — plus every
  // level-up row's own class-power dropdown, which has no such "only when
  // in play" carve-out: every row in levelPowerRows offers a real choice,
  // so every one of them needs a pick before saving.
  protected readonly canContinue = computed(() => {
    const generalComplicationSatisfied = this.draft.generalComplicationId() === null || this.draft.generalComplicationPowerId() !== null;
    const adultoSatisfied = this.draft.ageBracket() !== 'adulto' || this.draft.adultoPowerId() !== null;
    const classPowerIds = this.draft.classPowerIds();
    const levelPowersSatisfied = this.levelPowerRows().every((row) => classPowerIds[row.index] !== null);
    return generalComplicationSatisfied && adultoSatisfied && levelPowersSatisfied;
  });

  protected readonly saving = signal(false);

  back(): void {
    this.router.navigate(['/character-creation-step-8']);
  }

  continue(): void {
    const payload = buildCharacterPayload(this.draft, this.staticRegistry.origins, this.staticRegistry.races);

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
        this.draft.reset();
        closeSavingModal(() => this.router.navigate(['/player']));
      },
      error: (err) => {
        console.error('Failed to create character', err);
        closeSavingModal(() => {});
      },
    });
  }
}
