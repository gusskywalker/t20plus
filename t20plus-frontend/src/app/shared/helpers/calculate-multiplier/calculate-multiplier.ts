import { Effect, Weapon } from '../../../api.service';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * Crit damage multiplier — weapon's own base_multiplier is the starting
 * point, plus any mod_multiplier from checked effects (e.g. Golpe
 * Pessoal's Destruidor, +1).
 */
export function calculateMultiplier(weapon: Weapon, effects: Effect[]): number {
  return weapon.base_multiplier + resolveTag(effects, 'mod_multiplier');
}
