import { Character, Power } from '../../../api.service';
import { calculateSpellSlotCircleCaps, SpellSlot } from '../calculators/calculate-spell-slot-circle-caps/calculate-spell-slot-circle-caps';
import { resolveExtraSpellChoiceSlots } from '../resolve-extra-spell-choice-slots/resolve-extra-spell-choice-slots';

/**
 * Which NEW known-spell slots (if any) a class grants at the level it's
 * about to reach — used by level-change-modal, the level-up counterpart to
 * character-creation's resolveCasterSpellSlots. Reuses the same
 * calculateSpellSlotCircleCaps growth math (never re-derived here) and
 * filters down to just the slots tagged with this exact classLevel, since
 * that function otherwise returns every slot from level 1 up.
 *
 * The caster power (carrying starting_spell_count/spell_count_growth, e.g.
 * Arcanista's Bruxo/Feiticeiro/Mago) is found either already granted
 * (a returning caster class) or as selectedPowerId itself (a brand new
 * caster class's first level, where the Caminho pick IS that power) — so a
 * first-time multiclass into a caster works the same as a returning one.
 */
export function resolveNewSpellSlotsAtLevel(
  character: Character,
  classId: number,
  newClassLevel: number,
  selectedPowerId: number | null,
  powers: Power[],
  pickedPowerIds: (number | null)[] = [],
): SpellSlot[] {
  const extraSlots = pickedPowerIds.flatMap((powerId) => resolveExtraSpellChoiceSlots(classId, newClassLevel, powers.find((power) => power.id === powerId)));
  return [...resolveClassSpellSlotsAtLevel(character, classId, newClassLevel, selectedPowerId, powers), ...extraSlots];
}

function resolveClassSpellSlotsAtLevel(character: Character, classId: number, newClassLevel: number, selectedPowerId: number | null, powers: Power[]): SpellSlot[] {
  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const casterPower = powers.find(
    (power) =>
      (grantedPowerIds.has(power.id) || power.id === selectedPowerId) &&
      (power.effects ?? []).some((effect) => effect.tag === 'starting_spell_count') &&
      (power.prerequisites ?? []).some((prerequisite) => prerequisite.type === 'class' && (prerequisite.class_ids ?? []).includes(classId)),
  );
  if (!casterPower) {
    return [];
  }

  const startingEffect = (casterPower.effects ?? []).find((effect) => effect.tag === 'starting_spell_count');
  const growthEffect = (casterPower.effects ?? []).find((effect) => effect.tag === 'spell_count_growth');
  if (!startingEffect || !growthEffect) {
    return [];
  }

  const startingSpellCount = Number(startingEffect.value ?? 0);
  const growthValue = Number(growthEffect.value ?? 0);
  const growthPerLevels = growthEffect.per_class_level ?? 1;

  return calculateSpellSlotCircleCaps(classId, newClassLevel, startingSpellCount, growthValue, growthPerLevels).filter((slot) => slot.classLevel === newClassLevel);
}
