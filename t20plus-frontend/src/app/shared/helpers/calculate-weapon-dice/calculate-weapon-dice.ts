import { Effect, Weapon } from '../../../api.service';
import { resolveTag } from '../tag-solver/tag-solver';

// One ascending progression — alternates in the same step (comma/"ou" in
// the rulebook) are interchangeable, grouped together here. See
// claude-stuff/rules/weapon-rules.md's own copy of this line.
const DAMAGE_STEPS: string[][] = [
  ['1'],
  ['1d2'],
  ['1d3'],
  ['1d4'],
  ['1d6'],
  ['1d8', '2d4'],
  ['1d10'],
  ['1d12', '2d6', '3d4'],
  ['3d6'],
  ['4d6'],
  ['4d8'],
  ['4d10'],
  ['4d12'],
];

/**
 * Final weapon damage-die notation — the weapon's own base_dmg stepped up
 * by every checked weapon_step_increase effect (e.g. Mestre em Arma,
 * Campeão), NOT the raw base_dmg on its own. Anything that needs "the die
 * this attack is actually rolling" (the weapon's own roll, and the
 * weapon_die sentinel on extra_die effects like Brutal) must go through
 * this, since it has to reflect every step increase, not just the base.
 * Clamped to the table's own ends — can't step below "1" or above "4d12",
 * the rulebook's stated máximo.
 */
export function calculateWeaponDice(weapon: Weapon, effects: Effect[]): string {
  const steps = resolveTag(effects, 'weapon_step_increase');
  if (steps === 0) {
    return weapon.base_dmg;
  }

  const currentIndex = DAMAGE_STEPS.findIndex((step) => step.includes(weapon.base_dmg));
  if (currentIndex === -1) {
    return weapon.base_dmg; // base_dmg isn't on the table — shouldn't happen for a real weapon
  }

  const newIndex = Math.min(Math.max(currentIndex + steps, 0), DAMAGE_STEPS.length - 1);
  return DAMAGE_STEPS[newIndex][0];
}
