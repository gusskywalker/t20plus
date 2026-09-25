import { Armor, Character, Power } from '../../../../api.service';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';
import { resolveEffectSentinels } from '../../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveTag } from '../../tag-solver/tag-solver';

/**
 * Deslocamento — the character's race's own base_movement, plus any
 * mod_movement from active powers/conditions. No per-level accumulation
 * like calculateMaxPv/calculateMaxPm (movement doesn't scale with level),
 * closer in shape to calculateDefense's own single base + resolveTag —
 * except mod_movement also uses op 'set' (a new base, e.g. Asas de Abutre's
 * 12m — adds still stack on top), 'override' (the final value outright,
 * e.g. Caído's 1,5m — beats every add, the lowest one wins) and 'multiply'
 * (e.g. Lento's "reduzido à metade"), so it needs a bit more than a plain
 * resolveTag call. Heavy armor's own -3m (heavy_armor_movement_penalty on
 * the worn armor's own effects, waived by waive_heavy_armor_movement_penalty
 * on an active power/condition, e.g. a Golem's Chassi material) is folded
 * into that same additive step, not a hardcoded flat subtraction, so it
 * stacks/waives through the exact same pipeline as every other add.
 */
export function calculateMovement(character: Character, armors: Armor[], powers: Power[]): number {
  const activeEffects = resolveEffectSentinels(getActiveEffects(character), character, powers);
  const movementEffects = activeEffects.filter((effect) => effect.tag === 'mod_movement');

  const wornArmorItem = (character.inventory ?? []).find((item) => item.item_type === 'armor' && item.worn);
  const wornArmor = wornArmorItem ? armors.find((armor) => armor.id === wornArmorItem.item_id) : undefined;
  const hasHeavyArmorPenalty = (wornArmor?.effects ?? []).some((effect) => effect.tag === 'heavy_armor_movement_penalty' && effect.op === 'grant');
  const hasWaiver = activeEffects.some((effect) => effect.tag === 'waive_heavy_armor_movement_penalty' && effect.op === 'grant');
  const heavyArmorEffect = hasHeavyArmorPenalty && !hasWaiver ? [{ tag: 'mod_movement', op: 'add', value: -3 }] : [];

  const setValues = movementEffects.filter((effect) => effect.op === 'set').map((effect) => Number(effect.value ?? 0));
  const overrideValues = movementEffects.filter((effect) => effect.op === 'override').map((effect) => Number(effect.value ?? 0));
  const base = setValues.length > 0 ? setValues[setValues.length - 1] : (character.race?.base_movement ?? 0);
  const total = overrideValues.length > 0 ? Math.min(...overrideValues) : base + resolveTag([...movementEffects.filter((effect) => effect.op === 'add'), ...heavyArmorEffect], 'mod_movement');

  // multiply isn't a case resolveTag handles at all — applied last, on top
  // of whatever the running total already is, same "multiply always comes
  // after every additive step" convention spell-casting-modal's own
  // fail-resistance damage halving already follows.
  const multiplier = movementEffects.filter((effect) => effect.op === 'multiply').reduce((acc, effect) => acc * Number(effect.value ?? 1), 1);

  return total * multiplier;
}
