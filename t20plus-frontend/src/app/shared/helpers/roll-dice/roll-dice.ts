/**
 * Rolls a dice notation string like "1d12" or "2d6" — sums N rolls of a
 * dN. Just the raw dice, no flat modifier suffix support (every flat
 * modifier in this app already lives in its own mod_dmg effect entry,
 * never baked into the notation string itself). No per-die breakdown or
 * animation — some builds roll 100+ dice, so this only ever returns the
 * summed total.
 */
export function rollDice(notation: string): number {
  const match = notation.match(/^(\d+)d(\d+)$/);
  if (!match) {
    return 0;
  }
  const count = Number(match[1]);
  const sides = Number(match[2]);

  let total = 0;
  for (let i = 0; i < count; i++) {
    total += Math.floor(Math.random() * sides) + 1;
  }
  return total;
}

/**
 * Same roll as rollDice, but also returns each individual die's own
 * result — for a weapon's own base die specifically, small enough that
 * per-die tracking is cheap (unlike rollDice's own general-purpose case,
 * which can't afford it).
 */
export function rollDiceDetailed(notation: string): { total: number; rolls: number[] } {
  const match = notation.match(/^(\d+)d(\d+)$/);
  if (!match) {
    return { total: 0, rolls: [] };
  }
  const count = Number(match[1]);
  const sides = Number(match[2]);

  const rolls: number[] = [];
  for (let i = 0; i < count; i++) {
    rolls.push(Math.floor(Math.random() * sides) + 1);
  }
  return { total: rolls.reduce((sum, roll) => sum + roll, 0), rolls };
}
