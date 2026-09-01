import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';

@Component({
  selector: 'app-character-creation-step-3',
  imports: [CardHeader, SearchableDropdown],
  templateUrl: './character-creation-step-3.html',
  styleUrl: './character-creation-step-3.scss',
})
export class CharacterCreationStep3 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  constructor() {
    // Dev convenience: pre-fill every row with the first available class so
    // this screen doesn't need manual clicking through every test run. Only
    // fills rows that are still unset. TODO: remove once this stops being
    // useful during development.
    effect(() => {
      const classes = this.staticRegistry.classes;
      if (classes.length === 0) {
        return;
      }
      const rows = this.rows();
      if (rows.length === 0) {
        return;
      }
      const current = this.draft.classIds();
      if (rows.every((row) => (current[row.index] ?? null) !== null)) {
        return;
      }
      const filled = [...current];
      for (const row of rows) {
        if ((filled[row.index] ?? null) === null) {
          filled[row.index] = classes[0].id;
        }
      }
      this.draft.classIds.set(filled);
    });
  }

  protected get classes() {
    return this.staticRegistry.classes;
  }

  protected readonly rows = computed(() => {
    const levelCount = this.draft.level() ?? 0;
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

  protected readonly canContinue = computed(
    () => this.rows().length > 0 && this.rows().every((row) => this.classIdAt(row.index) !== null),
  );

  back(): void {
    this.router.navigate(['/character-creation-step-2']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-step-4']);
  }
}
