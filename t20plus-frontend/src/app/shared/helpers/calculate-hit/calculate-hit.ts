import { Effect } from '../../../api.service';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * The attack roll's final total — the raw d20 result, plus the Luta/
 * Pontaria skill bonus for the weapon in play (caller resolves which skill
 * and its bonus via calculateSkillBonus — this function doesn't know about
 * weapons/purpose), plus whatever mod_hit bonuses the player checked on for
 * this specific roll (Percepção Temporal, Ataque Poderoso, etc. — see
 * attack-modal's power checklist). checkedEffects is deliberately NOT
 * derived from getActiveEffects/is_active like every other calculate-*
 * helper: which powers apply to a given attack is a per-roll player choice,
 * not standing character state, so it's passed in directly instead of
 * resolved from the character here.
 */
export function calculateHit(roll: number, skillBonus: number, checkedEffects: Effect[]): number {
  return roll + skillBonus + resolveTag(checkedEffects, 'mod_hit');
}
