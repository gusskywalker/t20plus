import { Armor, Character, Power, Shield, Skill } from '../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../get-active-effects/get-active-effects';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * Bônus de Perícia = metade do nível (arredondado para baixo) +
 * atributo-chave + bônus de treinamento (+2 níveis 1-6, +4 níveis 7-14,
 * +6 nível 15+) — see claude-stuff/rules/character-skills.md — minus armor
 * penalty for skills flagged with it (Skill.armor_penalty): worn armor's
 * armor_penalty + worn shield's armor_penalty summed and subtracted —
 * plus any skill-tagged bonus from active powers (e.g. Vontade de Ferro's
 * +2 Vontade, Esquiva's +2 Reflexos), matched by this skill's own id.
 */
export function calculateSkillBonus(character: Character, skill: Skill, armors: Armor[], shields: Shield[], powers: Power[]): number {
  const halfLevel = Math.floor(character.level / 2);

  const attributeMod = calculateStatBonus(character, skill.key_attribute, powers);

  const trained = character.trained_skill_ids?.includes(skill.id) ?? false;
  const trainingBonus = !trained ? 0 : character.level >= 15 ? 6 : character.level >= 7 ? 4 : 2;

  const armorPenalty = skill.armor_penalty ? calculateWornArmorPenalty(character, armors, shields) : 0;

  const powerBonus = resolveTag(getActiveEffects(character, powers), 'skill', (effect) => effect.skill_id === skill.id);

  return halfLevel + attributeMod + trainingBonus - armorPenalty + powerBonus;
}

export function calculateWornArmorPenalty(character: Character, armors: Armor[], shields: Shield[]): number {
  const inventory = character.inventory ?? [];

  const wornArmorItem = inventory.find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;

  const wornShieldItem = inventory.find((item) => item.item_type === 'shield' && item.worn);
  const wornShield = wornShieldItem ? shields.find((shield) => shield.id === wornShieldItem.item_id) : undefined;

  return (wornArmor?.armor_penalty ?? 0) + (wornShield?.armor_penalty ?? 0);
}
