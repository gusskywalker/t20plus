import { CharacterGolpePessoalRow, Effect, Power } from '../../../api.service';

// Golpe Pessoal menu ids whose repeated picks don't stack linearly — e.g.
// Letal (id 121) picked twice totals -5 margin, not -2 + -2 = -4 (see its
// own comment in PowerSeeder.php). Keyed by id, then by pick count, to the
// effects that count should produce INSTEAD of count * that power's own
// effects. A count with no entry here (including every id not listed at
// all) falls back to the generic "sum each pick's own effects" behavior.
const NON_LINEAR_OVERRIDES: Record<number, Record<number, Effect[]>> = {
  121: { 2: [{ tag: 'mod_margin', op: 'add', value: -5 }] }, // Letal x2
};

/**
 * Merges a built golpe's power_ids into one flat Effect[] — each id's own
 * effects, concatenated once per pick (e.g. Elemental picked twice
 * contributes its extra_die entry twice), except ids in NON_LINEAR_OVERRIDES
 * above, which use their override for that exact pick count instead of
 * summing. Resolved live from the current powers list, same reasoning as
 * everywhere else that reads power_ids: never cached, so a menu item's own
 * balance change picks up automatically. The result just joins whatever
 * pool of modifiers the caller (attack-modal today, any future roll screen
 * later) is already summing — no separate resolution path downstream.
 */
export function resolveGolpePessoalEffects(golpe: CharacterGolpePessoalRow, powers: Power[]): Effect[] {
  const counts = new Map<number, number>();
  for (const id of golpe.power_ids ?? []) {
    counts.set(id, (counts.get(id) ?? 0) + 1);
  }

  return [...counts.entries()].flatMap(([id, count]) => {
    const override = NON_LINEAR_OVERRIDES[id]?.[count];
    if (override) {
      return override;
    }
    const effects = powers.find((power) => power.id === id)?.effects ?? [];
    return Array.from({ length: count }, () => effects).flat();
  });
}
