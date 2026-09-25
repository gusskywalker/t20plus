import { Effect } from '../../../api.service';

export const ATTRIBUTE_CODES = ['str', 'dex', 'con', 'int', 'knw', 'car'];

/**
 * Turns the number-shaped placeholders of one effect into numbers: a bare
 * attribute code ('knw', 'str', ...) in `value` or `limit` becomes that
 * attribute's current value, and 'character_level' becomes the level. A
 * `limit` caps the effect's value (a value under the limit stays as it is,
 * a value over it becomes the limit); a limit on an effect with no value at
 * all makes the limit itself the value. `limit` is returned resolved too.
 * Anything else — an 'attribute_knw' name, key_attribute, dice notation,
 * plain numbers — is left untouched. Effects with nothing to resolve are
 * returned as the same object.
 */
export function resolveNumericSentinels(effect: Effect, characterLevel: number, attributeValue: (code: string) => number): Effect {
  const numberOf = (placeholder: unknown): number | null => {
    if (typeof placeholder !== 'string') {
      return null;
    }
    if (placeholder === 'character_level') {
      return characterLevel;
    }
    return ATTRIBUTE_CODES.includes(placeholder) ? attributeValue(placeholder) : null;
  };
  const resolvedValue = numberOf(effect.value);
  const resolvedLimit = numberOf(effect.limit);
  if (resolvedValue === null && resolvedLimit === null) {
    return effect;
  }
  let value: number | string | undefined = resolvedValue ?? effect.value;
  if (resolvedLimit !== null) {
    if (typeof value === 'number') {
      value = Math.min(value, resolvedLimit);
    } else if (value === undefined) {
      value = resolvedLimit;
    }
  }
  return { ...effect, value, limit: resolvedLimit ?? effect.limit };
}
