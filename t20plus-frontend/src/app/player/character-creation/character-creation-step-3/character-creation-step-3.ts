import { Component, computed, inject } from '@angular/core';
import { Router } from '@angular/router';
import { DiceBadge } from '../../../shared/dice-badge/dice-badge';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';

@Component({
  selector: 'app-character-creation-step-3',
  imports: [DiceBadge, SearchableDropdown],
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
    const levelCount = this.draft.level() ?? 0;
    return Array.from({ length: levelCount }, (_, index) => ({
      index,
      label: index === 0 ? 'Classe Inicial' : `Nível ${index + 1}`,
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
    // Step 4 doesn't exist yet.
  }
}
