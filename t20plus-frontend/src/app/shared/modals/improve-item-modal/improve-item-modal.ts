import { Component, computed, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterInventoryRow, ItemEnchantment, ItemImprovement, ItemRestrictions } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { spendTibares } from '../../helpers/spend-tibares/spend-tibares';
import { UseCharacter } from '../../hooks/use-character';
import { StaticRegistry } from '../../hooks/static-registry';
import { SearchableDropdown } from '../../inputs/searchable-dropdown/searchable-dropdown';

// Prepended so each melhoria/encantamento dropdown can be explicitly left
// empty rather than forced to pick something — feminine/masculine to match
// "melhoria"/"encantamento".
const NENHUMA = { id: null, name: 'Nenhuma' };
const NENHUM = { id: null, name: 'Nenhum' };

// claude-stuff/rules/item-improvements-enchantments.md's own price table —
// the Nth pick (1-indexed) costs this step's price, on top of a material's
// own extra_cost if that pick is one. Two independent counters (melhorias/
// encantamentos never share a step count).
const MELHORIA_STEP_COSTS = [300, 3000, 9000, 18000];
const ENCANTAMENTO_STEP_COSTS = [18000, 36000, 72000];

@Component({
  selector: 'app-improve-item-modal',
  imports: [SearchableDropdown],
  templateUrl: './improve-item-modal.html',
  styleUrl: './improve-item-modal.scss',
})
export class ImproveItemModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  cancel = output<void>();

  protected readonly currentPage = signal(1);

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  // Only the categories item_improvements/item_enchantments actually cover
  // (see ItemImprovementSeeder) — weapons, armors, and general_items of
  // type tools/ammunition. No shields/accessories.
  protected weaponRows(): { inventoryRow: CharacterInventoryRow; name: string; iconFileName: string | undefined }[] {
    const rows: { inventoryRow: CharacterInventoryRow; name: string; iconFileName: string | undefined }[] = [];
    for (const item of this.character().inventory ?? []) {
      if (item.item_type !== 'weapon') {
        continue;
      }
      const weapon = this.staticRegistry.weapons.find((w) => w.id === item.item_id);
      if (!weapon) {
        continue;
      }
      rows.push({ inventoryRow: item, name: weapon.name, iconFileName: weapon.icon_file_name ?? undefined });
    }
    return rows;
  }

  protected armorRows(): { inventoryRow: CharacterInventoryRow; name: string; iconFileName: string | undefined }[] {
    const rows: { inventoryRow: CharacterInventoryRow; name: string; iconFileName: string | undefined }[] = [];
    for (const item of this.character().inventory ?? []) {
      if (item.item_type !== 'armor') {
        continue;
      }
      const armor = this.staticRegistry.armors.find((a) => a.id === item.item_id);
      if (!armor) {
        continue;
      }
      rows.push({ inventoryRow: item, name: armor.name, iconFileName: armor.icon_file_name ?? undefined });
    }
    return rows;
  }

  protected generalItemRows(): { inventoryRow: CharacterInventoryRow; name: string; iconFileName: string | undefined }[] {
    const rows: { inventoryRow: CharacterInventoryRow; name: string; iconFileName: string | undefined }[] = [];
    for (const item of this.character().inventory ?? []) {
      if (item.item_type !== 'general_item') {
        continue;
      }
      const generalItem = this.staticRegistry.generalItems.find((g) => g.id === item.item_id);
      if (!generalItem || (generalItem.type !== 'tools' && generalItem.type !== 'ammunition')) {
        continue;
      }
      rows.push({ inventoryRow: item, name: generalItem.name, iconFileName: generalItem.icon_file_name ?? undefined });
    }
    return rows;
  }

  // Which inventory row page 2's 7 dropdowns are being built against — set
  // by clicking a page-1 card.
  protected readonly selectedInventoryRow = signal<CharacterInventoryRow | null>(null);

  protected selectItem(inventoryRow: CharacterInventoryRow): void {
    this.selectedInventoryRow.set(inventoryRow);

    const improvementIds = inventoryRow.improvement_ids ?? [];
    this.melhoria1Id.set(improvementIds[0] ?? null);
    this.melhoria2Id.set(improvementIds[1] ?? null);
    this.melhoria3Id.set(improvementIds[2] ?? null);
    this.melhoria4Id.set(improvementIds[3] ?? null);

    const enchantmentIds = inventoryRow.enchantment_ids ?? [];
    this.encantamento1Id.set(enchantmentIds[0] ?? null);
    this.encantamento2Id.set(enchantmentIds[1] ?? null);
    this.encantamento3Id.set(enchantmentIds[2] ?? null);

    this.currentPage.set(2);
  }

  // How many of the 4 melhoria/3 encantamento slots are already saved on the
  // item — those slots are preloaded, locked, and excluded from
  // currentCost() (already paid for). Always the leading slots, since
  // selectItem() fills them in the same order as improvement_ids/
  // enchantment_ids.
  protected preloadedMelhoriaCount(): number {
    return (this.selectedInventoryRow()?.improvement_ids ?? []).length;
  }

  protected preloadedEncantamentoCount(): number {
    return (this.selectedInventoryRow()?.enchantment_ids ?? []).length;
  }

  protected goBack(): void {
    this.currentPage.set(1);
    this.selectedInventoryRow.set(null);
  }

  protected handleCancel(): void {
    if (this.currentPage() === 2) {
      this.goBack();
      return;
    }
    this.cancel.emit();
  }

  protected salvar(): void {
    const item = this.selectedInventoryRow();
    if (!item) {
      return;
    }
    const cost = this.currentCost();
    const improvement_ids = this.pickedMelhoriaIds();
    const enchantment_ids = this.pickedEncantamentoIds();

    // Fired independently, not chained — same fire-and-forget pattern as
    // spendPm/spendPv (see attack-modal's roll()), each patches its own
    // cache slice whenever it resolves instead of waiting on the other.
    this.apiService.updateCharacterInventoryItem(this.character().id, item.id, { improvement_ids, enchantment_ids }).subscribe((inventory) => {
      this.useCharacter.patchCharacterCache(this.id(), { inventory });
    });
    spendTibares(this.apiService, this.useCharacter, this.id(), this.character(), cost);

    this.cancel.emit();
  }

  // Does the candidate's own categories cover the selected item's item_type?
  private categoryMatches(candidate: ItemImprovement | ItemEnchantment): boolean {
    const item = this.selectedInventoryRow();
    return item !== null && candidate.categories.includes(item.item_type);
  }

  // Does the candidate's restrictions (if any) match the selected item's own
  // grip/purpose/damage_type/is_firearm (weapon) or type (general_item)?
  // Armor has no restriction fields defined yet, so armor candidates always
  // pass here.
  private restrictionsMatches(restrictions: ItemRestrictions | null): boolean {
    if (!restrictions) {
      return true;
    }
    const item = this.selectedInventoryRow();
    if (!item) {
      return false;
    }
    if (item.item_type === 'weapon') {
      const weapon = this.staticRegistry.weapons.find((w) => w.id === item.item_id);
      if (!weapon) {
        return false;
      }
      if (restrictions.grip && weapon.grip !== restrictions.grip) {
        return false;
      }
      if (restrictions.purpose && weapon.purpose !== restrictions.purpose) {
        return false;
      }
      if (restrictions.damage_type && weapon.damage_type !== restrictions.damage_type) {
        return false;
      }
      if (restrictions.is_firearm !== undefined && weapon.is_firearm !== restrictions.is_firearm) {
        return false;
      }
      return true;
    }
    if (item.item_type === 'general_item') {
      const generalItem = this.staticRegistry.generalItems.find((g) => g.id === item.item_id);
      if (!generalItem) {
        return false;
      }
      if (restrictions.type && generalItem.type !== restrictions.type) {
        return false;
      }
      return true;
    }
    return true;
  }

  // Which extra_cost key applies to the selected item — is_exoteric always
  // wins over the category-specific key (see tag-library.md). null for
  // general_item since no extra_cost category is defined for it yet.
  private extraCostCategoryKey(): string | null {
    const item = this.selectedInventoryRow();
    if (!item) {
      return null;
    }
    if (item.item_type === 'weapon') {
      const weapon = this.staticRegistry.weapons.find((w) => w.id === item.item_id);
      return weapon?.is_exoteric ? 'exoterics' : 'weapons';
    }
    if (item.item_type === 'shield') {
      const shield = this.staticRegistry.shields.find((s) => s.id === item.item_id);
      return shield?.is_exoteric ? 'exoterics' : 'shields';
    }
    if (item.item_type === 'armor') {
      const armor = this.staticRegistry.armors.find((a) => a.id === item.item_id);
      if (armor?.is_exoteric) {
        return 'exoterics';
      }
      if (armor?.type === 'light') {
        return 'light_armors';
      }
      if (armor?.type === 'heavy') {
        return 'heavy_armors';
      }
      if (armor?.type === 'vestment') {
        return 'vestments';
      }
    }
    return null;
  }

  // Total cost of whatever's currently picked across all 7 slots — step
  // price by position (see MELHORIA_STEP_COSTS/ENCANTAMENTO_STEP_COSTS)
  // plus a material's own extra_cost, order-independent since it's just a
  // sum (see item-improvements-enchantments.md's worked example).
  protected currentCost(): number {
    const categoryKey = this.extraCostCategoryKey();
    const preloadedMelhoriaCount = this.preloadedMelhoriaCount();
    const preloadedEncantamentoCount = this.preloadedEncantamentoCount();

    const melhoriaCost = this.pickedMelhoriaIds().reduce((sum, id, index) => {
      if (index < preloadedMelhoriaCount) {
        return sum;
      }
      const improvement = this.staticRegistry.itemImprovements.find((i) => i.id === id);
      const stepCost = MELHORIA_STEP_COSTS[index] ?? 0;
      const materialCost = improvement?.is_material && categoryKey ? (improvement.extra_cost?.[categoryKey] ?? 0) : 0;
      return sum + stepCost + materialCost;
    }, 0);

    const encantamentoCost = this.pickedEncantamentoIds().reduce((sum, _id, index) => {
      if (index < preloadedEncantamentoCount) {
        return sum;
      }
      return sum + (ENCANTAMENTO_STEP_COSTS[index] ?? 0);
    }, 0);

    return melhoriaCost + encantamentoCost;
  }

  // Salvar is gated on there being an actual change to save. Once
  // pre-loading existing improvement_ids/enchantment_ids into the slots is
  // built, a re-visit with nothing new picked will also cost 0 here, so this
  // gate covers that case for free.
  protected readonly canContinue = computed(() => this.currentCost() !== 0);

  // Melhoria 1-4 — own signal per slot, own setter per slot (clears every
  // later slot when a slot is set back to Nenhuma or changed, so a stale
  // pick can't survive under a now-disabled dropdown), own items() method
  // per slot.
  protected readonly melhoria1Id = signal<number | null>(null);
  protected readonly melhoria2Id = signal<number | null>(null);
  protected readonly melhoria3Id = signal<number | null>(null);
  protected readonly melhoria4Id = signal<number | null>(null);

  protected setMelhoria1Id(value: number | string | null): void {
    this.melhoria1Id.set(value as number | null);
    this.melhoria2Id.set(null);
    this.melhoria3Id.set(null);
    this.melhoria4Id.set(null);
  }

  protected setMelhoria2Id(value: number | string | null): void {
    this.melhoria2Id.set(value as number | null);
    this.melhoria3Id.set(null);
    this.melhoria4Id.set(null);
  }

  protected setMelhoria3Id(value: number | string | null): void {
    this.melhoria3Id.set(value as number | null);
    this.melhoria4Id.set(null);
  }

  protected setMelhoria4Id(value: number | string | null): void {
    this.melhoria4Id.set(value as number | null);
  }

  // Every currently picked melhoria id (across all 4 slots), used to check
  // prerequisites/incompatible_ids and to hide an id already picked in
  // another slot.
  private pickedMelhoriaIds(): number[] {
    return [this.melhoria1Id(), this.melhoria2Id(), this.melhoria3Id(), this.melhoria4Id()].filter((id): id is number => id !== null);
  }

  private eligibleMelhorias(ownPick: number | null): ItemImprovement[] {
    const pickedElsewhere = new Set(this.pickedMelhoriaIds().filter((id) => id !== ownPick));
    // Items can only have one is_material improvement (Adamante and Matéria
    // Vermelha can't both apply to the same item) — see
    // item-improvements-enchantments.md.
    const materialAlreadyPickedElsewhere = this.staticRegistry.itemImprovements.some(
      (i) => pickedElsewhere.has(i.id) && i.is_material,
    );
    return this.staticRegistry.itemImprovements
      .filter((improvement) => {
        if (improvement.id === ownPick) {
          return true;
        }
        if (pickedElsewhere.has(improvement.id)) {
          return false;
        }
        if (!this.categoryMatches(improvement) || !this.restrictionsMatches(improvement.restrictions)) {
          return false;
        }
        if (improvement.is_material && materialAlreadyPickedElsewhere) {
          return false;
        }
        if ((improvement.prerequisites ?? []).some((id) => !pickedElsewhere.has(id))) {
          return false;
        }
        if ((improvement.incompatible_ids ?? []).some((id) => pickedElsewhere.has(id))) {
          return false;
        }
        return true;
      })
      .sort((a, b) => a.name.localeCompare(b.name, 'pt-BR'));
  }

  protected melhoria1Items() {
    return [NENHUMA, ...this.eligibleMelhorias(this.melhoria1Id())];
  }

  protected melhoria2Items() {
    return [NENHUMA, ...this.eligibleMelhorias(this.melhoria2Id())];
  }

  protected melhoria3Items() {
    return [NENHUMA, ...this.eligibleMelhorias(this.melhoria3Id())];
  }

  protected melhoria4Items() {
    return [NENHUMA, ...this.eligibleMelhorias(this.melhoria4Id())];
  }

  // Encantamento 1-3 — same shape as the melhoria slots, own signal/setter/
  // items() per slot, checked against item_enchantments' own prerequisites/
  // incompatible_ids (a separate id space from melhorias' — same-table only,
  // per tag-library.md).
  protected readonly encantamento1Id = signal<number | null>(null);
  protected readonly encantamento2Id = signal<number | null>(null);
  protected readonly encantamento3Id = signal<number | null>(null);

  protected setEncantamento1Id(value: number | string | null): void {
    this.encantamento1Id.set(value as number | null);
    this.encantamento2Id.set(null);
    this.encantamento3Id.set(null);
  }

  protected setEncantamento2Id(value: number | string | null): void {
    this.encantamento2Id.set(value as number | null);
    this.encantamento3Id.set(null);
  }

  protected setEncantamento3Id(value: number | string | null): void {
    this.encantamento3Id.set(value as number | null);
  }

  private pickedEncantamentoIds(): number[] {
    return [this.encantamento1Id(), this.encantamento2Id(), this.encantamento3Id()].filter((id): id is number => id !== null);
  }

  private eligibleEncantamentos(ownPick: number | null): ItemEnchantment[] {
    const pickedElsewhere = new Set(this.pickedEncantamentoIds().filter((id) => id !== ownPick));
    return this.staticRegistry.itemEnchantments
      .filter((enchantment) => {
        if (enchantment.id === ownPick) {
          return true;
        }
        if (pickedElsewhere.has(enchantment.id)) {
          return false;
        }
        if (!this.categoryMatches(enchantment) || !this.restrictionsMatches(enchantment.restrictions)) {
          return false;
        }
        if ((enchantment.prerequisites ?? []).some((id) => !pickedElsewhere.has(id))) {
          return false;
        }
        if ((enchantment.incompatible_ids ?? []).some((id) => pickedElsewhere.has(id))) {
          return false;
        }
        return true;
      })
      .sort((a, b) => a.name.localeCompare(b.name, 'pt-BR'));
  }

  protected encantamento1Items() {
    return [NENHUM, ...this.eligibleEncantamentos(this.encantamento1Id())];
  }

  protected encantamento2Items() {
    return [NENHUM, ...this.eligibleEncantamentos(this.encantamento2Id())];
  }

  protected encantamento3Items() {
    return [NENHUM, ...this.eligibleEncantamentos(this.encantamento3Id())];
  }
}
