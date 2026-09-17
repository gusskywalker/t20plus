import { Component, computed, inject, model } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';

// Sílfide's Magia das Fadas — "duas das magias a seguir: Criar Ilusão,
// Enfeitiçar, Luz e Sono," a fixed pool, not a class-gated one (spans both
// arcana and universal spells), so it's hardcoded here same as Canção dos
// Mares' own pool. Both chosen spell_ids become custom_effects on power
// 16063's two grant_or_reduce_spell_pm_cost_by_1 effects at save time
// (character-payload.ts).
const MAGIA_DAS_FADAS_SPELL_IDS = [29, 27, 2002, 4];

@Component({
  selector: 'app-magia-das-fadas-section',
  imports: [SearchableDropdown],
  templateUrl: './magia-das-fadas-section.html',
  styleUrl: './magia-das-fadas-section.scss',
})
export class MagiaDasFadasSection {
  private staticRegistry = inject(StaticRegistry);

  spellIds = model<(number | null)[]>([null, null]);

  protected readonly pool = computed(() => MAGIA_DAS_FADAS_SPELL_IDS.map((id) => this.staticRegistry.spells.find((spell) => spell.id === id)).filter((spell) => spell !== undefined));

  // Same "already picked elsewhere, but keep this slot's own pick visible"
  // carve-out spells-step's own optionsForSlot uses.
  protected optionsFor(index: number) {
    const ownPick = this.spellIds()[index] ?? null;
    const chosenElsewhere = this.spellIds()[index === 0 ? 1 : 0] ?? null;
    return this.pool().filter((spell) => spell.id !== chosenElsewhere || spell.id === ownPick);
  }

  protected spellIdAt(index: number): number | null {
    return this.spellIds()[index] ?? null;
  }

  protected setSpellIdAt(index: number, value: number | string | null): void {
    const current = [...this.spellIds()];
    current[index] = (value as number | null) ?? null;
    this.spellIds.set(current);
  }
}
