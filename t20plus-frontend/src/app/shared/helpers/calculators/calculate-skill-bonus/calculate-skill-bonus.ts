import { Armor, Character, Power, Shield, Skill } from '../../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';
import { resolveEffectSentinels } from '../../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveTag } from '../../tag-solver/tag-solver';

export interface SkillBonusPart {
  label: string;
  value: number;
}

/**
 * Bônus de Perícia = metade do nível (arredondado para baixo) +
 * atributo-chave + bônus de treinamento (+2 níveis 1-6, +4 níveis 7-14,
 * +6 nível 15+) — see claude-stuff/rules/character-skills.md — minus armor
 * penalty for skills flagged with it (Skill.armor_penalty): worn armor's
 * armor_penalty + worn shield's armor_penalty summed and subtracted —
 * plus any skill/all_skills/skill_group bonus from active powers (e.g.
 * Vontade de Ferro's +2 Vontade, Esquiva's +2 Reflexos), matched by this
 * skill's own id/attribute. Itemized version of the same formula, so a
 * future change to it only ever happens here — calculateSkillBonus below
 * just sums these parts. Zero-value parts are skipped (a misleading "+0"
 * line helps no one), except the skill's own base line, which always shows.
 */
export function calculateSkillBonusBreakdown(character: Character, skill: Skill, armors: Armor[], shields: Shield[], powers: Power[]): SkillBonusPart[] {
  const halfLevel = Math.floor(character.level / 2);
  const attributeMod = calculateStatBonus(character, skill.key_attribute, powers);

  const parts: SkillBonusPart[] = [{ label: `${skill.name} (Base)`, value: halfLevel + attributeMod }];

  const trained = character.trained_skill_ids?.includes(skill.id) ?? false;
  if (trained) {
    const trainingBonus = character.level >= 15 ? 6 : character.level >= 7 ? 4 : 2;
    parts.push({ label: 'Bônus Treinada', value: trainingBonus });
  }

  const armorPenalty = skill.armor_penalty ? calculateWornArmorPenalty(character, armors, shields) : 0;
  if (armorPenalty !== 0) {
    parts.push({ label: 'Penalidade de Armadura', value: -armorPenalty });
  }

  // One line per power contributing a skill/all_skills/skill_group bonus —
  // named individually instead of one anonymous summed total, so a skill
  // roll's breakdown can show exactly which powers are stacking. Grouped
  // by active_effect (one line per granted power), each line summing
  // every matching effect that power itself carries.
  for (const activeEffect of character.active_effects ?? []) {
    if (!activeEffect.is_active) {
      continue;
    }
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (!power) {
      continue;
    }
    const ownEffects = resolveEffectSentinels(power.effects ?? [], character, powers);
    const value =
      resolveTag(ownEffects, 'skill', (e) => e.skill_id === skill.id) +
      resolveTag(ownEffects, 'all_skills') +
      resolveTag(ownEffects, 'skill_group', (e) => e.attribute === skill.key_attribute && e.exclude_skill_id !== skill.id);
    if (value !== 0) {
      parts.push({ label: power.name, value });
    }
  }

  return parts;
}

export function calculateSkillBonus(character: Character, skill: Skill, armors: Armor[], shields: Shield[], powers: Power[]): number {
  return calculateSkillBonusBreakdown(character, skill, armors, shields, powers).reduce((sum, part) => sum + part.value, 0);
}

export function calculateWornArmorPenalty(character: Character, armors: Armor[], shields: Shield[]): number {
  const inventory = character.inventory ?? [];

  const wornArmorItem = inventory.find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;

  const wornShieldItem = inventory.find((item) => item.item_type === 'shield' && item.worn);
  const wornShield = wornShieldItem ? shields.find((shield) => shield.id === wornShieldItem.item_id) : undefined;

  return (wornArmor?.armor_penalty ?? 0) + (wornShield?.armor_penalty ?? 0);
}
