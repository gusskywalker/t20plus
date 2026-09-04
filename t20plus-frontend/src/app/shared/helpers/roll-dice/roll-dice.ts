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
