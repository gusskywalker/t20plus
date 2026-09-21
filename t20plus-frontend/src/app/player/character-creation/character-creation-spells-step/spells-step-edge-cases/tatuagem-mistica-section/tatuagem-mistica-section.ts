import { Component, computed, inject, input, model } from '@angular/core';
import { SearchableDropdown } from '../../../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { StaticRegistry } from '../../../../../shared/hooks/static-registry';

// Qareen's Tatuagem Mística — "uma magia de 1º círculo a sua escolha," no
// class/type restriction at all, so every 1st-circle spell qualifies except
// 'specific' ones (reachable only through their own dedicated granting
// power, e.g. Olhar Atordoante — never freely pickable here). The chosen
// spell_id becomes a custom_effect on power 16055's grant_or_reduce_spell_
// pm_cost_by_1 effect at save time (character-payload.ts).
@Component({
  selector: 'app-tatuagem-mistica-section',
  imports: [SearchableDropdown],
  templateUrl: './tatuagem-mistica-section.html',
  styleUrl: './tatuagem-mistica-section.scss',
})
export class TatuagemMisticaSection {
  private staticRegistry = inject(StaticRegistry);

  spellId = model<number | null>(null);
  excludedSpellIds = input<ReadonlySet<number>>(new Set());

  protected readonly firstCircleSpells = computed(() =>
    this.staticRegistry.spells.filter((spell) => spell.circle === 1 && spell.type !== 'specific' && !this.excludedSpellIds().has(spell.id)),
  );
}
