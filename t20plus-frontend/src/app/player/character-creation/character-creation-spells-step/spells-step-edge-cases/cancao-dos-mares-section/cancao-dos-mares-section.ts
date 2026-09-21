import { Component, computed, inject, input, model } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';

// Sereia/Tritão's Canção dos Mares — "duas das magias a seguir: Amedrontar,
// Comando, Despedaçar, Enfeitiçar, Hipnotismo ou Sono," a fixed pool, not a
// class-gated one (spans both arcana and divina spells), so it's hardcoded
// here same as Qareen's own ancestry pick. Both chosen spell_ids become
// custom_effects on power 16056's two grant_or_reduce_spell_pm_cost_by_1
// effects at save time (character-payload.ts).
const CANCAO_DOS_MARES_SPELL_IDS = [13, 6, 1001, 27, 28, 4];

@Component({
  selector: 'app-cancao-dos-mares-section',
  imports: [SearchableDropdown],
  templateUrl: './cancao-dos-mares-section.html',
  styleUrl: './cancao-dos-mares-section.scss',
})
export class CancaoDosMaresSection {
  private staticRegistry = inject(StaticRegistry);

  spellIds = model<(number | null)[]>([null, null]);
  excludedSpellIds = input<ReadonlySet<number>>(new Set());

  protected readonly pool = computed(() => CANCAO_DOS_MARES_SPELL_IDS.map((id) => this.staticRegistry.spells.find((spell) => spell.id === id)).filter((spell) => spell !== undefined));

  // Same "already picked elsewhere, but keep this slot's own pick visible"
  // carve-out spells-step's own optionsForSlot uses.
  protected optionsFor(index: number) {
    const ownPick = this.spellIds()[index] ?? null;
    const chosenElsewhere = this.spellIds()[index === 0 ? 1 : 0] ?? null;
    return this.pool().filter((spell) => !this.excludedSpellIds().has(spell.id) && (spell.id !== chosenElsewhere || spell.id === ownPick));
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
