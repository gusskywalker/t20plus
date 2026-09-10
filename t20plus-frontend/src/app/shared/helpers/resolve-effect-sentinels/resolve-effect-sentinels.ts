import { Character, Effect, Power } from '../../../api.service';
import { calculateStatBonus } from '../calculators/calculate-stat-bonus/calculate-stat-bonus';

const ATTRIBUTE_CODES = ['str', 'dex', 'con', 'int', 'knw', 'car'];

// Tags excluded from sentinel resolution even though their value/limit may
// look like one — mod_dmg_attribute's 'str'/'dex'/etc is an attribute CODE
// selecting which stat adds to damage (see calculate-attribute-dmg.ts,
// which already owns resolving it), not an amount to substitute a number
// for. Every other tag that's ever used an attribute-code-shaped value
// has meant "amount" — this is the one exception on record, so it gets
// one explicit line here rather than every future amount-tag needing to
// register itself just to keep working.
const SENTINEL_EXCLUDED_TAGS = ['mod_dmg_attribute'];

// null = not a sentinel this function knows how to resolve (a plain
// number, dice notation, or another sentinel like mod_def_from_shield/
// weapon_die — those stay untouched, resolved by whatever specifically
// consumes that tag, e.g. weapon_die -> calculate-weapon-dice.ts).
function resolveSentinel(sentinel: string, character: Character, powers: Power[]): number | null {
  if (sentinel === 'character_level') {
    return character.level;
  }
  if (ATTRIBUTE_CODES.includes(sentinel)) {
    // calculateStatBonus, not base_str/base_knw/etc directly — folds in
    // any mod_str/mod_knw/etc from the character's OTHER active powers
    // (e.g. Aumentar Atributo), so this reflects the current stat, not
    // the base one.
    return calculateStatBonus(character, sentinel, powers);
  }
  return null;
}

/**
 * Turns a sentinel `value` (an attribute code or `character_level`) into
 * the character's actual current number, and applies a sentinel `limit` as
 * a cap on that result (e.g. Percepção Temporal/Arqueiro: "+Conhecimento,
 * mas não mais que seu nível" — value: 'knw', limit: 'character_level').
 * Leaves every other value/limit shape untouched. Effects with neither a
 * sentinel value nor limit pass through unchanged. Tags in
 * SENTINEL_EXCLUDED_TAGS are never considered, regardless of shape — see
 * its own comment.
 */
export function resolveEffectSentinels(effects: Effect[], character: Character, powers: Power[]): Effect[] {
  return effects.map((effect) => {
    if (SENTINEL_EXCLUDED_TAGS.includes(effect.tag)) {
      return effect;
    }
    const resolvedValue = typeof effect.value === 'string' ? resolveSentinel(effect.value, character, powers) : null;
    const resolvedLimit = typeof effect.limit === 'string' ? resolveSentinel(effect.limit, character, powers) : null;

    if (resolvedValue === null && resolvedLimit === null) {
      return effect;
    }

    const value = resolvedValue ?? (typeof effect.value === 'number' ? effect.value : 0);
    return { ...effect, value: resolvedLimit !== null ? Math.min(value, resolvedLimit) : value };
  });
}
