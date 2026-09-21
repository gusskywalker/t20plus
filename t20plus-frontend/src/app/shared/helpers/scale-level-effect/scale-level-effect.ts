import { Effect } from '../../../api.service';

export const PATAMAR_LEVELS = [5, 11, 17];

/**
 * Turns a level-scaled effect into the flat `add` it is at this character
 * level — `add_per_level` (ceil(level / per_character_level) * value),
 * `add_per_patamar` (value * patamar levels 5/11/17 reached) and
 * `add_after_first` (floor((level - 1) / per_class_level) * value). Every
 * other effect is returned untouched.
 */
export function scaleLevelEffect(effect: Effect, characterLevel: number): Effect {
  if (effect.op === 'add_per_level') {
    const perLevels = effect.per_character_level ?? 1;
    return { ...effect, op: 'add', value: Math.ceil(characterLevel / perLevels) * Number(effect.value ?? 0) };
  }
  if (effect.op === 'add_per_patamar') {
    const reached = PATAMAR_LEVELS.filter((level) => characterLevel >= level).length;
    return { ...effect, op: 'add', value: reached * Number(effect.value ?? 0) };
  }
  if (effect.op === 'add_after_first') {
    const perLevels = effect.per_class_level ?? 1;
    return { ...effect, op: 'add', value: Math.floor((characterLevel - 1) / perLevels) * Number(effect.value ?? 0) };
  }
  return effect;
}
