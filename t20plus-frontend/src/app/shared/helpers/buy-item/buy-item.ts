import { Accessory, Armor, GeneralItem, Shield, Weapon } from '../../../api.service';
import { SecondarySegment } from '../../inputs/searchable-dropdown/searchable-dropdown';

export type ShopItemSource = 'weapon' | 'armor' | 'shield' | 'accessory' | 'general_item';

export interface ShopItem {
  // Synthetic "source:id" key, not a bare number — weapons/armors/shields/
  // accessories each have their own independent id sequence, so a plain
  // numeric id would collide across catalogs (e.g. weapon 1 and armor 1
  // aren't the same item). Parse it back with parseShopItemKey when you
  // actually need to resolve the purchase.
  id: string;
  name: string;
  cost: number;
}

/**
 * Merges every purchasable item across all four catalogs into one list —
 * used by the character creation wizard's Comprar Item step and (later)
 * the character sheet's own shopping flow. -1 cost means "not purchasable"
 * (e.g. accessories.cost on a devotion item like Símbolo Sagrado) and is
 * filtered out everywhere, not just accessories, in case another source
 * ever reuses the same sentinel.
 */
export function buildShopItems(
  weapons: Weapon[],
  armors: Armor[],
  shields: Shield[],
  accessories: Accessory[],
  generalItems: GeneralItem[],
): ShopItem[] {
  return [
    ...weapons
      .filter((w) => w.cost >= 0)
      .map((w) => ({ id: `weapon:${w.id}`, name: w.name, cost: w.cost })),
    ...armors
      .filter((a) => a.cost >= 0)
      .map((a) => ({ id: `armor:${a.id}`, name: a.name, cost: a.cost })),
    ...shields
      .filter((s) => s.cost >= 0)
      .map((s) => ({ id: `shield:${s.id}`, name: s.name, cost: s.cost })),
    ...accessories
      .filter((a) => a.cost >= 0)
      .map((a) => ({ id: `accessory:${a.id}`, name: a.name, cost: a.cost })),
    ...generalItems
      .filter((g) => g.cost >= 0)
      .map((g) => ({ id: `general_item:${g.id}`, name: g.name, cost: g.cost })),
  ];
}

/** Splits a ShopItem's synthetic "source:id" key back into its parts. */
export function parseShopItemKey(key: string): { source: ShopItemSource; id: number } {
  const [source, id] = key.split(':');
  return { source: source as ShopItemSource, id: Number(id) };
}

// Ammo is always bought in fixed-size bundles that each land as their
// own character_inventory row (quantity = bundle size), never a row holding
// however many were "bought" — see GeneralItemSeeder.php's Flechas (20).
// Hardcoded per item id since different ammo types can bundle differently;
// not derivable from any general_items column (that catalog deliberately
// carries no quantity/bundle-size field of its own). Shared by the runtime
// Comprar Item modal and the character-creation wizard's own purchase step
// so both apply the exact same bundle size instead of two copies drifting.
const AMMO_BUNDLE_SIZES: Record<number, number> = {
  2: 20, // Flechas (20)
  3: 20, // Munição (20)
};

/** undefined = this general_item id isn't ammo sold in a bundle. */
export function ammoBundleSize(generalItemId: number): number | undefined {
  return AMMO_BUNDLE_SIZES[generalItemId];
}

/**
 * secondaryFn factory for app-searchable-dropdown — shows "T$ {cost}",
 * pt-BR formatted, painted faded red once the cost would exceed what's
 * currently left to spend (same threshold as shopItemNameColor, so the
 * name and the cost go red together). Runs over NENHUM_SHOP_ITEM too
 * (same list, same dropdown) — it has no cost at all, so that gets no
 * segment rather than crashing on it.
 */
export function shopItemPrice(remainingTibares: number) {
  return (item: ShopItem): SecondarySegment[] => {
    if (item.cost === undefined) {
      return [];
    }
    return [
      {
        text: `T$ ${item.cost.toLocaleString('pt-BR')}`,
        color: item.cost > remainingTibares ? 'var(--color-tormenta-red)' : undefined,
      },
    ];
  };
}

/** Hardcoded "Nenhum" option — id null means "no purchase in this slot." */
export const NENHUM_SHOP_ITEM = { id: null, name: 'Nenhum' };

/**
 * A repeating "buy another item" picker: N picked slots plus always one
 * trailing empty slot to add the next purchase. Multiple slots can point
 * at the same item on purpose (buying two daggers is normal shopping,
 * unlike the mutually-exclusive picks elsewhere in this app).
 *
 * Ensures there's always exactly one trailing null after the last real
 * pick — call this after every change to a purchase slot.
 */
export function growPurchaseSlots(current: (string | null)[]): (string | null)[] {
  if (current.length === 0 || current[current.length - 1] !== null) {
    return [...current, null];
  }
  // Already ends in exactly one null — trim any extra trailing nulls a
  // slot removal might have left behind, down to just the one.
  let end = current.length;
  while (end > 1 && current[end - 2] === null) {
    end--;
  }
  return current.slice(0, end);
}

/** Tibares left after every purchased slot's cost is subtracted from the base amount. */
export function calculateRemainingTibares(
  baseTibares: number,
  purchasedKeys: (string | null)[],
  shopItems: ShopItem[],
): number {
  const spent = purchasedKeys.reduce((sum, key) => {
    if (key === null) {
      return sum;
    }
    const item = shopItems.find((i) => i.id === key);
    return sum + (item?.cost ?? 0);
  }, 0);
  return baseTibares - spent;
}

/**
 * nameColorFn factory for app-searchable-dropdown's Comprar Item list —
 * paints an item's name faded red once its cost would exceed what's
 * currently left to spend, so the player sees it's unaffordable before
 * picking it. NENHUM_SHOP_ITEM has no cost at all, so it never paints.
 */
export function shopItemNameColor(remainingTibares: number) {
  return (item: ShopItem): string | null =>
    item.cost !== undefined && item.cost > remainingTibares
      ? 'var(--color-tormenta-red)'
      : null;
}
