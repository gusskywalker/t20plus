import { Component, computed, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { ArcanistaPathSection } from '../../../shared/arcanista-path-section/arcanista-path-section';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';

const ARCANISTA_CLASS_ID = 3;

@Component({
  selector: 'app-character-creation-step-3',
  imports: [CardHeader, SearchableDropdown, ArcanistaPathSection],
  templateUrl: './character-creation-step-3.html',
  styleUrl: './character-creation-step-3.scss',
})
export class CharacterCreationStep3 {
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

  // The one row (lowest index) whose class is Arcanista — every later row
  // picking Arcanista again is just a further level in the same path
  // already chosen, so only this row shows the Caminho section.
  protected readonly firstArcanistaRowIndex = computed(() => {
    const classIds = this.draft.classIds();
    return classIds.findIndex((classId) => classId === ARCANISTA_CLASS_ID);
  });

  protected isFirstArcanistaRow(index: number): boolean {
    return this.firstArcanistaRowIndex() === index;
  }

  protected get draftArcanistaPathPowerId() {
    return this.draft.arcanistaPathPowerId;
  }

  protected readonly canContinue = computed(
    () =>
      this.rows().length > 0 &&
      this.rows().every((row) => this.classIdAt(row.index) !== null) &&
      (this.firstArcanistaRowIndex() === -1 || this.draft.arcanistaPathPowerId() !== null),
  );

  back(): void {
    this.router.navigate(['/character-creation-step-2']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-step-4']);
  }
}
