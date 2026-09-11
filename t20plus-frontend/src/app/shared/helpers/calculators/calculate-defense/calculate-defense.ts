import { Armor, Character, Power, Shield } from '../../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';
import { resolveEffectSentinels } from '../../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveTag } from '../../tag-solver/tag-solver';

// Worn armor's own mod_def is treated as if it carried this stack_group —
// not a real field on Armor, just a fixed constant applied here so a
// spell/power can share it (e.g. Armadura Arcana: "cumulativo com outras
// magias, mas não com bônus fornecido por armaduras" — only the higher of
// the two survives resolveTag's own stack_group dedup, same mechanism
// Cruel/Atroz's mod_dmg already uses).
export const ARMOR_BONUS_STACK_GROUP = 'armor_bonus';

/**
 * Defesa — 10 + Destreza (only without armor or in light armor; heavy
 * armor blocks it entirely) + worn armor's mod_def (deduped against any
 * ARMOR_BONUS_STACK_GROUP power/spell effect) + any worn shield's mod_def +
 * any mod_def from active powers (e.g. Percepção Temporal's "value": "knw",
 * resolved to the character's current Conhecimento via resolveEffectSentinels
 * before summing).
 */
export function calculateDefense(character: Character, armors: Armor[], shields: Shield[], powers: Power[]): number {
  const inventory = character.inventory ?? [];

  const wornArmorItem = inventory.find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;

  const dexBonus = wornArmor?.type === 'heavy' ? 0 : calculateStatBonus(character, 'dex', powers);

  const shieldBonus = inventory
    .filter((item) => item.item_type === 'shield' && item.worn)
    .reduce((total, item) => {
      const shield = shields.find((s) => s.id === item.item_id);
      return total + (shield?.mod_def ?? 0);
    }, 0);

  const activeEffects = resolveEffectSentinels(getActiveEffects(character, powers), character, powers);
  const armorEffect = wornArmor ? [{ tag: 'mod_def', op: 'add', value: wornArmor.mod_def, stack_group: ARMOR_BONUS_STACK_GROUP }] : [];
  const modDefBonus = resolveTag([...activeEffects, ...armorEffect], 'mod_def');

  return 10 + dexBonus + shieldBonus + modDefBonus;
}
