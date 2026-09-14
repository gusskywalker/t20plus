import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { ClassPickRow } from '../../../shared/class-pick-row/class-pick-row';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { AGE_BRACKETS } from '../../../shared/constants/age-brackets';
import { FEITICEIRO_POWER_ID } from '../../../shared/arcanista-path-section/arcanista-path-section';

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
  imports: [CardHeader, ClassPickRow],
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

  constructor() {
    // Clear a stale Caminho pick once NO row is Arcanista's own first level
    // anymore — a single list-wide check, not one per row: every row's own
    // <app-class-pick-row> is bound to this same shared draft field (there's
    // only one Caminho pick, not one per row), so a per-row "clear if it's
    // not MY row" effect would fire from every OTHER row the instant the
    // real owner sets it, wiping it out immediately. This lives here
    // instead of inside ClassPickRow for exactly that reason.
    effect(() => {
      if (this.firstArcanistaRowIndex() === -1 && this.draft.arcanistaPathPowerId() !== null) {
        this.draft.arcanistaPathPowerId.set(null);
      }
    });

    // Same reasoning, one level down: clear a stale Linhagem pick whenever
    // the Caminho itself isn't Feiticeiro anymore — covers both "no
    // Arcanista row at all" (arcanistaPathPowerId already null) and "an
    // Arcanista row exists but picked Bruxo/Mago instead." Lives here, not
    // inside ArcanistaPathSection, for the same reason as the effect above.
    effect(() => {
      if (this.draft.arcanistaPathPowerId() !== FEITICEIRO_POWER_ID && this.draft.linhagemPowerId() !== null) {
        this.draft.linhagemPowerId.set(null);
      }
    });
  }

  // One row per level the character has, base levels (step 3's own
  // classIds) followed by whichever age-bracket bonus levels apply (Maduro
  // +1, Velho +2, Ancião +3) — same base-then-bonus order as
  // character-draft.ts's own orderedClassIds, indexed the same way
  // (absoluteIndex lines up 1:1 with a position in orderedClassIds).
  // Bonus rows are labeled with the bracket's name so it's clear at a
  // glance why that level exists.
  protected readonly rows = computed(() => {
    const baseLevel = this.draft.baseLevel() ?? 0;
    const baseRows = Array.from({ length: baseLevel }, (_, index) => ({
      absoluteIndex: index,
      label: index === 0 ? 'Nível 1 - Classe Inicial' : `Nível ${index + 1}`,
    }));

    const bracket = this.draft.ageBracket();
    const bonusCount = bracket ? (AGE_BRACKET_EXTRA_LEVEL_COUNT[bracket] ?? 0) : 0;
    const bracketName = AGE_BRACKETS.find((b) => b.id === bracket)?.name ?? '';
    const bonusRows = Array.from({ length: bonusCount }, (_, index) => ({
      absoluteIndex: baseLevel + index,
      label: `Nível ${baseLevel + index + 1} (${bracketName})`,
    }));

    return [...baseRows, ...bonusRows];
  });

  // Dispatches by absolute index — below baseLevel reads/writes step 3's
  // own classIds array; at or past it, reads/writes whichever draft field
  // the current bracket actually owns (maduroClassId is a single value,
  // velhoClassIds/anciaoClassIds are arrays) — the same fields
  // character-draft.ts's ageBracketExtraClassIds already reads. One
  // surface for both cases instead of two parallel row lists/getters, even
  // though the underlying storage genuinely differs.
  protected classIdAt(absoluteIndex: number): number | null {
    const baseLevel = this.draft.baseLevel() ?? 0;
    if (absoluteIndex < baseLevel) {
      return this.draft.classIds()[absoluteIndex] ?? null;
    }
    const bonusIndex = absoluteIndex - baseLevel;
    switch (this.draft.ageBracket()) {
      case 'maduro':
        return this.draft.maduroClassId();
      case 'velho':
        return this.draft.velhoClassIds()[bonusIndex] ?? null;
      case 'anciao':
        return this.draft.anciaoClassIds()[bonusIndex] ?? null;
      default:
        return null;
    }
  }

  protected setClassIdAt(absoluteIndex: number, value: number | string | null): void {
    const baseLevel = this.draft.baseLevel() ?? 0;
    if (absoluteIndex < baseLevel) {
      const current = [...this.draft.classIds()];
      while (current.length <= absoluteIndex) {
        current.push(null);
      }
      current[absoluteIndex] = value as number | null;
      this.draft.classIds.set(current);
      return;
    }
    const bonusIndex = absoluteIndex - baseLevel;
    switch (this.draft.ageBracket()) {
      case 'maduro':
        this.draft.maduroClassId.set(value as number | null);
        break;
      case 'velho': {
        const current = [...this.draft.velhoClassIds()];
        current[bonusIndex] = value as number | null;
        this.draft.velhoClassIds.set(current);
        break;
      }
      case 'anciao': {
        const current = [...this.draft.anciaoClassIds()];
        current[bonusIndex] = value as number | null;
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

  protected get draftLinhagemPowerId() {
    return this.draft.linhagemPowerId;
  }

  protected readonly canContinue = computed(
    () =>
      this.rows().length > 0 &&
      this.rows().every((row) => this.classIdAt(row.absoluteIndex) !== null) &&
      (this.firstArcanistaRowIndex() === -1 || this.draft.arcanistaPathPowerId() !== null) &&
      (this.draft.arcanistaPathPowerId() !== FEITICEIRO_POWER_ID || this.draft.linhagemPowerId() !== null),
  );

  back(): void {
    this.router.navigate(['/character-creation-origin-step']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-god-step']);
  }
}
