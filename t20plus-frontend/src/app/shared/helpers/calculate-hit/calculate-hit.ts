import { Effect } from '../../../api.service';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * The attack roll's final total — the raw d20 result plus whatever mod_hit
 * bonuses the player checked on for this specific roll (Percepção Temporal,
 * Ataque Poderoso, etc. — see attack-modal's power checklist). Deliberately
 * NOT derived from getActiveEffects/is_active like every other calculate-*
 * helper: which powers apply to a given attack is a per-roll player choice,
 * not standing character state, so the checked Effect[] is passed in
 * directly instead of resolved from the character here.
 */
export function calculateHit(roll: number, checkedEffects: Effect[]): number {
  return roll + resolveTag(checkedEffects, 'mod_hit');
}
