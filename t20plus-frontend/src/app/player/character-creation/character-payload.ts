import {
  CreateCharacterInventoryItem,
  CreateCharacterLevel,
  CreateCharacterPayload,
  Origin,
  Power,
  Race,
} from '../../api.service';
import { ammoBundleSize, parseShopItemKey } from '../../shared/helpers/buy-item/buy-item';
import { naturalWeaponSize } from '../../shared/helpers/natural-weapon-size/natural-weapon-size';
import { resolveGrantedPowerIds } from '../../shared/helpers/resolve-granted-power-ids/resolve-granted-power-ids';
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
  races: Race[],
  powers: Power[],
): CreateCharacterPayload {
  const race = races.find((r) => r.id === draft.raceId()) ?? null;
  const weaponSize = naturalWeaponSize(race?.base_size ?? 0);
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
      if (option.tag === 'skill' && option.op === 'trains' && option.skill_id !== undefined) {
        trainedSkillIds.add(option.skill_id);
      } else if (option.tag === 'accessory' && option.accessory_id !== undefined) {
        inventory.push({ item_type: 'accessory', item_id: option.accessory_id, worn: false });
      } else if (option.tag === 'armor' && option.armor_id !== undefined) {
        inventory.push({ item_type: 'armor', item_id: option.armor_id, worn: false });
      } else if (option.tag === 'weapon' && option.weapon_id !== undefined) {
        inventory.push({ item_type: 'weapon', item_id: option.weapon_id, worn: false, weapon_size: weaponSize });
      } else if (option.tag === 'general_item' && option.general_item_id !== undefined) {
        inventory.push({ item_type: 'general_item', item_id: option.general_item_id, worn: false });
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

  const complicationIds = [
    draft.generalComplicationId(),
    draft.adultoAgeComplicationId(),
    ...draft.maduroAgeComplicationIds(),
    ...draft.velhoAgeComplicationIds(),
    ...draft.anciaoAgeComplicationIds(),
  ].filter((id): id is number => id !== null);

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

  // Nothing starts equipped — worn is always false at creation, for every
  // source (free starting gear, origin item grants, Comprar Item
  // purchases alike). Equipping is a separate action the player takes
  // later, not implied by simply owning an item.
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
  draft.purchasedItemKeys().forEach((key) => {
    if (key === null) {
      return;
    }
    const { source, id } = parseShopItemKey(key);
    // Ammo is always a fixed-size bundle (see ammoBundleSize) — same
    // rule the runtime Comprar Item modal applies, so a wizard purchase
    // doesn't land as a useless 1-arrow stack.
    const bundleSize = source === 'general_item' ? ammoBundleSize(id) : undefined;
    inventory.push({
      item_type: source,
      item_id: id,
      worn: false,
      ...(bundleSize !== undefined ? { quantity: bundleSize } : {}),
      ...(source === 'weapon' ? { weapon_size: weaponSize } : {}),
    });
  });

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

  return {
    name: draft.name(),
    // finalBaseStr/etc — raw point-buy plus Aumentar Atributo's own
    // permanent mod_base_str (see character-draft.ts) — not draft.baseStr()
    // directly, which stays step 2's own untouched point-buy value.
    base_str: draft.finalBaseStr() + (other.has('str') ? 1 : 0) + (race?.mod_str ?? 0),
    base_dex: draft.finalBaseDex() + (other.has('dex') ? 1 : 0) + (race?.mod_dex ?? 0),
    base_con: draft.finalBaseCon() + (other.has('con') ? 1 : 0) + (race?.mod_con ?? 0),
    base_int: draft.finalBaseInt() + (other.has('int') ? 1 : 0) + (race?.mod_int ?? 0),
    base_knw: draft.finalBaseKnw() + (other.has('knw') ? 1 : 0) + (race?.mod_knw ?? 0),
    base_car: draft.finalBaseCar() + (other.has('car') ? 1 : 0) + (race?.mod_car ?? 0),
    current_size: race?.base_size ?? 0,
    race_id: draft.raceId(),
    origin_id: draft.originId(),
    god_id: draft.godId(),
    portrait_id: draft.portraitId(),
    trained_skill_ids: [...trainedSkillIds],
    age: draft.age(),
    age_bracket: draft.ageBracket(),
    complication_ids: complicationIds,
    power_ids: [...powerIds, ...grantedChildPowerIds],
    tibares: draft.remainingTibares(),
    levels,
    inventory,
  };
}
