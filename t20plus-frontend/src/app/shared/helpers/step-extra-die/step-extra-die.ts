// One ascending progression — alternates in the same step (comma/"ou" in
// the rulebook) are interchangeable, grouped together here. Own copy, not
// shared with calculate-weapon-dice.ts — that one steps a WEAPON's own die
// (weapon_step_increase/weaponSize), this one steps an extra_die effect's
// own die by character level (e.g. Executor's +1d6, one step every 4
// levels past 1st). Different mechanics, kept fully separate on purpose.
// See claude-stuff/rules/weapon-rules.md's own copy of this line.
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
 * Steps an extra_die effect's own die notation by however many
 * die_steps_per_levels thresholds the character's level has crossed past
 * level 1 (e.g. Executor: dieStepsPerLevels 4, base "1d6" — level 1-4 stays
 * 1d6, level 5-8 steps to 1d8, level 9-12 to 1d10, etc.). Clamped to the
 * table's own ends, same as calculate-weapon-dice's own clamp.
 */
export function stepExtraDie(baseNotation: string, characterLevel: number, dieStepsPerLevels: number): string {
  const steps = Math.floor((characterLevel - 1) / dieStepsPerLevels);
  if (steps === 0) {
    return baseNotation;
  }

  const currentIndex = DAMAGE_STEPS.findIndex((step) => step.includes(baseNotation));
  if (currentIndex === -1) {
    return baseNotation; // baseNotation isn't on the table — shouldn't happen for a real power
  }

  const newIndex = Math.min(Math.max(currentIndex + steps, 0), DAMAGE_STEPS.length - 1);
  return DAMAGE_STEPS[newIndex][0];
}
