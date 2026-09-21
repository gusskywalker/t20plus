import { Character, Effect, Power } from '../../../api.service';
import { isTriggerSatisfied } from '../is-trigger-satisfied/is-trigger-satisfied';
import { resolveReplacedPowerIds } from '../resolve-replaced-power-ids/resolve-replaced-power-ids';
import { scaleLevelEffect } from '../scale-level-effect/scale-level-effect';

/**
 * The only two fields getActiveEffects actually reads — narrowed from the
 * full Character so a character-creation-time CharacterDraft (which isn't a
 * real Character yet) can also be passed in, by exposing get active_effects()/
 * get level() getters of its own. See CharacterDraft's own comment on those
 * getters for why the wizard needs this (checking a power's prerequisites
 * against the draft's current, possibly level-up-modified, stats).
 */
export type ActiveEffectsSource = Pick<Character, 'active_effects' | 'active_spell_effects' | 'level'>;

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
 * ceil(character.level / per_character_level) * value — counts from level
 * 1, e.g. Vontade de Ferro/Sangue Élfico's own "no 1º nível e a cada dois
 * níveis" cadence: levels 1/3/5/7 each add one more step) since this is
 * the one place that already has the character's level in scope —
 * resolveTag never needs to know about levels at all. per_character_level:
 * 1 is unaffected by the ceil (every level already adds its own step under
 * floor too).
 *
 * `add_per_patamar` is the same idea for powers that step at the T20
 * patamar boundaries instead of a linear cadence (Novato/Veterano/
 * Campeão/Lenda — levels 5/11/17 are fixed, irregular gaps that
 * add_per_level's formula can't produce). value * however many of those
 * three levels the character has reached (e.g. Coração Heroico's own
 * +3 PM base plus +3 more at each patamar).
 *
 * `add_after_first` is add_per_level's formula shifted so level 1 never
 * contributes (floor((character.level - 1) / per_class_level) * value
 * instead of ceil(character.level / per_character_level) * value) — for
 * cadences layered on top of a separate flat starting value rather than
 * growing from level 1 itself (e.g. spell_count_growth: a caster's
 * starting spells known are their own flat number — see
 * starting_spell_count — and this only ever adds MORE on top, starting at
 * level 2 at the earliest). Its own real consumers (resolve-caster-spell-
 * slots.ts/resolve-new-spell-slots-at-level.ts) read power.effects
 * directly and use CLASS-relative level instead of character.level — this
 * branch's own character.level-based scaling is never actually consumed
 * by anything today (nothing calls resolveTag(…, 'spell_count_growth')),
 * kept only so getActiveEffects doesn't drop the op silently.
 *
 * `trigger: 'on_other_sources_satisfied'` effects (e.g. Empatia Selvagem's
 * own "+2 Adestramento" clause) only count once the granting
 * active_effect row's own other_sources_state is 'satisfied' — set by
 * ManagesPowers::grantPower() (backend) when a second, different-source
 * grant of the same power actually happens. See tag-system.md.
 */
export function getActiveEffects(character: ActiveEffectsSource, powers: Power[]): Effect[] {
  const effects: Effect[] = [];
  const activePowerIds = new Set((character.active_effects ?? []).filter((row) => row.is_active).map((row) => row.power_id));
  const replacedPowerIds = resolveReplacedPowerIds(new Set((character.active_effects ?? []).map((row) => row.power_id)), powers);

  for (const activeEffect of character.active_effects ?? []) {
    if (!activeEffect.is_active) {
      continue;
    }
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (!power || replacedPowerIds.has(power.id)) {
      continue;
    }
    // applies_when.active_power_id — this power's effects only count while
    // that other power is toggled on (e.g. Arsenal de Allihanna's Defesa
    // bonus, only while Armadura de Allihanna is active).
    if (power.applies_when?.active_power_id !== undefined && !activePowerIds.has(power.applies_when.active_power_id)) {
      continue;
    }
    // custom_effect (character_active_effects' own column) is per-character
    // customization for this specific granted-power instance — shaped
    // exactly like power.effects, so it goes through the same op-scaling
    // below. Used for open-ended player choices a shared Power row can't
    // represent (e.g. Espião's freely chosen skill_attribute target).
    for (const effect of [...(power.effects ?? []), ...(activeEffect.custom_effect ?? [])]) {
      // Only counts once THIS row's own other_sources_state says a second,
      // different-source grant actually happened (e.g. Empatia Selvagem) —
      // an 'open' or null row skips it entirely. See tag-system.md.
      if (!isTriggerSatisfied(effect, activeEffect.other_sources_state)) {
        continue;
      }
      effects.push(scaleLevelEffect(effect, character.level));
    }
  }

  // Spell buffs (character_active_spell_effects) — already the final,
  // resolved effects for that one casting (no power to join against, no
  // per-level scaling; see CharacterActiveSpellEffectRow). A row's mere
  // existence means it's active (no is_active flag — Remover deletes the
  // row outright, nothing ever needs "present but suspended"), but only
  // the passive-shaped entries within it fold in here — a 'roll_active'
  // one only applies to a specific roll and belongs in skill-roll-modal's
  // own checklist instead, never blanket-applied like this.
  for (const activeSpellEffect of character.active_spell_effects ?? []) {
    for (const effect of activeSpellEffect.effects) {
      if (effect.usability === 'roll_active') {
        continue;
      }
      effects.push(effect);
    }
  }

  return effects;
}
