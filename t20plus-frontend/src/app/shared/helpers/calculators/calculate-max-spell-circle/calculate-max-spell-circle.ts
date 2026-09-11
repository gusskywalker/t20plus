const ARCANISTA_CLASS_ID = 3;

// Class-relative level -> minimum level needed for each spell círculo.
// Hardcoded per class, not a shared formula — Bardo's progression is
// already known to differ from the rest, so nothing here should be
// assumed to generalize until each class's own table is confirmed.
const MIN_LEVEL_FOR_CIRCLE: Record<number, Record<number, number>> = {
  [ARCANISTA_CLASS_ID]: { 1: 1, 2: 5, 3: 9, 4: 13, 5: 17 },
};

/** Highest spell círculo a class-relative level can access — 0 if the class isn't a caster (or isn't in the table yet). */
export function calculateMaxSpellCircle(classId: number, classLevel: number): number {
  const thresholds = MIN_LEVEL_FOR_CIRCLE[classId];
  if (!thresholds) {
    return 0;
  }
  let maxCircle = 0;
  for (const [circle, minLevel] of Object.entries(thresholds)) {
    if (classLevel >= minLevel) {
      maxCircle = Math.max(maxCircle, Number(circle));
    }
  }
  return maxCircle;
}
