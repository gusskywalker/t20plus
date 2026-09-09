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
 * Only rows with `is_active: true` are included — set once at insert time
 * on the backend (true for usability 'passive', false otherwise — see
 * create_character_active_effects_table.php's own comment), so this stays
 * a plain flag check instead of re-deriving "does this count" from
 * usability here. A passive power's row is is_active from the moment it's
 * granted; an 'active' power's row only becomes is_active once its own
 * Ativar button flips it, at which point its effects start folding into
 * Defesa/PV/PM/skill totals automatically, no extra logic needed here.
 * roll_active rows never get toggled at all, so they stay excluded
 * forever, same net effect as the old usability check.
 *
 * `add_per_level` effects are pre-scaled here into a flat `add` (value =
 * ceil(character.level / per_levels) * value — counts from level 1, e.g.
 * Vontade de Ferro/Sangue Élfico's own "no 1º nível e a cada dois níveis"
 * cadence: levels 1/3/5/7 each add one more step) since this is the one
 * place that already has the character's level in scope — resolveTag never
 * needs to know about levels at all. per_levels: 1 is unaffected by the
 * ceil (every level already adds its own step under floor too).
 */
export function getActiveEffects(character: ActiveEffectsSource, powers: Power[]): Effect[] {
  const effects: Effect[] = [];

  for (const activeEffect of character.active_effects ?? []) {
    if (!activeEffect.is_active) {
      continue;
    }
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (!power) {
      continue;
    }
    for (const effect of power.effects ?? []) {
      if (effect.op === 'add_per_level') {
        const perLevels = effect.per_levels ?? 1;
        const scaled = Math.ceil(character.level / perLevels) * Number(effect.value ?? 0);
        effects.push({ ...effect, op: 'add', value: scaled });
        continue;
      }
      effects.push(effect);
    }
  }

  return effects;
}
