import { Character, Power } from '../../../../api.service';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';
import { resolveEffectSentinels } from '../../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveTag } from '../../tag-solver/tag-solver';

/**
 * Deslocamento — the character's race's own base_movement, plus any
 * mod_movement from active powers/conditions. No per-level accumulation
 * like calculateMaxPv/calculateMaxPm (movement doesn't scale with level),
 * closer in shape to calculateDefense's own single base + resolveTag —
 * except mod_movement also uses op 'set' (e.g. Caído's fixed 1,5m,
 * replacing base outright) and 'multiply' (e.g. Lento's "reduzido à
 * metade"), so it needs a bit more than a plain resolveTag call.
 */
export function calculateMovement(character: Character, powers: Power[]): number {
  const base = character.race?.base_movement ?? 0;
  const activeEffects = resolveEffectSentinels(getActiveEffects(character, powers), character, powers);
  const movementEffects = activeEffects.filter((effect) => effect.tag === 'mod_movement');

  // add/set resolve through resolveTag same as every other tag — a 'set'
  // (op 'set'/'override') REPLACES the running total rather than adding to
  // it, so base only survives when nothing overrides it.
  const hasOverride = movementEffects.some((effect) => effect.op === 'set' || effect.op === 'override');
  const total = hasOverride ? resolveTag(movementEffects, 'mod_movement') : base + resolveTag(movementEffects, 'mod_movement');

  // multiply isn't a case resolveTag handles at all (it only sums add/set/
  // override) — applied last, on top of whatever the running total already
  // is, same "multiply always comes after every additive step" convention
  // spell-casting-modal's own fail-resistance damage halving already
  // follows.
  const multiplier = movementEffects.filter((effect) => effect.op === 'multiply').reduce((acc, effect) => acc * Number(effect.value ?? 1), 1);

  return total * multiplier;
}
