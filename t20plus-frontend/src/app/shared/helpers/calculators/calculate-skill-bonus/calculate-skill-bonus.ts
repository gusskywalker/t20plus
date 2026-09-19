import { Accessory, Armor, Character, CharacterInventoryRow, Effect, GeneralItem, ItemEnchantment, ItemImprovement, Power, Shield, Skill, Spell } from '../../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';
import { getItemGrantedPowers } from '../../get-item-granted-effects/get-item-granted-effects';
import { resolveEffectSentinels } from '../../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveSkillKeyAttribute } from '../../resolve-skill-key-attribute/resolve-skill-key-attribute';
import { resolveTag } from '../../tag-solver/tag-solver';
import { resolveTrainedSkillIds } from '../../resolve-trained-skill-ids/resolve-trained-skill-ids';

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

  const trained = resolveTrainedSkillIds(character.trained_skill_ids ?? [], getActiveEffects(character, powers)).has(skill.id);
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

  // stack_group dedup for skill/all_skills/skill_group has to happen across
  // EVERY contributing power at once, not per power in isolation — resolveTag
  // only dedupes within a single call, so two different powers sharing a
  // stack_group (e.g. Duplo's Multiplicidade +2 Diplomacia vs Arte do
  // Disfarce +10 Diplomacia, "não se acumula") would never actually compete
  // if each power got its own separate resolveTag call. So: gather every
  // matching effect from every source FIRST, run the same best-of-group
  // logic resolveTag uses internally, then only the survivors are allowed
  // through the per-power loops below (object-reference filtered — safe
  // because resolveEffectSentinels returns the SAME effect object when
  // nothing needed resolving, which is always true for these plain-number
  // tags).
  const matchesSkill = (e: Effect) =>
    (e.tag === 'skill' && e.skill_id === skill.id) ||
    e.tag === 'all_skills' ||
    (e.tag === 'skill_group' && e.attribute === keyAttribute && !(e.exclude_skill_ids ?? []).includes(skill.id));

  const allMatchingEffects: Effect[] = [];
  for (const activeEffect of character.active_effects ?? []) {
    if (!activeEffect.is_active) {
      continue;
    }
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (!power) {
      continue;
    }
    allMatchingEffects.push(...resolveEffectSentinels(power.effects ?? [], character, powers).filter(matchesSkill));
  }
  for (const { item, ownEffects } of relevantItemGrantSources(character, armors, shields, accessories, generalItems)) {
    const grantedPowers = getItemGrantedPowers(item, itemImprovements, itemEnchantments, powers, null, ownEffects).filter(
      (power) => power.usability === 'passive',
    );
    for (const power of grantedPowers) {
      allMatchingEffects.push(...resolveEffectSentinels(power.effects ?? [], character, powers).filter(matchesSkill));
    }
  }
  // Spell buffs (character_active_spell_effects) — same "already-resolved,
  // no power to join against" shape getActiveEffects.ts folds in generically,
  // but this calculator gathers its own sources by hand (for the itemized,
  // stack_group-competing breakdown lines below), so they have to be added
  // here too instead of riding along for free. A 'roll_active' entry only
  // applies to a specific roll and belongs in skill-roll-modal's own
  // checklist instead, never blanket-applied like this — same exclusion
  // getActiveEffects.ts already uses.
  for (const activeSpellEffect of character.active_spell_effects ?? []) {
    const passiveEffects = activeSpellEffect.effects.filter((effect) => effect.usability !== 'roll_active');
    allMatchingEffects.push(...resolveEffectSentinels(passiveEffects, character, powers).filter(matchesSkill));
  }

  const bestByGroup = new Map<string, Effect>();
  for (const effect of allMatchingEffects) {
    if (!effect.stack_group) {
      continue;
    }
    const current = bestByGroup.get(effect.stack_group);
    if (!current || Number(effect.value ?? 0) > Number(current.value ?? 0)) {
      bestByGroup.set(effect.stack_group, effect);
    }
  }
  const survivors = new Set(allMatchingEffects.filter((effect) => !effect.stack_group || bestByGroup.get(effect.stack_group) === effect));

  // One line per power contributing a skill/all_skills/skill_group bonus —
  // named individually instead of one anonymous summed total, so a skill
  // roll's breakdown can show exactly which powers are stacking. Grouped
  // by active_effect (one line per granted power), each line summing
  // every matching effect that power itself carries, filtered to survivors
  // so a power that lost its stack_group contest contributes nothing (no
  // zero-value line either).
  for (const activeEffect of character.active_effects ?? []) {
    if (!activeEffect.is_active) {
      continue;
    }
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (!power) {
      continue;
    }
    const ownEffects = resolveEffectSentinels(power.effects ?? [], character, powers).filter((e) => survivors.has(e));
    const value =
      resolveTag(ownEffects, 'skill', (e) => e.skill_id === skill.id) +
      resolveTag(ownEffects, 'all_skills') +
      resolveTag(ownEffects, 'skill_group', (e) => e.attribute === keyAttribute && !(e.exclude_skill_ids ?? []).includes(skill.id));
    if (value !== 0) {
      parts.push({ label: power.name, value });
    }
  }

  // Same idea, for every passive power a currently worn armor/shield/
  // accessory or owned non-consumable general_item grants (its own
  // effects, or its improvement_ids/enchantment_ids) — resolved the same
  // way attack-modal resolves a selected weapon's granted powers.
  for (const { item, ownEffects: catalogEffects } of relevantItemGrantSources(character, armors, shields, accessories, generalItems)) {
    const grantedPowers = getItemGrantedPowers(item, itemImprovements, itemEnchantments, powers, null, catalogEffects).filter(
      (power) => power.usability === 'passive',
    );
    for (const power of grantedPowers) {
      const ownEffects = resolveEffectSentinels(power.effects ?? [], character, powers).filter((e) => survivors.has(e));
      const value =
        resolveTag(ownEffects, 'skill', (e) => e.skill_id === skill.id) +
        resolveTag(ownEffects, 'all_skills') +
        resolveTag(ownEffects, 'skill_group', (e) => e.attribute === keyAttribute && !(e.exclude_skill_ids ?? []).includes(skill.id));
      if (value !== 0) {
        parts.push({ label: power.name, value });
      }
    }
  }

  // Same idea, one line per active spell buff — labeled by the casting
  // spell's own name (Sifão de Mana-style ally buffs from another caster
  // included, same as any other active_spell_effects row).
  for (const activeSpellEffect of character.active_spell_effects ?? []) {
    const passiveEffects = activeSpellEffect.effects.filter((effect) => effect.usability !== 'roll_active');
    const ownEffects = resolveEffectSentinels(passiveEffects, character, powers).filter((e) => survivors.has(e));
    const value =
      resolveTag(ownEffects, 'skill', (e) => e.skill_id === skill.id) +
      resolveTag(ownEffects, 'all_skills') +
      resolveTag(ownEffects, 'skill_group', (e) => e.attribute === keyAttribute && !(e.exclude_skill_ids ?? []).includes(skill.id));
    if (value !== 0) {
      const spell = spells.find((s) => s.id === activeSpellEffect.spell_id);
      parts.push({ label: spell?.name ?? 'Magia', value });
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
 * worn (e.g. Yidishan's Peças Metálicas). Sources: the character's active
 * powers and spell buffs (getActiveEffects) plus passive powers granted by
 * worn/owned items.
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
  const collect = (effects: Effect[]) => {
    effects.filter((effect) => effect.tag === 'mod_armor_penalty' && effect.op === 'add').forEach((effect) => mods.push(Number(effect.value ?? 0)));
    if (effects.some((effect) => effect.tag === 'waive_armor_penalty_for_armors' && effect.op === 'grant')) {
      waivesArmorPenalty = true;
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
  return Math.max(0, calculateWornArmorPenalty(character, armors, shields, waivesArmorPenalty) + reductions) + additions;
}

export function calculateWornArmorPenalty(character: Character, armors: Armor[], shields: Shield[], waiveArmor = false): number {
  const inventory = character.inventory ?? [];

  const wornArmorItem = inventory.find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;

  const wornShieldItem = inventory.find((item) => item.item_type === 'shield' && item.worn);
  const wornShield = wornShieldItem ? shields.find((shield) => shield.id === wornShieldItem.item_id) : undefined;

  return (waiveArmor ? 0 : (wornArmor?.armor_penalty ?? 0)) + (wornShield?.armor_penalty ?? 0);
}
