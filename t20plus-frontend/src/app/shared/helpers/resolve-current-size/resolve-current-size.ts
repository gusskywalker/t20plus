import { Character, Effect, Power } from '../../../api.service';
import { getActiveEffects } from '../get-active-effects/get-active-effects';

const MIN_SIZE = -2;
const MAX_SIZE = 3;

/**
 * The character's size right now: the stored characters.current_size (the
 * base size, set at creation) shifted by every active mod_current_size
 * (e.g. Crescimento Feérico). A growing effect never shrinks a character
 * that's already past its own max_size, and entries sharing a stack_group
 * only count once (the biggest one).
 */
export function resolveCurrentSize(character: Character, powers: Power[]): number {
  const effects = getActiveEffects(character, powers).filter((effect) => effect.tag === 'mod_current_size' && effect.op === 'add');

  const bestByGroup = new Map<string, Effect>();
  for (const effect of effects) {
    if (!effect.stack_group) {
      continue;
    }
    const current = bestByGroup.get(effect.stack_group);
    if (!current || Number(effect.value ?? 0) > Number(current.value ?? 0)) {
      bestByGroup.set(effect.stack_group, effect);
    }
  }
  const survivors = effects.filter((effect) => !effect.stack_group || bestByGroup.get(effect.stack_group) === effect);

  let size = character.current_size;
  for (const effect of survivors) {
    const value = Number(effect.value ?? 0);
    size = value > 0 ? Math.max(size, Math.min(size + value, effect.max_size ?? MAX_SIZE)) : size + value;
  }
  return Math.min(MAX_SIZE, Math.max(MIN_SIZE, size));
}
