import { Armor, Character, Shield } from '../../../api.service';

/**
 * Defesa — 10 + Destreza (only without armor or in light armor; heavy
 * armor blocks it entirely) + worn armor's mod_def + any worn shield's
 * mod_def. Poderes/magias/itens mágicos bonuses (claude-stuff/rules/defence.md's
 * "Outros Bônus") aren't summed yet — no generic effects resolver exists
 * to pull mod_def contributions from those sources, same accepted gap as
 * elsewhere in this app.
 */
export function calculateDefense(character: Character, armors: Armor[], shields: Shield[]): number {
  const inventory = character.inventory ?? [];

  const wornArmorItem = inventory.find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;

  const dexBonus = wornArmor?.type === 'heavy' ? 0 : character.base_dex;
  const armorBonus = wornArmor?.mod_def ?? 0;

  const shieldBonus = inventory
    .filter((item) => item.item_type === 'shield' && item.worn)
    .reduce((total, item) => {
      const shield = shields.find((s) => s.id === item.item_id);
      return total + (shield?.mod_def ?? 0);
    }, 0);

  return 10 + dexBonus + armorBonus + shieldBonus;
}
