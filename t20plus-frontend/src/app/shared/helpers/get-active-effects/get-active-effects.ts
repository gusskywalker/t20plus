import { Character, Effect, Power } from '../../../api.service';

/**
 * The only two fields getActiveEffects actually reads — narrowed from the
 * full Character so a character-creation-time CharacterDraft (which isn't a
 * real Character yet) can also be passed in, by exposing get active_effects()/
 * get level() getters of its own. See CharacterDraft's own comment on those
 * getters for why the wizard needs this (checking a power's prerequisites
 * against the draft's current, possibly level-up-modified, stats).
 */
export type ActiveEffectsSource = Pick<Character, 'active_effects' | 'level'>;

/**
 * Flattens a character's character_active_effects rows into the one Effect[]
 * they grant, joined against the powers catalog. This is the character-
 * specific collection step — resolveTag (tag-solver.ts) is what actually
 * combines the tags into a number and knows nothing about Character.
 *
 * Only `usability: 'passive'` powers are included — per the powers
 * migration's own doc comment, passive effects always apply, but
 * active/roll_active/trigger/trigger_active effects only count while their roll/condition
 * is actually happening (e.g. Afinidade com a Tormenta's trigger_on:
 * targets_you_tormenta), which isn't something a static sheet number like
 * Defesa/PM/skill bonus should reflect — no roll-specific resolver exists
 * yet to decide "is this trigger/roll live right now."
 *
 * `add_per_level` effects are pre-scaled here into a flat `add` (value =
 * floor(character.level / per_levels) * value) since this is the one place
 * that already has the character's level in scope — resolveTag never needs
 * to know about levels at all.
 */
export function getActiveEffects(character: ActiveEffectsSource, powers: Power[]): Effect[] {
  const effects: Effect[] = [];

  for (const activeEffect of character.active_effects ?? []) {
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (power?.usability !== 'passive') {
      continue;
    }
    for (const effect of power.effects ?? []) {
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
