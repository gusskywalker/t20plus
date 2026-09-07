import { CharacterInventoryRow, Effect, ItemEnchantment, ItemImprovement, Power } from '../../../api.service';

/**
 * Every power ONE inventory item's own improvement_ids/enchantment_ids
 * actually grant — the shared walk behind both getItemGrantedEffects
 * (flattens these powers' own effects, for calculators) and
 * getItemGrantedPowers (the powers themselves, for a card list showing
 * their name/icon). Deliberately scoped to just this item: never merged
 * with the character's own active_effects, since an item-granted power
 * (Farpada, Cruel, etc.) only applies while that specific physical item is
 * the one in play (see tag-system.md's item-vs-character resolution split).
 *
 * Each improvement/enchantment never carries a tag directly — it only ever
 * grants a power (`{tag: 'power', op: 'grant', power_id, when_category?,
 * when_type?}`). when_category/when_type narrow a multi-branch grant (e.g.
 * Matéria Vermelha) to this item's actual item_type/type — a grant with
 * neither qualifier always applies.
 */
function resolveGrantedPowers(
  inventoryRow: Pick<CharacterInventoryRow, 'item_type' | 'improvement_ids' | 'enchantment_ids'>,
  itemImprovements: ItemImprovement[],
  itemEnchantments: ItemEnchantment[],
  powers: Power[],
  type: string | null,
): Power[] {
  const granted: Power[] = [];

  const collect = (grants: { effects: Effect[] | null }[]) => {
    for (const grant of grants) {
      for (const grantEffect of grant.effects ?? []) {
        if (grantEffect.tag !== 'power' || grantEffect.op !== 'grant' || grantEffect.power_id === undefined) {
          continue;
        }
        if (grantEffect.when_category && grantEffect.when_category !== inventoryRow.item_type) {
          continue;
        }
        if (grantEffect.when_type && grantEffect.when_type !== type) {
          continue;
        }
        const power = powers.find((p) => p.id === grantEffect.power_id);
        if (power) {
          granted.push(power);
        }
      }
    }
  };

  collect((inventoryRow.improvement_ids ?? []).map((id) => itemImprovements.find((i) => i.id === id)).filter((i): i is ItemImprovement => !!i));
  collect((inventoryRow.enchantment_ids ?? []).map((id) => itemEnchantments.find((e) => e.id === id)).filter((e): e is ItemEnchantment => !!e));

  return granted;
}

/** Flattens the granted powers' own effects — feed straight into calculateMargin/calculateMultiplier/calculateWeaponDice/etc. */
export function getItemGrantedEffects(
  inventoryRow: Pick<CharacterInventoryRow, 'item_type' | 'improvement_ids' | 'enchantment_ids'>,
  itemImprovements: ItemImprovement[],
  itemEnchantments: ItemEnchantment[],
  powers: Power[],
  type: string | null,
): Effect[] {
  return resolveGrantedPowers(inventoryRow, itemImprovements, itemEnchantments, powers, type).flatMap((power) => power.effects ?? []);
}

/** The granted powers themselves — for a card list showing each one's own name/icon. */
export function getItemGrantedPowers(
  inventoryRow: Pick<CharacterInventoryRow, 'item_type' | 'improvement_ids' | 'enchantment_ids'>,
  itemImprovements: ItemImprovement[],
  itemEnchantments: ItemEnchantment[],
  powers: Power[],
  type: string | null,
): Power[] {
  return resolveGrantedPowers(inventoryRow, itemImprovements, itemEnchantments, powers, type);
}
