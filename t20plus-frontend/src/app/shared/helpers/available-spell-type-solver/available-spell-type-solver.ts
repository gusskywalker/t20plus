const ARCANISTA_CLASS_ID = 3;

// Hardcoded per class, same convention as calculate-max-spell-circle —
// which casting type(s) a class's own spell list is drawn from. Divina
// casters (Clérigo, Druida...) aren't seeded yet, so nothing maps to
// 'divina' here until one exists to confirm against.
const SPELL_TYPES_BY_CLASS: Record<number, string> = {
  [ARCANISTA_CLASS_ID]: 'arcana',
};

/** Every spell type a class can pick from — its own type (if the class is a known caster) plus 'universal', which every caster can always pick from regardless of class. */
export function availableSpellTypesForClass(classId: number): string[] {
  const ownType = SPELL_TYPES_BY_CLASS[classId];
  return ownType ? [ownType, 'universal'] : ['universal'];
}
