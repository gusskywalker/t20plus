import {
  CreateCharacterInventoryItem,
  CreateCharacterLevel,
  CreateCharacterPayload,
  Effect,
  Origin,
  Power,
  Race,
} from '../../api.service';
import { calculateStartingTibares } from '../../shared/helpers/calculate-starting-tibares/calculate-starting-tibares';
import { naturalWeaponSize } from '../../shared/helpers/natural-weapon-size/natural-weapon-size';
import { resolveLimitedSpellChoicePowers } from '../../shared/helpers/resolve-limited-spell-choice-powers/resolve-limited-spell-choice-powers';
import { resolveSkillBonusChoicePowers } from '../../shared/helpers/resolve-skill-bonus-choice-powers/resolve-skill-bonus-choice-powers';
import { resolveGrantedPowerIds } from '../../shared/helpers/resolve-granted-power-ids/resolve-granted-power-ids';
import { TATUAGEM_MISTICA_POWER_ID, CANCAO_DOS_MARES_POWER_ID, MAGIA_DAS_FADAS_POWER_ID } from '../../shared/helpers/power-pick-constants/power-pick-constants';
import { ADOLESCENTE_SKILL_POWER_GROUP_INDEX, CharacterDraft } from './character-draft';
import { resolveCasterSpellSlots } from './resolve-caster-spell-slots';

// Espião's choose_skill_not_combat pick (OriginGrantedPowerSeeder.php).
const ESPIAO_SKILL_ATTRIBUTE_POWER_ID = 350;

/**
 * Assembles the finished wizard's draft into the shape
 * ApiService.createCharacter posts — every "resulting fact" flattened
 * (trained_skill_ids/complication_ids/power_ids), plus the per-level
 * character_levels rows and starting character_inventory rows. See
 * characters/character_levels/character_inventory migrations for why
 * these are flat facts rather than wizard-choice provenance.
 */
export function buildCharacterPayload(
  draft: CharacterDraft,
  origins: Origin[],
  races: Race[],
  powers: Power[],
): CreateCharacterPayload {
  const race = races.find((r) => r.id === draft.raceId()) ?? null;

  // Memória Póstuma's Trocar Raça Base — the chosen race_granted power's
  // own race prerequisite tells us which race's base_size to inherit
  // (e.g. Chifres -> Minotauro, base_size +1). A power shared across
  // several races (Visão no Escuro) has no single unambiguous source race
  // for this purpose, so the first listed race_ids entry is used.
  const changeBaseRacePower = draft.memoriaPostumaChoice() === 'change_base_race' ? (powers.find((p) => p.id === draft.memoriaPostumaRaceAbilityPowerId()) ?? null) : null;
  const changeBaseRaceId = changeBaseRacePower?.prerequisites?.find((prerequisite) => prerequisite.type === 'race')?.race_ids?.[0] ?? null;
  const changeBaseRace = changeBaseRaceId !== null ? (races.find((r) => r.id === changeBaseRaceId) ?? null) : null;
  const effectiveBaseSize = changeBaseRace?.base_size ?? race?.base_size ?? 0;

  const weaponSize = naturalWeaponSize(effectiveBaseSize);
  const origin = origins.find((o) => o.id === draft.originId()) ?? null;
  const originGroups = origin?.grants ?? [];
  const originChoices = draft.originChoices();

  const adolescenteCase = draft.adolescenteCase();
  const overrideIds = new Set(draft.adolescenteOverride());

  const powerIds = draft.grantedPowerIds();
  const inventory: CreateCharacterInventoryItem[] = [];

  originGroups.forEach((group, groupIndex) => {
    (originChoices[groupIndex] ?? []).forEach((optionIndex) => {
      const isRemovedByAdolescente =
        groupIndex === ADOLESCENTE_SKILL_POWER_GROUP_INDEX &&
        adolescenteCase === 'origin' &&
        overrideIds.has(optionIndex);
      if (isRemovedByAdolescente) {
        return;
      }

      const option = group.options[optionIndex];
      if (!option) {
        return;
      }
      if (option.tag === 'accessory' && option.accessory_id !== undefined) {
        inventory.push({ item_type: 'accessory', item_id: option.accessory_id, worn: false });
      } else if (option.tag === 'armor' && option.armor_id !== undefined) {
        inventory.push({ item_type: 'armor', item_id: option.armor_id, worn: false });
      } else if (option.tag === 'weapon' && option.weapon_id !== undefined) {
        inventory.push({ item_type: 'weapon', item_id: option.weapon_id, worn: false, weapon_size: weaponSize });
      } else if (option.tag === 'choose_martial_weapon') {
        const originMartialWeaponId = draft.originMartialWeaponId();
        if (originMartialWeaponId !== null) {
          inventory.push({ item_type: 'weapon', item_id: originMartialWeaponId, worn: false, weapon_size: weaponSize });
        }
      } else if (option.tag === 'choose_simple_weapon') {
        const originSimpleWeaponId = draft.originSimpleWeaponId();
        if (originSimpleWeaponId !== null) {
          inventory.push({ item_type: 'weapon', item_id: originSimpleWeaponId, worn: false, weapon_size: weaponSize });
        }
      } else if (option.tag === 'choose_tool') {
        const originToolId = draft.originToolId();
        if (originToolId !== null) {
          inventory.push({ item_type: 'general_item', item_id: originToolId, worn: false });
        }
      } else if (option.tag === 'general_item' && option.general_item_id !== undefined) {
        inventory.push({ item_type: 'general_item', item_id: option.general_item_id, worn: false, quantity: option.quantity ?? 1 });
      }
    });
  });

  const complicationIds = [
    draft.generalComplicationId(),
    draft.adultoAgeComplicationId(),
    ...draft.maduroAgeComplicationIds(),
    ...draft.velhoAgeComplicationIds(),
    ...draft.anciaoAgeComplicationIds(),
  ].filter((id): id is number => id !== null);

  // Chosen spells grouped by the (class_id, classLevel) their slot belongs
  // to (step 10's own dropdown order matches resolveCasterSpellSlots' order
  // 1:1) — keyed by both, not just classLevel, since a character can have
  // more than one caster class at once (e.g. Arcanista + Bardo), each
  // counting its own class-relative levels from 1.
  const spellIdsByClassAndLevel = new Map<string, number[]>();
  resolveCasterSpellSlots(draft, powers).forEach((slot, i) => {
    const spellId = draft.chosenSpellIds()[i];
    if (spellId === null || spellId === undefined) {
      return;
    }
    const key = `${slot.classId}:${slot.classLevel}`;
    const existing = spellIdsByClassAndLevel.get(key) ?? [];
    existing.push(spellId);
    spellIdsByClassAndLevel.set(key, existing);
  });

  // One row per character level, in order — class_level is that class's
  // own running count (matches LevelPowerRow.classLevel in step 9),
  // power_id only meaningful from class_level 2 onward, spell_ids only
  // meaningful for the caster's own class rows.
  const classLevelCounts = new Map<number, number>();
  const levels: CreateCharacterLevel[] = draft
    .orderedClassIds()
    .map((classId, index) => {
      if (classId === null) {
        return null;
      }
      const classLevel = (classLevelCounts.get(classId) ?? 0) + 1;
      classLevelCounts.set(classId, classLevel);
      const spellIds = spellIdsByClassAndLevel.get(`${classId}:${classLevel}`);
      return {
        level: index + 1,
        class_id: classId,
        class_level: classLevel,
        power_id: draft.classPowerIds()[index] ?? null,
        ...(spellIds ? { spell_ids: spellIds } : {}),
      };
    })
    .filter((row): row is CreateCharacterLevel => row !== null);

  const startingSimpleWeaponId = draft.startingSimpleWeaponId();
  if (startingSimpleWeaponId !== null) {
    inventory.push({ item_type: 'weapon', item_id: startingSimpleWeaponId, worn: false, weapon_size: weaponSize });
  }
  const startingMartialWeaponId = draft.startingMartialWeaponId();
  if (startingMartialWeaponId !== null) {
    inventory.push({ item_type: 'weapon', item_id: startingMartialWeaponId, worn: false, weapon_size: weaponSize });
  }
  const startingArmorId = draft.startingArmorId();
  if (startingArmorId !== null) {
    inventory.push({ item_type: 'armor', item_id: startingArmorId, worn: false });
  }
  const startingShieldId = draft.startingShieldId();
  if (startingShieldId !== null) {
    inventory.push({ item_type: 'shield', item_id: startingShieldId, worn: false });
  }

  const other = new Set(draft.otherAttributes());

  // Any picked/granted power (flat power_ids above, or a per-level class
  // pick) can itself grant others (tag: 'power', op: 'grant' — source:
  // 'power_granted' on the granted side, e.g. Espreitar's two children).
  // Only the NEWLY reached ids get appended to the flat list — a root id
  // already covered by its own levels[].power_id row must not also appear
  // here, or the backend's insert would violate character_active_effects'
  // (character_id, power_id) uniqueness.
  const levelPowerIds = levels.map((l) => l.power_id).filter((id): id is number => id !== null);
  const allRootPowerIds = [...powerIds, ...levelPowerIds];
  const resolvedPowerIds = resolveGrantedPowerIds(allRootPowerIds, powers);
  const grantedChildPowerIds = [...resolvedPowerIds].filter((id) => !allRootPowerIds.includes(id));

  // A power carrying an on_other_sources_satisfied effect that reached the
  // character from two different sources (a root pick/grant plus a vessel's
  // child grant, or two vessels' child grants) starts out 'satisfied'.
  const satisfiedPowerIds = [...resolvedPowerIds].filter((id) => {
    const power = powers.find((p) => p.id === id);
    if (!(power?.effects ?? []).some((effect) => effect.trigger === 'on_other_sources_satisfied')) {
      return false;
    }
    const rootSources = allRootPowerIds.includes(id) ? 1 : 0;
    const grantingParents = [...resolvedPowerIds].filter(
      (parentId) =>
        parentId !== id &&
        (powers.find((p) => p.id === parentId)?.effects ?? []).some((effect) => effect.tag === 'power' && effect.op === 'grant' && effect.power_id === id),
    ).length;
    return rootSources + grantingParents >= 2;
  });

  const customEffects: { power_id: number; custom_effect: Effect[] }[] = [];
  const espiaoSkillId = draft.espiaoSkillAttributeSkillId();
  if (espiaoSkillId !== null && powerIds.has(ESPIAO_SKILL_ATTRIBUTE_POWER_ID)) {
    customEffects.push({
      power_id: ESPIAO_SKILL_ATTRIBUTE_POWER_ID,
      custom_effect: [{ tag: 'skill_attribute', op: 'override', skill_id: espiaoSkillId, value: 'car' }],
    });
  }
  const tatuagemMisticaSpellId = draft.tatuagemMisticaSpellId();
  if (tatuagemMisticaSpellId !== null && powerIds.has(TATUAGEM_MISTICA_POWER_ID)) {
    customEffects.push({
      power_id: TATUAGEM_MISTICA_POWER_ID,
      custom_effect: [{ tag: 'grant_or_reduce_spell_pm_cost_by_1', op: 'grant', spell_id: tatuagemMisticaSpellId }],
    });
  }
  const cancaoDosMaresSpellIds = draft.cancaoDosMaresSpellIds().filter((id): id is number => id !== null);
  if (cancaoDosMaresSpellIds.length > 0 && powerIds.has(CANCAO_DOS_MARES_POWER_ID)) {
    customEffects.push({
      power_id: CANCAO_DOS_MARES_POWER_ID,
      custom_effect: cancaoDosMaresSpellIds.map((spellId) => ({ tag: 'grant_or_reduce_spell_pm_cost_by_1', op: 'grant', spell_id: spellId })),
    });
  }
  const magiaDasFadasSpellIds = draft.magiaDasFadasSpellIds().filter((id): id is number => id !== null);
  if (magiaDasFadasSpellIds.length > 0 && powerIds.has(MAGIA_DAS_FADAS_POWER_ID)) {
    customEffects.push({
      power_id: MAGIA_DAS_FADAS_POWER_ID,
      custom_effect: magiaDasFadasSpellIds.map((spellId) => ({ tag: 'grant_or_reduce_spell_pm_cost_by_1', op: 'grant', spell_id: spellId })),
    });
  }
  const limitedSpellChoiceIds = draft.limitedSpellChoiceIds();
  resolveLimitedSpellChoicePowers(powerIds, powers).forEach(({ power }) => {
    const spellIds = (limitedSpellChoiceIds[power.id] ?? []).filter((id): id is number => id !== null);
    if (spellIds.length > 0) {
      customEffects.push({
        power_id: power.id,
        custom_effect: spellIds.map((spellId) => ({ tag: 'grant_or_reduce_spell_pm_cost_by_1', op: 'grant', spell_id: spellId })),
      });
    }
  });

  const skillBonusChoiceIds = draft.skillBonusChoiceIds();
  resolveSkillBonusChoicePowers(powerIds, powers).forEach(({ power, bonus, skillIds }) => {
    const pickedSkillIds = (skillBonusChoiceIds[power.id] ?? []).filter((id): id is number => id !== null && skillIds.includes(id));
    if (pickedSkillIds.length > 0) {
      customEffects.push({
        power_id: power.id,
        custom_effect: pickedSkillIds.map((skillId) => ({ tag: 'skill', op: 'add', skill_id: skillId, value: bonus })),
      });
    }
  });

  return {
    name: draft.name(),
    // finalBaseStr/etc — raw point-buy plus Aumentar Atributo's own
    // permanent mod_base_str (see character-draft.ts) — not draft.baseStr()
    // directly, which stays step 2's own untouched point-buy value.
    base_str: draft.finalBaseStr() + (other.has('str') ? 1 : 0) + draft.duendeAnimalBonus('str') + (race?.mod_str ?? 0),
    base_dex: draft.finalBaseDex() + (other.has('dex') ? 1 : 0) + draft.duendeAnimalBonus('dex') + (race?.mod_dex ?? 0),
    base_con: draft.finalBaseCon() + (other.has('con') ? 1 : 0) + draft.duendeAnimalBonus('con') + (race?.mod_con ?? 0),
    base_int: draft.finalBaseInt() + (other.has('int') ? 1 : 0) + draft.duendeAnimalBonus('int') + (race?.mod_int ?? 0),
    base_knw: draft.finalBaseKnw() + (other.has('knw') ? 1 : 0) + draft.duendeAnimalBonus('knw') + (race?.mod_knw ?? 0),
    base_car: draft.finalBaseCar() + (other.has('car') ? 1 : 0) + draft.duendeAnimalBonus('car') + (race?.mod_car ?? 0),
    current_size: effectiveBaseSize,
    race_id: draft.raceId(),
    origin_id: draft.originId(),
    god_id: draft.godId(),
    portrait_id: draft.portraitId(),
    trained_skill_ids: [...draft.baseTrainedSkillIds()],
    age: draft.age(),
    age_bracket: draft.ageBracket(),
    complication_ids: complicationIds,
    power_ids: [...powerIds, ...grantedChildPowerIds],
    custom_effects: customEffects,
    satisfied_power_ids: satisfiedPowerIds,
    tibares: calculateStartingTibares(draft.totalLevel(), origin, originChoices),
    levels,
    inventory,
  };
}
