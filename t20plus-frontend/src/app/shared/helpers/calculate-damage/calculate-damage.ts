import { Effect } from '../../../api.service';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * The damage roll's final total — the weapon's rolled base_dmg dice plus
 * whatever mod_dmg bonuses the player checked on for this attack (same
 * checklist as calculateHit's mod_hit — see attack-modal step 2).
 * checkedEffects is passed in directly for the same reason as calculateHit:
 * which powers apply is a per-roll player choice, not standing state.
 */
export function calculateDamage(diceTotal: number, checkedEffects: Effect[]): number {
  return diceTotal + resolveTag(checkedEffects, 'mod_dmg');
}
