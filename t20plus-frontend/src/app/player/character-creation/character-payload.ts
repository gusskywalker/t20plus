import {
  CharacterClass,
  Complication,
  CreateCharacterInventoryItem,
  CreateCharacterLevel,
  CreateCharacterPayload,
  Origin,
} from '../../api.service';
import { parseShopItemKey } from '../../shared/helpers/buy-item/buy-item';
import { AGE_BRACKETS } from '../../shared/constants/age-brackets';
import { CharacterDraft } from './character-draft';

// Origem em Construção's "unmark 1" only ever touches the origin's own
// Perícias e Poderes group — see adolescenteCase in
// character-creation-step-7.ts, which this mirrors.
const ADOLESCENTE_SKILL_POWER_GROUP_INDEX = 1;

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
  classes: CharacterClass[],
  complications: Complication[],
): CreateCharacterPayload {
  const origin = origins.find((o) => o.id === draft.originId()) ?? null;
  const originGroups = origin?.grants ?? [];
  const originChoices = draft.originChoices();

  const skillPowerGroup = originGroups[ADOLESCENTE_SKILL_POWER_GROUP_INDEX] ?? null;
  const adolescenteCase: 'origin' | 'class' | null =
    draft.ageBracket() !== 'adolescente' || !skillPowerGroup
      ? null
      : skillPowerGroup.picks >= 2
        ? 'origin'
        : 'class';
  const overrideIds = new Set(draft.adolescenteOverride());

  const trainedSkillIds = new Set<number>();
  const powerIds = new Set<number>();
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
      if (option.tag === 'skill' && option.op === 'trains' && option.skill_id !== undefined) {
        trainedSkillIds.add(option.skill_id);
      } else if (option.tag === 'power' && option.op === 'grant' && option.power_id !== undefined) {
        powerIds.add(option.power_id);
      } else if (option.tag === 'accessory' && option.accessory_id !== undefined) {
        inventory.push({ item_type: 'accessory', item_id: option.accessory_id, worn: true });
      } else if (option.tag === 'armor' && option.armor_id !== undefined) {
        inventory.push({ item_type: 'armor', item_id: option.armor_id, worn: true });
      }
    });
  });

  // classSkillChoices are always training picks — Origem em Construção's
  // "class" fallback case strips a skill id directly here instead of an
  // origin option index.
  draft.classSkillChoices().forEach((ids) => {
    ids.forEach((id) => {
      if (adolescenteCase === 'class' && overrideIds.has(id)) {
        return;
      }
      trainedSkillIds.add(id);
    });
  });

  draft.godPowerIds().forEach((id) => powerIds.add(id));

  const generalComplicationPowerId = draft.generalComplicationPowerId();
  if (generalComplicationPowerId !== null) {
    powerIds.add(generalComplicationPowerId);
  }
  const adultoPowerId = draft.adultoPowerId();
  if (adultoPowerId !== null) {
    powerIds.add(adultoPowerId);
  }

  const startingClass = classes.find((c) => c.id === draft.classIds()[0]) ?? null;
  (startingClass?.proficiency_ids ?? []).forEach((id) => powerIds.add(id));

  const ageBracket = AGE_BRACKETS.find((b) => b.id === draft.ageBracket()) ?? null;
  (ageBracket?.powerIds ?? []).forEach((id) => powerIds.add(id));

  const complicationIds = [
    draft.generalComplicationId(),
    draft.adultoAgeComplicationId(),
    ...draft.maduroAgeComplicationIds(),
    ...draft.velhoAgeComplicationIds(),
    ...draft.anciaoAgeComplicationIds(),
  ].filter((id): id is number => id !== null);

  complicationIds.forEach((complicationId) => {
    const complication = complications.find((c) => c.id === complicationId);
    (complication?.power_ids ?? []).forEach((id) => powerIds.add(id));
  });

  // One row per character level, in order — class_level is that class's
  // own running count (matches LevelPowerRow.classLevel in step 9),
  // power_id only meaningful from class_level 2 onward.
  const classLevelCounts = new Map<number, number>();
  const levels: CreateCharacterLevel[] = draft
    .orderedClassIds()
    .map((classId, index) => {
      if (classId === null) {
        return null;
      }
      const classLevel = (classLevelCounts.get(classId) ?? 0) + 1;
      classLevelCounts.set(classId, classLevel);
      return {
        level: index + 1,
        class_id: classId,
        class_level: classLevel,
        power_id: draft.classPowerIds()[index] ?? null,
      };
    })
    .filter((row): row is CreateCharacterLevel => row !== null);

  const startingSimpleWeaponId = draft.startingSimpleWeaponId();
  if (startingSimpleWeaponId !== null) {
    inventory.push({ item_type: 'weapon', item_id: startingSimpleWeaponId, worn: true });
  }
  const startingMartialWeaponId = draft.startingMartialWeaponId();
  if (startingMartialWeaponId !== null) {
    inventory.push({ item_type: 'weapon', item_id: startingMartialWeaponId, worn: true });
  }
  const startingArmorId = draft.startingArmorId();
  if (startingArmorId !== null) {
    inventory.push({ item_type: 'armor', item_id: startingArmorId, worn: true });
  }
  const startingShieldId = draft.startingShieldId();
  if (startingShieldId !== null) {
    inventory.push({ item_type: 'shield', item_id: startingShieldId, worn: true });
  }
  // Comprar Item purchases aren't equipped by default — worn:false, same
  // as any other item sitting in inventory until the player equips it.
  draft.purchasedItemKeys().forEach((key) => {
    if (key === null) {
      return;
    }
    const { source, id } = parseShopItemKey(key);
    inventory.push({ item_type: source, item_id: id, worn: false });
  });

  const other = new Set(draft.otherAttributes());

  return {
    name: draft.name(),
    level: draft.level() ?? 1,
    base_str: draft.baseStr() + (other.has('str') ? 1 : 0),
    base_dex: draft.baseDex() + (other.has('dex') ? 1 : 0),
    base_con: draft.baseCon() + (other.has('con') ? 1 : 0),
    base_int: draft.baseInt() + (other.has('int') ? 1 : 0),
    base_knw: draft.baseKnw() + (other.has('knw') ? 1 : 0),
    base_car: draft.baseCar() + (other.has('car') ? 1 : 0),
    race_id: draft.raceId(),
    origin_id: draft.originId(),
    god_id: draft.godId(),
    portrait_id: draft.portraitId(),
    trained_skill_ids: [...trainedSkillIds],
    age: draft.age(),
    age_bracket: draft.ageBracket(),
    complication_ids: complicationIds,
    power_ids: [...powerIds],
    levels,
    inventory,
  };
}
