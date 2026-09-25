import { Character, Effect, Power } from '../../../api.service';
import { calculateStatBonus } from '../calculators/calculate-stat-bonus/calculate-stat-bonus';
import { resolveCasterKeyAttribute, resolveCasterMaxCircle } from '../resolve-spell-caster-info/resolve-spell-caster-info';
import { resolveArcanistaLevels } from '../resolve-arcanista-levels/resolve-arcanista-levels';
import { resolveNumericSentinels } from '../resolve-numeric-sentinels/resolve-numeric-sentinels';

// The placeholders that need more than the character's attributes/level
// (bare attribute codes and character_level live in
// resolve-numeric-sentinels.ts). null = not a sentinel this function knows
// how to resolve (a plain number, dice notation, or another sentinel like
// mod_def_from_shield/weapon_die — those stay untouched, resolved by
// whatever specifically consumes that tag, e.g. weapon_die ->
// calculate-weapon-dice.ts).
function resolveSentinel(sentinel: string, character: Character, powers: Power[]): number | null {
  if (sentinel === 'key_attribute') {
    // resolveCasterKeyAttribute lives in resolve-spell-caster-info.ts, not
    // here — this file stays domain-agnostic (character_level, raw
    // attribute codes); knowing what spell_key_attribute even means is
    // spellcasting-system knowledge, not a generic sentinel concept.
    return calculateStatBonus(character, resolveCasterKeyAttribute(character, powers));
  }
  if (sentinel === 'arcanista_levels') {
    // Same reasoning as key_attribute above — tied to one specific class,
    // not a generic sentinel concept, so the actual counting lives in
    // resolve-arcanista-levels.ts.
    return resolveArcanistaLevels(character);
  }
  return null;
}

/**
 * Turns an effect's placeholder `value`/`limit` into numbers for the
 * character: bare attribute codes and character_level (see
 * resolve-numeric-sentinels.ts — getActiveEffects already applies those, so
 * they only ever resolve here for effects read off a power directly), plus
 * key_attribute, arcanista_levels and per_available_spell_circle. An
 * 'attribute_knw' value names an attribute and is never resolved. Effects
 * with nothing to resolve pass through unchanged.
 */
export function resolveEffectSentinels(effects: Effect[], character: Character, powers: Power[], availableSpellCircle?: number): Effect[] {
  return effects.map((original) => {
    const effect = resolveNumericSentinels(original, character.level, (code) => calculateStatBonus(character, code));
    const resolvedValue = typeof effect.value === 'string' ? resolveSentinel(effect.value, character, powers) : null;
    const resolvedLimit = typeof effect.limit === 'string' ? resolveSentinel(effect.limit, character, powers) : null;

    const perAvailableSpellCircle = effect.per_available_spell_circle;

    if (resolvedValue === null && resolvedLimit === null && perAvailableSpellCircle === undefined) {
      return effect;
    }

    const baseValue = resolvedValue ?? (typeof effect.value === 'number' ? effect.value : 0);
    // per_available_spell_circle: `value` is granted once per N círculos the
    // casting class can cast (the caller passes the spell's own casting
    // class círculo; without one, the character's caster círculo).
    const value =
      perAvailableSpellCircle !== undefined
        ? baseValue * Math.floor((availableSpellCircle ?? resolveCasterMaxCircle(character, powers)) / Math.max(1, perAvailableSpellCircle))
        : baseValue;
    return { ...effect, value: resolvedLimit !== null ? Math.min(value, resolvedLimit) : value };
  });
}
