import { Component, computed, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { ArcanistaPathSection } from '../../../shared/arcanista-path-section/arcanista-path-section';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { AGE_BRACKETS } from '../../../shared/constants/age-brackets';

const ARCANISTA_CLASS_ID = 3;

// How many age-bracket bonus levels each bracket grants — same mapping
// character-draft.ts's own ageBracketExtraClassIds keys off of.
const AGE_BRACKET_EXTRA_LEVEL_COUNT: Record<string, number> = {
  maduro: 1,
  velho: 2,
  anciao: 3,
};

@Component({
  selector: 'app-character-creation-classes-step',
  imports: [CardHeader, SearchableDropdown, ArcanistaPathSection],
  templateUrl: './character-creation-classes-step.html',
  styleUrl: './character-creation-classes-step.scss',
})
export class CharacterCreationClassesStep {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  protected get classes() {
    return this.staticRegistry.classes;
  }

  protected readonly rows = computed(() => {
    const levelCount = this.draft.baseLevel() ?? 0;
    return Array.from({ length: levelCount }, (_, index) => ({
      index,
      label: index === 0 ? 'Nível 1 - Classe Inicial' : `Nível ${index + 1}`,
    }));
  });

  protected classIdAt(index: number): number | string | null {
    return this.draft.classIds()[index] ?? null;
  }

  protected setClassIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.classIds()];
    while (current.length <= index) {
      current.push(null);
    }
    current[index] = value as number | null;
    this.draft.classIds.set(current);
  }

  // The age-bracket's own bonus levels (Maduro +1, Velho +2, Ancião +3) —
  // same rows conceptually as the base ones above, just continuing right
  // after them (character-draft.ts's own orderedClassIds does the same
  // base-then-bonus concatenation). Labeled with the bracket's name so
  // it's clear at a glance why this level exists.
  protected readonly ageBracketExtraRows = computed(() => {
    const bracket = this.draft.ageBracket();
    const count = bracket ? (AGE_BRACKET_EXTRA_LEVEL_COUNT[bracket] ?? 0) : 0;
    if (count === 0) {
      return [];
    }
    const baseLevel = this.draft.baseLevel() ?? 0;
    const bracketName = AGE_BRACKETS.find((b) => b.id === bracket)?.name ?? '';
    return Array.from({ length: count }, (_, index) => ({
      index,
      absoluteIndex: baseLevel + index,
      label: `Nível ${baseLevel + index + 1} (${bracketName})`,
    }));
  });

  // Dispatches to whichever draft field the current bracket actually owns
  // (maduroClassId is a single value, velhoClassIds/anciaoClassIds are
  // arrays) — same fields character-draft.ts's ageBracketExtraClassIds
  // already reads, just now written from here instead of the age step.
  protected ageBracketClassIdAt(index: number): number | string | null {
    switch (this.draft.ageBracket()) {
      case 'maduro':
        return this.draft.maduroClassId();
      case 'velho':
        return this.draft.velhoClassIds()[index] ?? null;
      case 'anciao':
        return this.draft.anciaoClassIds()[index] ?? null;
      default:
        return null;
    }
  }

  protected setAgeBracketClassIdAt(index: number, value: number | string | null): void {
    switch (this.draft.ageBracket()) {
      case 'maduro':
        this.draft.maduroClassId.set(value as number | null);
        break;
      case 'velho': {
        const current = [...this.draft.velhoClassIds()];
        current[index] = value as number | null;
        this.draft.velhoClassIds.set(current);
        break;
      }
      case 'anciao': {
        const current = [...this.draft.anciaoClassIds()];
        current[index] = value as number | null;
        this.draft.anciaoClassIds.set(current);
        break;
      }
    }
  }

  // The one row (lowest absolute index, across base AND age-bracket bonus
  // levels — see character-draft.ts's orderedClassIds) whose class is
  // Arcanista — every later row picking Arcanista again is just a further
  // level in the same path already chosen, so only this row shows the
  // Caminho section. Reading orderedClassIds (not just classIds) matters:
  // a character whose only Arcanista level is an age-bracket bonus one
  // still needs to pick a Caminho.
  protected readonly firstArcanistaRowIndex = computed(() => {
    return this.draft.orderedClassIds().findIndex((classId) => classId === ARCANISTA_CLASS_ID);
  });

  protected isFirstArcanistaRow(absoluteIndex: number): boolean {
    return this.firstArcanistaRowIndex() === absoluteIndex;
  }

  protected get draftArcanistaPathPowerId() {
    return this.draft.arcanistaPathPowerId;
  }

  protected readonly canContinue = computed(
    () =>
      this.rows().length > 0 &&
      this.rows().every((row) => this.classIdAt(row.index) !== null) &&
      this.ageBracketExtraRows().every((row) => this.ageBracketClassIdAt(row.index) !== null) &&
      (this.firstArcanistaRowIndex() === -1 || this.draft.arcanistaPathPowerId() !== null),
  );

  back(): void {
    this.router.navigate(['/character-creation-age-step']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-origin-step']);
  }
}
