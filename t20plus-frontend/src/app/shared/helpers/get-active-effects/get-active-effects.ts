import { Character, Effect, Power } from '../../../api.service';

/**
 * Flattens a character's character_active_effects rows into the one Effect[]
 * they grant, joined against the powers catalog. This is the character-
 * specific collection step — resolveTag (tag-solver.ts) is what actually
 * combines the tags into a number and knows nothing about Character.
 *
 * `add_per_level` effects are pre-scaled here into a flat `add` (value =
 * floor(character.level / per_levels) * value) since this is the one place
 * that already has the character's level in scope — resolveTag never needs
 * to know about levels at all.
 */
export function getActiveEffects(character: Character, powers: Power[]): Effect[] {
  const effects: Effect[] = [];

  for (const activeEffect of character.active_effects ?? []) {
    const power = powers.find((p) => p.id === activeEffect.power_id);
    for (const effect of power?.effects ?? []) {
      if (effect.op === 'add_per_level') {
        const perLevels = effect.per_levels ?? 1;
        const scaled = Math.floor(character.level / perLevels) * Number(effect.value ?? 0);
        effects.push({ ...effect, op: 'add', value: scaled });
        continue;
      }
      effects.push(effect);
    }
  }

  return effects;
}
