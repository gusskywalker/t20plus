import { Effect, Weapon } from '../../../api.service';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * Crit threat range — weapon's own base_margin is the starting point, plus
 * any mod_margin from checked effects (e.g. a Golpe Pessoal option). A
 * negative mod_margin widens the range (20 -> 19 -> 18, etc.), same sign
 * convention as every other op:add tag — the value is just added directly.
 */
export function calculateMargin(weapon: Weapon, effects: Effect[]): number {
  return weapon.base_margin + resolveTag(effects, 'mod_margin');
}
