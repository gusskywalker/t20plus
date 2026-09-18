import { Origin } from '../../../api.service';

// Dinheiro Inicial by Nível. Level 1 is "4d6" in the sourcebook — hardcoded
// to 14 (the roll's median) rather than actually rolled.
const TIBARES_BY_LEVEL: Record<number, number> = {
  1: 14,
  2: 300,
  3: 600,
  4: 1000,
  5: 2000,
  6: 3000,
  7: 5000,
  8: 7000,
  9: 10000,
  10: 13000,
  11: 19000,
  12: 27000,
  13: 36000,
  14: 49000,
  15: 66000,
  16: 88000,
  17: 110000,
  18: 150000,
  19: 200000,
  20: 260000,
};

/**
 * Base tibares off the starting table plus any origin grant bonus (e.g.
 * Coureiro's T$ 100 em itens alquímicos) — used both by character-creation-
 * items-step.ts (the read-only Tibares field) and character-payload.ts (the
 * actual value saved onto the new character), so there's one source of
 * truth instead of two copies drifting apart.
 */
export function calculateStartingTibares(totalLevel: number, origin: Origin | null, originChoices: number[][]): number {
  const groups = origin?.grants ?? [];
  let bonus = 0;
  groups.forEach((group, groupIndex) => {
    (originChoices[groupIndex] ?? []).forEach((optionIndex) => {
      const option = group.options[optionIndex];
      if (option?.tag === 'tibares' && option.op === 'add') {
        bonus += option.value ?? 0;
      }
    });
  });
  return (TIBARES_BY_LEVEL[totalLevel] ?? 0) + bonus;
}
