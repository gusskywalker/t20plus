import { Armor, Character, Shield, Skill } from '../../../api.service';

/**
 * Bônus de Perícia = metade do nível (arredondado para baixo) +
 * atributo-chave + bônus de treinamento (+2 níveis 1-6, +4 níveis 7-14,
 * +6 nível 15+) — see claude-stuff/rules/character-skills.md — minus armor
 * penalty for skills flagged with it (Skill.armor_penalty): worn armor's
 * armor_penalty + worn shield's armor_penalty summed and subtracted.
 */
export function calculateSkillBonus(character: Character, skill: Skill, armors: Armor[], shields: Shield[]): number {
  const halfLevel = Math.floor(character.level / 2);

  const attributeKeys: Record<string, number> = {
    str: character.base_str,
    dex: character.base_dex,
    con: character.base_con,
    int: character.base_int,
    knw: character.base_knw,
    car: character.base_car,
  };
  const attributeMod = attributeKeys[skill.key_attribute] ?? 0;

  const trained = character.trained_skill_ids?.includes(skill.id) ?? false;
  const trainingBonus = !trained ? 0 : character.level >= 15 ? 6 : character.level >= 7 ? 4 : 2;

  const armorPenalty = skill.armor_penalty ? calculateWornArmorPenalty(character, armors, shields) : 0;

  return halfLevel + attributeMod + trainingBonus - armorPenalty;
}

export function calculateWornArmorPenalty(character: Character, armors: Armor[], shields: Shield[]): number {
  const inventory = character.inventory ?? [];

  const wornArmorItem = inventory.find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;

  const wornShieldItem = inventory.find((item) => item.item_type === 'shield' && item.worn);
  const wornShield = wornShieldItem ? shields.find((shield) => shield.id === wornShieldItem.item_id) : undefined;

  return (wornArmor?.armor_penalty ?? 0) + (wornShield?.armor_penalty ?? 0);
}
