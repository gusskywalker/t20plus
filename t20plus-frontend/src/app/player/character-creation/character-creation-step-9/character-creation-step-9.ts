import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { Power } from '../../../api.service';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';

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
  imports: [CardHeader, SearchableDropdown],
  templateUrl: './character-creation-step-9.html',
  styleUrl: './character-creation-step-9.scss',
})
export class CharacterCreationStep9 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

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

  // The class's own choosable power pool — type 'class' (not
  // 'class_granted', which is auto-only and never shown here) whose
  // prerequisites name this class. Empty today since no pool powers are
  // seeded yet (only class_granted ones, e.g. Ataque Especial's tiers).
  protected classPowerItems(classId: number): Power[] {
    return this.staticRegistry.powers.filter(
      (power) =>
        power.type === 'class' &&
        (power.prerequisites ?? []).some(
          (prerequisite) =>
            prerequisite.type === 'class' && (prerequisite.class_ids ?? []).includes(classId),
        ),
    );
  }

  protected classPowerIdAt(index: number): number | null {
    return this.draft.classPowerIds()[index] ?? null;
  }

  protected setClassPowerIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.classPowerIds()];
    current[index] = (value as number | null) ?? null;
    this.draft.classPowerIds.set(current);
  }

  back(): void {
    this.router.navigate(['/character-creation-step-8']);
  }

  continue(): void {
    // Step 10 doesn't exist yet.
  }
}
