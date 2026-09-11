import { calculateMaxSpellCircle } from '../calculate-max-spell-circle/calculate-max-spell-circle';

export interface SpellSlot {
  /** Which class granted this slot — a character can have more than one caster class at once (e.g. Arcanista + Bardo), so every slot must carry its own class id rather than assuming a single caster. */
  classId: number;
  /** Class-relative level this slot was gained at (1 for every starting slot) — NOT the character's absolute level, since Arcanista might have been picked up via multiclassing partway through. Callers with access to orderedClassIds translate this to the actual character_levels row to write the pick onto. */
  classLevel: number;
  /** Highest círculo this slot's spell can be — the max unlocked at classLevel, not the character's current max uniformly. */
  cap: number;
}

/**
 * One entry per known-spell slot the character has, each capped at the
 * highest círculo that was actually unlocked when that slot was gained —
 * not the character's current max círculo uniformly. The starting slots
 * (spell_count_growth's own add_after_first formula never touches these)
 * all cap at level 1's círculo; every level from 2 up replays
 * add_after_first's own floor((level-1)/per_levels)*value formula to find
 * exactly which levels actually add a new slot (Feiticeiro's per_levels=2
 * means only every other level ticks up), tagging each new slot with
 * calculateMaxSpellCircle at THAT level. Grouping the result by cap is
 * exactly "5 slots at 1º círculo, 4 at 2º, 1 at 3º" — one dropdown per
 * entry, options filtered to circle <= that entry's cap.
 */
export function calculateSpellSlotCircleCaps(classId: number, classLevel: number, startingSpellCount: number, growthValue: number, growthPerLevels: number): SpellSlot[] {
  const slots: SpellSlot[] = [];

  const level1Cap = calculateMaxSpellCircle(classId, 1);
  for (let i = 0; i < startingSpellCount; i++) {
    slots.push({ classId, classLevel: 1, cap: level1Cap });
  }

  let previousGrowth = 0;
  for (let level = 2; level <= classLevel; level++) {
    const growth = Math.floor((level - 1) / growthPerLevels) * growthValue;
    const newSlots = growth - previousGrowth;
    for (let i = 0; i < newSlots; i++) {
      slots.push({ classId, classLevel: level, cap: calculateMaxSpellCircle(classId, level) });
    }
    previousGrowth = growth;
  }

  return slots;
}
