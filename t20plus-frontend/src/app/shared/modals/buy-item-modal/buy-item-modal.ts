import { Component, computed, effect, inject, input, output, signal } from '@angular/core';
import { ApiService, Character } from '../../../api.service';
import { ammoBundleSize, buildShopItems, NENHUM_SHOP_ITEM, parseShopItemKey, shopItemNameColor, shopItemPrice } from '../../helpers/buy-item/buy-item';
import { naturalWeaponSize } from '../../helpers/natural-weapon-size/natural-weapon-size';
import { WEAPON_SIZE_ITEMS } from '../../helpers/weapon-size-label/weapon-size-label';
import { spendTibares } from '../../helpers/spend-tibares/spend-tibares';
import { StaticRegistry } from '../../hooks/static-registry';
import { UseCharacter } from '../../hooks/use-character';
import { NumberInput } from '../../inputs/number-input/number-input';
import { SearchableDropdown } from '../../inputs/searchable-dropdown/searchable-dropdown';

@Component({
  selector: 'app-buy-item-modal',
  imports: [NumberInput, SearchableDropdown],
  templateUrl: './buy-item-modal.html',
  styleUrl: './buy-item-modal.scss',
})
export class BuyItemModal {
  private readonly staticRegistry = inject(StaticRegistry);
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  cancel = output<void>();

  protected readonly weaponSizeItems = WEAPON_SIZE_ITEMS;

  // Every purchasable item across all four catalogs, same merge buy-item.ts
  // already does for character-creation-step-9's own Comprar Item step —
  // reused as-is, not rebuilt here.
  private readonly shopItems = computed(() =>
    buildShopItems(
      this.staticRegistry.weapons,
      this.staticRegistry.armors,
      this.staticRegistry.shields,
      this.staticRegistry.accessories,
      this.staticRegistry.generalItems,
    ),
  );

  protected readonly shopDropdownItems = computed(() => [NENHUM_SHOP_ITEM, ...this.shopItems()]);

  // Nothing's been spent yet in this single-purchase modal — remaining is
  // just the character's own current tibares, unlike step 9's wizard-draft
  // budget which subtracts every other pending purchase slot too.
  protected readonly shopItemPriceFn = computed(() => shopItemPrice(this.character().tibares));

  protected readonly shopItemNameColorFn = computed(() => shopItemNameColor(this.character().tibares));

  protected readonly selectedItemKey = signal<string | null>(null);

  protected setSelectedItemKey(value: string | number | null): void {
    this.selectedItemKey.set(value as string | null);
  }

  protected isWeaponSelected(): boolean {
    const key = this.selectedItemKey();
    return key !== null && parseShopItemKey(key).source === 'weapon';
  }

  protected readonly weaponSizeId = signal<number | null>(null);

  protected setWeaponSizeId(value: number | string | null): void {
    this.weaponSizeId.set(value as number | null);
  }

  // Quantidade — only for potions (stackable, the typed number is exactly
  // how many are bought). Ammo never shows this field: it's always
  // bought one bundle at a time (see ammoBundleSize in buy-item.ts) — a
  // free-typed number here would invite someone to type the arrow count
  // (e.g. 20) instead of a bundle count, so there's no input to misread.
  protected isPotionSelected(): boolean {
    const key = this.selectedItemKey();
    if (key === null) {
      return false;
    }
    const { source, id } = parseShopItemKey(key);
    if (source !== 'general_item') {
      return false;
    }
    const generalItem = this.staticRegistry.generalItems.find((g) => g.id === id);
    return generalItem?.type === 'potion';
  }

  protected readonly quantidade = signal<number | null>(1);

  constructor() {
    // Every time a weapon is (re)selected, reset Tamanho back to the
    // character's own natural grip — the race's base_size, not
    // current_size (buying gear reflects your race, not a temporary size
    // change) — run through naturalWeaponSize (Weapon Sizes,
    // claude-stuff/rules/weapon-rules.md). The player can still override it
    // afterward; this only fires again on the next item selection change.
    effect(() => {
      const key = this.selectedItemKey();
      if (key === null || parseShopItemKey(key).source !== 'weapon') {
        return;
      }
      const race = this.staticRegistry.races.find((r) => r.id === this.character().race_id);
      this.weaponSizeId.set(naturalWeaponSize(race?.base_size ?? 0));
    });

    // Same idea — every time a potion is (re)selected, reset Quantidade
    // back to 1.
    effect(() => {
      if (this.isPotionSelected()) {
        this.quantidade.set(1);
      }
    });
  }

  protected canContinue(): boolean {
    return this.selectedItemKey() !== null;
  }

  protected comprar(): void {
    const key = this.selectedItemKey();
    if (key === null) {
      return;
    }
    const { source, id } = parseShopItemKey(key);
    const unitCost = this.shopItems().find((i) => i.id === key)?.cost ?? 0;
    // Potion cost scales with however many are being bought — a flat unit
    // cost regardless of Quantidade would undercharge for anything past
    // the first one.
    const totalCost = this.isPotionSelected() ? unitCost * (this.quantidade() ?? 1) : unitCost;

    // Ammo is always exactly one bundle per purchase — this modal
    // closes on every Comprar (see cancel.emit() below) same as any other
    // item, so buying more is just reopening it and buying again, not a
    // count to loop over here.
    const bundleSize = source === 'general_item' ? ammoBundleSize(id) : undefined;
    if (bundleSize !== undefined) {
      this.apiService
        .createCharacterInventoryItem(this.character().id, { item_type: 'general_item', item_id: id, quantity: bundleSize })
        .subscribe((inventory) => {
          this.useCharacter.patchCharacterCache(this.id(), { inventory });
        });
      spendTibares(this.apiService, this.useCharacter, this.id(), this.character(), totalCost);
      this.cancel.emit();
      return;
    }

    const payload: { item_type: typeof source; item_id: number; quantity?: number; weapon_size?: number } = {
      item_type: source,
      item_id: id,
    };
    if (source === 'weapon') {
      payload.weapon_size = this.weaponSizeId() ?? 0;
    }
    if (this.isPotionSelected()) {
      payload.quantity = this.quantidade() ?? 1;
    }

    // Fired independently, not chained — same fire-and-forget pattern as
    // spendPm/spendPv (see attack-modal's roll()), each patches its own
    // cache slice whenever it resolves instead of waiting on the other.
    this.apiService.createCharacterInventoryItem(this.character().id, payload).subscribe((inventory) => {
      this.useCharacter.patchCharacterCache(this.id(), { inventory });
    });
    spendTibares(this.apiService, this.useCharacter, this.id(), this.character(), totalCost);

    this.cancel.emit();
  }
}
