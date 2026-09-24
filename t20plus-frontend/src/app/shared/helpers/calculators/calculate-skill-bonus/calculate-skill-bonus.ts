import { Accessory, Armor, Character, CharacterInventoryRow, Effect, GeneralItem, ItemEnchantment, ItemImprovement, Power, Shield, Skill, Spell } from '../../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';
import { getItemGrantedPowers } from '../../get-item-granted-effects/get-item-granted-effects';
import { resolveEffectSentinels } from '../../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveReplacedPowerIds } from '../../resolve-replaced-power-ids/resolve-replaced-power-ids';
import { resolveSkillKeyAttribute } from '../../resolve-skill-key-attribute/resolve-skill-key-attribute';
import { resolveTag } from '../../tag-solver/tag-solver';
import { resolveTrainedSkillIds } from '../../resolve-trained-skill-ids/resolve-trained-skill-ids';
import { resolveCurrentSize } from '../../resolve-current-size/resolve-current-size';
import { CHARACTER_SIZE_MODIFIERS, FURTIVIDADE_SKILL_ID } from '../../../constants/character-size-modifiers';

export interface SkillBonusPart {
  label: string;
  value: number;
}

// Which inventory rows currently count as a granted-power source: worn
// armor/shield/accessory (unchanged), plus every non-consumable general_item
// regardless of worn — general_items have no worn concept at all, and
// consumable ones (Cosmético, Essência de Mana) only grant their power
// through the separate one-shot "Usar" flow, not passively just by being
// owned. Non-consumable ones (Luneta, Gazua, Estojo de Disfarces...) are
// always with the character once owned, same as tibares — nothing tracks
// "left at the inn."
function relevantItemGrantSources(
  character: Character,
  armors: Armor[],
  shields: Shield[],
  accessories: Accessory[],
  generalItems: GeneralItem[],
): { item: CharacterInventoryRow; ownEffects: Effect[] | null }[] {
  const sources: { item: CharacterInventoryRow; ownEffects: Effect[] | null }[] = [];
  for (const item of character.inventory ?? []) {
    if (item.item_type === 'general_item') {
      const generalItem = generalItems.find((g) => g.id === item.item_id);
      if (generalItem && !generalItem.consumable) {
        sources.push({ item, ownEffects: generalItem.effects });
      }
      continue;
    }
    if (!item.worn) {
      continue;
    }
    const catalogItem =
      item.item_type === 'armor'
        ? armors.find((a) => a.id === item.item_id)
        : item.item_type === 'shield'
          ? shields.find((s) => s.id === item.item_id)
          : item.item_type === 'accessory'
            ? accessories.find((a) => a.id === item.item_id)
            : undefined;
    if (catalogItem) {
      sources.push({ item, ownEffects: catalogItem.effects });
    }
  }
  return sources;
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
 * Sagrado's +1 Vontade/Fortitude/Reflexos) or an owned non-consumable
 * general_item — see relevantItemGrantSources above and get-item-granted-
 * effects.ts. Only usability 'passive' item-granted powers count here;
 * a roll_active one (e.g. Luneta's +5 Percepção) is self-reported per
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
  accessories: Accessory[],
  generalItems: GeneralItem[],
  itemImprovements: ItemImprovement[],
  itemEnchantments: ItemEnchantment[],
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

  const attributeMod = calculateStatBonus(character, keyAttribute, powers);

  const parts: SkillBonusPart[] = [{ label: `${skill.name} (Base)`, value: halfLevel + attributeMod }];

  const trained = forceTrained || resolveTrainedSkillIds(character.trained_skill_ids ?? [], getActiveEffects(character, powers)).has(skill.id);
  if (trained) {
    const trainingBonus = character.level >= 15 ? 6 : character.level >= 7 ? 4 : 2;
    parts.push({ label: 'Bônus Treinada', value: trainingBonus });
  }

  const armorPenalty = skill.armor_penalty
    ? calculateArmorPenalty(character, armors, shields, accessories, generalItems, itemImprovements, itemEnchantments, powers)
    : 0;
  if (armorPenalty !== 0) {
    parts.push({ label: 'Penalidade de Armadura', value: -armorPenalty });
  }

  // Smaller creatures are stealthier, bigger ones aren't (character-size-
  // rules.md) — read off the live size, so a size-changing power/spell counts.
  if (skill.id === FURTIVIDADE_SKILL_ID) {
    const sizeModifier = CHARACTER_SIZE_MODIFIERS[resolveCurrentSize(character, powers)]?.furtividade ?? 0;
    if (sizeModifier !== 0) {
      parts.push({ label: 'Tamanho', value: sizeModifier });
    }
  }

  const matchesSkill = (e: Effect) =>
    (e.tag === 'skill' && e.skill_id === skill.id) ||
    e.tag === 'all_skills' ||
    (e.tag === 'skill_group' && e.attribute === keyAttribute && !(e.exclude_skill_ids ?? []).includes(skill.id));

  // Every source is walked and sentinel-resolved exactly once, into one
  // entry each — the stack_group contest and the breakdown lines both read
  // these same entries. stack_group dedup has to compete across EVERY contributing source at
  // once, not per source in isolation (e.g. Duplo's Multiplicidade +2
  // Diplomacia vs Arte do Disfarce +10 Diplomacia, "não se acumula").
  const sources: { label: string; effects: Effect[] }[] = [];

  const replacedPowerIds = resolveReplacedPowerIds(new Set((character.active_effects ?? []).map((row) => row.power_id)), powers);
  for (const activeEffect of character.active_effects ?? []) {
    if (!activeEffect.is_active) {
      continue;
    }
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (!power || replacedPowerIds.has(power.id)) {
      continue;
    }
    sources.push({ label: power.name, effects: resolveEffectSentinels(power.effects ?? [], character, powers).filter(matchesSkill) });
  }
  // Passive powers granted by a worn armor/shield/accessory or an owned
  // non-consumable general_item (its own effects, or its improvement_ids/
  // enchantment_ids) — resolved the same way attack-modal resolves a
  // selected weapon's granted powers.
  for (const { item, ownEffects } of relevantItemGrantSources(character, armors, shields, accessories, generalItems)) {
    const grantedPowers = getItemGrantedPowers(item, itemImprovements, itemEnchantments, powers, null, ownEffects).filter(
      (power) => power.usability === 'passive',
    );
    for (const power of grantedPowers) {
      sources.push({ label: power.name, effects: resolveEffectSentinels(power.effects ?? [], character, powers).filter(matchesSkill) });
    }
  }
  // Spell buffs (character_active_spell_effects) — labeled by the casting
  // spell's own name (Sifão de Mana-style ally buffs from another caster
  // included). A 'roll_active' entry only applies to a specific roll and
  // belongs in skill-roll-modal's own checklist instead, never blanket-
  // applied like this — same exclusion getActiveEffects.ts already uses.
  for (const activeSpellEffect of character.active_spell_effects ?? []) {
    const passiveEffects = activeSpellEffect.effects.filter((effect) => effect.usability !== 'roll_active');
    const spell = spells.find((s) => s.id === activeSpellEffect.spell_id);
    sources.push({ label: spell?.name ?? 'Magia', effects: resolveEffectSentinels(passiveEffects, character, powers).filter(matchesSkill) });
  }

  const bestByGroup = new Map<string, Effect>();
  for (const effect of sources.flatMap((source) => source.effects)) {
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
  for (const source of sources) {
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
  accessories: Accessory[],
  generalItems: GeneralItem[],
  itemImprovements: ItemImprovement[],
  itemEnchantments: ItemEnchantment[],
  powers: Power[],
  spells: Spell[],
): number {
  return calculateSkillBonusBreakdown(
    character,
    skill,
    armors,
    shields,
    accessories,
    generalItems,
    itemImprovements,
    itemEnchantments,
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
 * every positive contributor. Sources: the character's active powers and
 * spell buffs (getActiveEffects) plus passive powers granted by worn/owned
 * items.
 */
export function calculateArmorPenalty(
  character: Character,
  armors: Armor[],
  shields: Shield[],
  accessories: Accessory[],
  generalItems: GeneralItem[],
  itemImprovements: ItemImprovement[],
  itemEnchantments: ItemEnchantment[],
  powers: Power[],
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
  collect(getActiveEffects(character, powers));
  for (const { item, ownEffects } of relevantItemGrantSources(character, armors, shields, accessories, generalItems)) {
    getItemGrantedPowers(item, itemImprovements, itemEnchantments, powers, null, ownEffects)
      .filter((power) => power.usability === 'passive')
      .forEach((power) => collect(power.effects ?? []));
  }
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
