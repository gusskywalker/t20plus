import { Armor, Character, Power, Shield } from '../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../get-active-effects/get-active-effects';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * Defesa — 10 + Destreza (only without armor or in light armor; heavy
 * armor blocks it entirely) + worn armor's mod_def + any worn shield's
 * mod_def + any mod_def from active powers (e.g. Esquiva). Only flat-number
 * mod_def effects resolve correctly today — a power whose mod_def value is
 * an attribute code instead of a number (e.g. Percepção Temporal's
 * "value": "knw", plus its limit/stack_group) isn't handled by resolveTag
 * yet, same accepted gap as elsewhere in this app.
 */
export function calculateDefense(character: Character, armors: Armor[], shields: Shield[], powers: Power[]): number {
  const inventory = character.inventory ?? [];

  const wornArmorItem = inventory.find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;

  const dexBonus = wornArmor?.type === 'heavy' ? 0 : calculateStatBonus(character, 'dex', powers);
  const armorBonus = wornArmor?.mod_def ?? 0;

  const shieldBonus = inventory
    .filter((item) => item.item_type === 'shield' && item.worn)
    .reduce((total, item) => {
      const shield = shields.find((s) => s.id === item.item_id);
      return total + (shield?.mod_def ?? 0);
    }, 0);

  const powerBonus = resolveTag(getActiveEffects(character, powers), 'mod_def');

  return 10 + dexBonus + armorBonus + shieldBonus + powerBonus;
}
