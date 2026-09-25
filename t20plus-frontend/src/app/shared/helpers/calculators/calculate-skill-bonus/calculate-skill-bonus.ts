import { Armor, Character, Effect, Power, Shield, Skill, Spell } from '../../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';
import { resolveEffectSentinels } from '../../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveSkillKeyAttribute } from '../../resolve-skill-key-attribute/resolve-skill-key-attribute';
import { resolveTag } from '../../tag-solver/tag-solver';
import { resolveTrainedSkillIds } from '../../resolve-trained-skill-ids/resolve-trained-skill-ids';
import { resolveCurrentSize } from '../../resolve-current-size/resolve-current-size';
import { CHARACTER_SIZE_MODIFIERS, FURTIVIDADE_SKILL_ID } from '../../../constants/character-size-modifiers';

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
 * Vontade de Ferro's +2 Vontade, Esquiva's +2 Reflexos) OR from a passive
 * power granted by a currently worn armor/shield/accessory (e.g. Símbolo
 * Sagrado's +1 Vontade/Fortitude/Reflexos) — all read off getActiveEffects.
 * A roll_active power (e.g. Luneta's +5 Percepção) is self-reported per
 * roll instead — see skill-roll-modal.ts. Matched by this skill's own
 * id/attribute. Itemized version
 * of the same formula, so a future change to it only ever happens here —
 * calculateSkillBonus below just sums these parts. Zero-value parts are
 * skipped (a misleading "+0" line helps no one), except the skill's own
 * base line, which always shows.
 */
export function calculateSkillBonusBreakdown(
  character: Character,
  skill: Skill,
  armors: Armor[],
  shields: Shield[],
  powers: Power[],
  spells: Spell[],
  // True when a roll_active power's {tag:'skill', op:'trains'} was checked
  // for THIS roll (e.g. Herança de Skerry) — getActiveEffects never sees a
  // roll_active row (it's never toggled), so a per-roll caller resolves
  // that checkbox itself and forces trained status in here instead.
  forceTrained = false,
): SkillBonusPart[] {
  const halfLevel = Math.floor(character.level / 2);

  // skill_attribute (e.g. the two fixed Sabedoria-instead-of-X cases, or
  // Espião's open "escolha uma perícia... use Carisma" via custom_effect)
  // overrides which attribute this skill reads for both its own base line
  // AND which skill_group bonuses now apply to it — the skill is fully
  // treated as governed by the new attribute, not just its base number.
  // Routed through getActiveEffects so both a power's own fixed effect and
  // a character_active_effects row's custom_effect are caught the same
  // way. Last matching effect wins if more than one somehow applies, same
  // as resolveTag's own `set`/`override` semantics.
  const keyAttribute = resolveSkillKeyAttribute(character, skill, powers);

  const attributeMod = calculateStatBonus(character, keyAttribute);

  const parts: SkillBonusPart[] = [{ label: `${skill.name} (Base)`, value: halfLevel + attributeMod }];

  const activeEffects = getActiveEffects(character);

  const trained = forceTrained || resolveTrainedSkillIds(character.trained_skill_ids ?? [], activeEffects).has(skill.id);
  if (trained) {
    const trainingBonus = character.level >= 15 ? 6 : character.level >= 7 ? 4 : 2;
    parts.push({ label: 'Bônus Treinada', value: trainingBonus });
  }

  const armorPenalty = skill.armor_penalty
    ? calculateArmorPenalty(character, armors, shields)
    : 0;
  if (armorPenalty !== 0) {
    parts.push({ label: 'Penalidade de Armadura', value: -armorPenalty });
  }

  // Smaller creatures are stealthier, bigger ones aren't (character-size-
  // rules.md) — read off the live size, so a size-changing power/spell counts.
  if (skill.id === FURTIVIDADE_SKILL_ID) {
    const sizeModifier = CHARACTER_SIZE_MODIFIERS[resolveCurrentSize(character)]?.furtividade ?? 0;
    if (sizeModifier !== 0) {
      parts.push({ label: 'Tamanho', value: sizeModifier });
    }
  }

  const matchesSkill = (e: Effect) =>
    (e.tag === 'skill' && e.skill_id === skill.id) ||
    e.tag === 'all_skills' ||
    (e.tag === 'skill_group' && e.attribute === keyAttribute && !(e.exclude_skill_ids ?? []).includes(skill.id));

  // Skill effects of every active source, grouped by the source each was
  // tagged with — the stack_group contest and the breakdown lines both read
  // these groups. stack_group dedup has to compete across EVERY contributing
  // source at once, not per source in isolation (e.g. Duplo's Multiplicidade
  // +2 Diplomacia vs Arte do Disfarce +10 Diplomacia, "não se acumula").
  const sources = new Map<string, { label: string; effects: Effect[] }>();
  for (const activeEffect of activeEffects) {
    const effect = resolveEffectSentinels([activeEffect], character, powers)[0];
    if (!matchesSkill(effect)) {
      continue;
    }
    const key = activeEffect.source_spell_id !== undefined ? `spell:${activeEffect.source_spell_id}` : `power:${activeEffect.source_power_id}`;
    const label =
      activeEffect.source_spell_id !== undefined
        ? (spells.find((s) => s.id === activeEffect.source_spell_id)?.name ?? 'Magia')
        : (powers.find((p) => p.id === activeEffect.source_power_id)?.name ?? '');
    const source = sources.get(key) ?? { label, effects: [] };
    source.effects.push(effect);
    sources.set(key, source);
  }

  const bestByGroup = new Map<string, Effect>();
  for (const effect of [...sources.values()].flatMap((source) => source.effects)) {
    if (!effect.stack_group) {
      continue;
    }
    const current = bestByGroup.get(effect.stack_group);
    if (!current || Number(effect.value ?? 0) > Number(current.value ?? 0)) {
      bestByGroup.set(effect.stack_group, effect);
    }
  }

  // One line per source contributing a skill/all_skills/skill_group bonus —
  // named individually instead of one anonymous summed total, so a skill
  // roll's breakdown can show exactly which sources are stacking. A source
  // that lost its stack_group contest contributes nothing (no zero-value
  // line either).
  for (const source of sources.values()) {
    const ownEffects = source.effects.filter((effect) => !effect.stack_group || bestByGroup.get(effect.stack_group) === effect);
    const value =
      resolveTag(ownEffects, 'skill', (e) => e.skill_id === skill.id) +
      resolveTag(ownEffects, 'all_skills') +
      resolveTag(ownEffects, 'skill_group', (e) => e.attribute === keyAttribute && !(e.exclude_skill_ids ?? []).includes(skill.id));
    if (value !== 0) {
      parts.push({ label: source.label, value });
    }
  }

  return parts;
}

export function calculateSkillBonus(
  character: Character,
  skill: Skill,
  armors: Armor[],
  shields: Shield[],
  powers: Power[],
  spells: Spell[],
): number {
  return calculateSkillBonusBreakdown(
    character,
    skill,
    armors,
    shields,
    powers,
    spells,
  ).reduce((sum, part) => sum + part.value, 0);
}

/**
 * The armor penalty a skill flagged with armor_penalty actually takes: the
 * worn armor's + shield's own penalty, reduced by every negative
 * mod_armor_penalty (never below 0 — a reduction can't turn into a bonus),
 * plus every positive mod_armor_penalty, which applies regardless of what's
 * worn (e.g. Yidishan's Peças Metálicas), plus chassi_armor_penalty (e.g.
 * Golem's Chassi de Ferro) unless waive_chassi_armor_penalty is also active
 * (e.g. Chassi Gracioso) — kept separate from the generic mod_armor_penalty
 * additions since a waiver needs to cancel only its own specific source, not
 * every positive contributor. Sources: getActiveEffects (active powers,
 * spell buffs and worn-item passives).
 */
export function calculateArmorPenalty(
  character: Character,
  armors: Armor[],
  shields: Shield[],
): number {
  const mods: number[] = [];
  // waive_armor_penalty_for_armors (e.g. Conforto do Aço) drops the worn
  // ARMOR's own penalty but, unlike a blanket reduction, leaves the shield's.
  let waivesArmorPenalty = false;
  let chassiArmorPenalty = 0;
  let waivesChassiArmorPenalty = false;
  const collect = (effects: Effect[]) => {
    effects.filter((effect) => effect.tag === 'mod_armor_penalty' && effect.op === 'add').forEach((effect) => mods.push(Number(effect.value ?? 0)));
    if (effects.some((effect) => effect.tag === 'waive_armor_penalty_for_armors' && effect.op === 'grant')) {
      waivesArmorPenalty = true;
    }
    effects
      .filter((effect) => effect.tag === 'chassi_armor_penalty' && effect.op === 'grant')
      .forEach((effect) => (chassiArmorPenalty += Number(effect.value ?? 0)));
    if (effects.some((effect) => effect.tag === 'waive_chassi_armor_penalty' && effect.op === 'grant')) {
      waivesChassiArmorPenalty = true;
    }
  };
  collect(getActiveEffects(character));
  const reductions = mods.filter((value) => value < 0).reduce((sum, value) => sum + value, 0);
  const additions = mods.filter((value) => value > 0).reduce((sum, value) => sum + value, 0);
  return (
    Math.max(0, calculateWornArmorPenalty(character, armors, shields, waivesArmorPenalty) + reductions) +
    additions +
    (waivesChassiArmorPenalty ? 0 : chassiArmorPenalty)
  );
}

export function calculateWornArmorPenalty(character: Character, armors: Armor[], shields: Shield[], waiveArmor = false): number {
  const inventory = character.inventory ?? [];

  const wornArmorItem = inventory.find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;

  const wornShieldItem = inventory.find((item) => item.item_type === 'shield' && item.worn);
  const wornShield = wornShieldItem ? shields.find((shield) => shield.id === wornShieldItem.item_id) : undefined;

  return (waiveArmor ? 0 : (wornArmor?.armor_penalty ?? 0)) + (wornShield?.armor_penalty ?? 0);
}
