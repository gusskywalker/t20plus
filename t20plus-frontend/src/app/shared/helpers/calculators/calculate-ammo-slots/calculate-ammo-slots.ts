// Bespoke per-ammo-id slot rule — quantity-bucketed, not a flat
// slots-times-quantity multiply (unlike every other stacked general_item).
// A stack shrinks as arrows get spent (once ammo-spend-on-attack is built)
// and the space it takes doesn't scale linearly with count. Different ammo
// ids can have entirely different thresholds — no shared formula assumed,
// each one is its own explicit table.
const ammoSlotThresholds: Record<number, { min: number; slots: number }[]> = {
  2: [
    // Flechas (20) — 20/0.5/0, per the book's ammo bundling rule.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
  3: [
    // Munição (20) — same 20/0.5/0 bundling rule as Flechas.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
  66: [
    // Virotes (20) — same 20/0.5/0 bundling rule as Flechas.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
  67: [
    // Dardos (10) — same 20/0.5/0 bundling rule as Flechas.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
  68: [
    // Pedras (20) — same 20/0.5/0 bundling rule as Flechas.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
  71: [
    // Flechas Assobiadoras (20) — same 20/0.5/0 bundling rule as Flechas.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
  72: [
    // Flechas de Caça (20) — same 20/0.5/0 bundling rule as Flechas.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
  73: [
    // Flechas Pesadas (20) — same 20/0.5/0 bundling rule as Flechas.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
  74: [
    // Virotes Pesados (20) — same 20/0.5/0 bundling rule as Flechas.
    { min: 20, slots: 1 },
    { min: 10, slots: 0.5 },
    { min: 1, slots: 0 },
  ],
};

// Ammo sold one by one: every unit takes its own slots, no bucketing.
const ammoSlotsPerUnit: Record<number, number> = {
  69: 0.5, // Bomba (Munição)
  70: 0.5, // Bola de Ferro (Munição)
};

// null = this general_item id isn't a special-cased ammo — caller should
// fall back to the generic catalog slots * quantity multiply. 0 for
// quantity <= 0 — that row is meant to be destroyed once ammo-spend-to-zero
// cleanup exists, not counted as taking space in the meantime.
export function calculateAmmoSlots(generalItemId: number, quantity: number): number | null {
  const perUnit = ammoSlotsPerUnit[generalItemId];
  if (perUnit !== undefined) {
    return Math.max(quantity, 0) * perUnit;
  }
  const thresholds = ammoSlotThresholds[generalItemId];
  if (!thresholds) {
    return null;
  }
  if (quantity <= 0) {
    return 0;
  }
  for (const { min, slots } of thresholds) {
    if (quantity >= min) {
      return slots;
    }
  }
  return 0;
}
