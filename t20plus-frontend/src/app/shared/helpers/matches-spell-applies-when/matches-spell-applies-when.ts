import { AppliesWhen } from '../../../api.service';

/**
 * The AND-across-present-fields check every "does this granted power's
 * applies_when match this specific spell/cast" consumer needs —
 * calculate-spell-cd's mod_cd bonus, spell-casting-modal's mod_spell_pm_cost/
 * mod_spell_dmg_per_die bonuses, and matchingSpellEnhancementPowers'
 * eligibility filter used to each hand-roll their own copy of this same
 * filter (a different field subset each time). A field absent on the power
 * is never a restriction; a field present on the power must match the given
 * context.
 */
export interface SpellAppliesWhenContext {
  school: string | null;
  extraSchools?: string[];
  type?: string | null;
  resistance?: string | null;
  damageType?: string | null;
  casterMaxCircle?: number;
  doubleKnown?: boolean;
  spellId?: number;
  // Id of the power that granted this spell through its own
  // grant_or_reduce_spell_pm_cost_by_1, when there is one.
  grantedByPowerId?: number;
  actionCost?: string;
  hasAffectedArea?: boolean;
  range?: string | null;
}

export function matchesSpellAppliesWhen(appliesWhen: AppliesWhen | null | undefined, context: SpellAppliesWhenContext): boolean {
  if (!appliesWhen) {
    return true;
  }
  if (appliesWhen.spell_schools) {
    const schools = [...(context.school ? [context.school] : []), ...(context.extraSchools ?? [])];
    if (!schools.some((school) => appliesWhen.spell_schools?.includes(school))) {
      return false;
    }
  }
  if (appliesWhen.spell_types && !(context.type && appliesWhen.spell_types.includes(context.type))) {
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
  if (appliesWhen.spell_double_known && !context.doubleKnown) {
    return false;
  }
  if (appliesWhen.spell_ids && !(context.spellId !== undefined && appliesWhen.spell_ids.includes(context.spellId))) {
    return false;
  }
  if (appliesWhen.spell_granted_by_power_id !== undefined && context.grantedByPowerId !== appliesWhen.spell_granted_by_power_id) {
    return false;
  }
  if (appliesWhen.spell_action_costs &&!(context.actionCost && appliesWhen.spell_action_costs.includes(context.actionCost))) {
    return false;
  }
  if (appliesWhen.spell_has_affected_area && !context.hasAffectedArea) {
    return false;
  }
  if (appliesWhen.spell_ranges && !(context.range && appliesWhen.spell_ranges.includes(context.range))) {
    return false;
  }
  return true;
}
