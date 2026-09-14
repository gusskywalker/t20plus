import { Component, ViewChild, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { CharacterDraft } from '../character-draft';
import { CharacterCreationSaving } from '../character-creation-saving/character-creation-saving';
import { resolveCasterSpellSlots } from '../resolve-caster-spell-slots';
import { resolveAvailableSpellOptions } from '../../../shared/helpers/resolve-available-spell-options/resolve-available-spell-options';

@Component({
  selector: 'app-character-creation-spells-step',
  imports: [CardHeader, SearchableDropdown, CharacterCreationSaving],
  templateUrl: './character-creation-spells-step.html',
  styleUrl: './character-creation-spells-step.scss',
})
export class CharacterCreationSpellsStep {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  @ViewChild(CharacterCreationSaving) private saving!: CharacterCreationSaving;

  protected readonly slots = computed(() => resolveCasterSpellSlots(this.draft, this.staticRegistry.powers));

  constructor() {
    // Keep chosenSpellIds sized to match slots — a class/level/Caminho
    // change earlier in the wizard can grow or shrink the slot count, and
    // a stale longer/shorter array would misalign index-to-slot. Existing
    // picks at still-valid indices are preserved.
    effect(() => {
      const slotCount = this.slots().length;
      const current = this.draft.chosenSpellIds();
      if (current.length === slotCount) {
        return;
      }
      const next = Array.from({ length: slotCount }, (_, i) => current[i] ?? null);
      this.draft.chosenSpellIds.set(next);
    });
  }

  protected slotLabel(cap: number): string {
    return `Magia (${cap}º Círculo)`;
  }

  // Every arcana spell at or below this slot's cap, minus whatever's
  // already picked in a DIFFERENT slot (a character shouldn't know the
  // same spell twice) — except this slot's own current pick, which has to
  // stay in its own list or the dropdown would show a blank label for a
  // value it can't find, same carve-out convention as every other
  // "already granted elsewhere" dropdown in this wizard.
  protected optionsForSlot(index: number): { id: number; name: string }[] {
    const slot = this.slots()[index];
    const cap = slot?.cap ?? 0;
    const ownPick = this.draft.chosenSpellIds()[index] ?? null;
    const chosenElsewhere = new Set(this.draft.chosenSpellIds().filter((id, i) => id !== null && i !== index));
    const options = resolveAvailableSpellOptions({
      spells: this.staticRegistry.spells,
      classId: slot?.classId ?? -1,
      cap,
      granted: this.draft.grantedPowerIds(),
      powers: this.staticRegistry.powers,
    });
    return options.filter((spell) => !chosenElsewhere.has(spell.id) || spell.id === ownPick);
  }

  protected chosenSpellIdAt(index: number): number | null {
    return this.draft.chosenSpellIds()[index] ?? null;
  }

  protected setChosenSpellIdAt(index: number, value: number | string | null): void {
    const current = [...this.draft.chosenSpellIds()];
    current[index] = (value as number | null) ?? null;
    this.draft.chosenSpellIds.set(current);
  }

  protected readonly canContinue = computed(() => {
    const chosen = this.draft.chosenSpellIds();
    return this.slots().every((_, i) => chosen[i] !== null && chosen[i] !== undefined);
  });

  back(): void {
    this.router.navigate(['/character-creation-powers-step']);
  }

  continue(): void {
    this.saving.save();
  }
}
