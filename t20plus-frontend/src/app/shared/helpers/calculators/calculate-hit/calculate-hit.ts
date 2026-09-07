import { Effect } from '../../../../api.service';
import { resolveTag } from '../../tag-solver/tag-solver';

/**
 * The attack roll's final total — the raw d20 result, plus the Luta/
 * Pontaria skill bonus for the weapon in play (caller resolves which skill
 * and its bonus via calculateSkillBonus — this function doesn't know about
 * weapons/purpose), plus whatever mod_hit bonuses apply to this roll.
 * checkedEffects is assembled by the caller (attack-modal), not derived
 * here — it's a mix of per-roll player choices (roll_active powers checked
 * in the power checklist, e.g. Ataque Poderoso) AND standing character
 * state (any 'active' power currently is_active, e.g. Percepção Temporal,
 * via getActiveEffects — see attack-modal's standingActiveEffects).
 */
export function calculateHit(roll: number, skillBonus: number, checkedEffects: Effect[]): number {
  return roll + skillBonus + resolveTag(checkedEffects, 'mod_hit');
}
