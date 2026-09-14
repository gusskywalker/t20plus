import { AppliesWhen } from '../../../api.service';

/**
 * The AND-across-present-fields check every "does this granted passive
 * power's applies_when match this specific spell/cast" consumer needs —
 * calculate-spell-cd's mod_cd bonus, spell-casting-modal's mod_spell_pm_cost
 * and mod_spell_dmg_per_die bonuses used to each hand-roll their own copy of
 * this same filter. A field absent on the power is never a restriction; a
 * field present on the power must match the given context.
 */
export interface SpellAppliesWhenContext {
  school: string;
  resistance?: string | null;
  damageType?: string | null;
  casterMaxCircle?: number;
}

export function matchesSpellAppliesWhen(appliesWhen: AppliesWhen | null | undefined, context: SpellAppliesWhenContext): boolean {
  if (!appliesWhen) {
    return true;
  }
  if (appliesWhen.spell_schools && !appliesWhen.spell_schools.includes(context.school)) {
    return false;
  }
  if (appliesWhen.spell_resistances && !(context.resistance && appliesWhen.spell_resistances.includes(context.resistance))) {
    return false;
  }
  if (appliesWhen.spell_damage_types && !(context.damageType && appliesWhen.spell_damage_types.includes(context.damageType))) {
    return false;
  }
  if (appliesWhen.caster_min_circle !== undefined && (context.casterMaxCircle === undefined || context.casterMaxCircle < appliesWhen.caster_min_circle)) {
    return false;
  }
  return true;
}
