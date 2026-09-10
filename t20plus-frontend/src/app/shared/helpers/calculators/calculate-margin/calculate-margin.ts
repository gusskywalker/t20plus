import { Effect, Weapon } from '../../../../api.service';
import { resolveTag } from '../../tag-solver/tag-solver';

/**
 * Crit threat range — weapon's own base_margin is the starting point, plus
 * any mod_margin from checked effects (e.g. a Golpe Pessoal option). A
 * negative mod_margin widens the range (20 -> 19 -> 18, etc.), same sign
 * convention as every other op:add tag — the value is just added directly.
 *
 * A mod_margin with op 'set'/'override' (e.g. Disparo Sublime's "esse
 * ataque é um acerto crítico automático" — margin forced to 1) replaces
 * the whole margin instead, ignoring base_margin and any op:add entries —
 * same last-match-wins convention as calculate-attribute-dmg.ts's own
 * override check, since resolveTag can't tell "set to 1" apart from "add
 * 1" on its own.
 */
export function calculateMargin(weapon: Weapon, effects: Effect[]): number {
  const override = [...effects].reverse().find((e) => e.tag === 'mod_margin' && (e.op === 'set' || e.op === 'override'));
  if (override) {
    return Number(override.value ?? 0);
  }
  return weapon.base_margin + resolveTag(effects, 'mod_margin');
}
