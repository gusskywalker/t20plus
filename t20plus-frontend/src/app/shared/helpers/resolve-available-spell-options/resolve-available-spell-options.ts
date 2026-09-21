import { Power, Spell } from '../../../api.service';
import { SpellSlot } from '../calculators/calculate-spell-slot-circle-caps/calculate-spell-slot-circle-caps';

const ARCANISTA_CLASS_ID = 3;

// Hardcoded per class, same convention as calculate-max-spell-circle —
// which casting type(s) a class's own spell list is drawn from.
const SPELL_TYPES_BY_CLASS: Record<number, string> = {
  [ARCANISTA_CLASS_ID]: 'arcana',
};

// Every spell type a class can pick from — its own type (if the class is a
// known caster) plus 'universal', which every caster can always pick from
// regardless of class.
function availableSpellTypesForClass(classId: number): string[] {
  const ownType = SPELL_TYPES_BY_CLASS[classId];
  return ownType ? [ownType, 'universal'] : ['universal'];
}

/**
 * Every spell a caster can pick from at a given slot: the class's own
 * type(s) (via availableSpellTypesForClass above) capped at `cap`, PLUS any
 * type a granted power explicitly unlocks via `grant_spell_type` (e.g.
 * Linhagem Abençoada letting a Feiticeiro also pick 1st-circle divina
 * spells) — each granted type carries its OWN cap, independent of the
 * class's own circle cap. Shared by every spell-picking dropdown
 * (character-creation-spells-step, level-change-modal, add-spell-modal)
 * instead of each re-deriving this filter itself.
 */
export function resolveSlotSpellOptions(params: { spells: Spell[]; slot: SpellSlot | undefined; granted: Set<number>; powers: Power[] }): Spell[] {
  const { spells, slot, granted, powers } = params;
  // A restricted slot (e.g. Linhagem Feérica) ignores the class's own spell
  // list: any spell matching its schools/types up to the cap.
  if (slot && (slot.schools || slot.types)) {
    return spells.filter(
      (spell) =>
        spell.type !== 'specific' &&
        spell.circle <= slot.cap &&
        (!slot.schools || (spell.school !== null && slot.schools.includes(spell.school))) &&
        (!slot.types || slot.types.includes(spell.type)),
    );
  }
  return resolveAvailableSpellOptions({ spells, classId: slot?.classId ?? -1, cap: slot?.cap ?? 0, granted, powers });
}

export function resolveAvailableSpellOptions(params: { spells: Spell[]; classId: number; cap: number; granted: Set<number>; powers: Power[] }): Spell[] {
  const { spells, classId, cap, granted, powers } = params;
  const baseTypes = availableSpellTypesForClass(classId);

  // Two granted powers can unlock the SAME type at different caps at once
  // (e.g. Linhagem Abençoada's max_circle: 1 plus Herança Aprimorada's
  // max_circle: 3, both granted simultaneously once a Feiticeiro upgrades) —
  // take the highest cap per type, not just whichever grant happens to be
  // found first.
  const grantedTypeCaps = new Map<string, number>();
  powers
    .filter((power) => granted.has(power.id))
    .flatMap((power) => power.effects ?? [])
    .filter((effect) => effect.tag === 'grant_spell_type' && effect.op === 'grant' && effect.spell_type !== undefined && effect.max_circle !== undefined)
    .forEach((effect) => {
      const type = effect.spell_type as string;
      const maxCircle = effect.max_circle as number;
      grantedTypeCaps.set(type, Math.max(grantedTypeCaps.get(type) ?? 0, maxCircle));
    });

  return spells.filter((spell) => {
    if (baseTypes.includes(spell.type)) {
      return spell.circle <= cap;
    }
    const grantedCap = grantedTypeCaps.get(spell.type);
    return grantedCap !== undefined && spell.circle <= grantedCap;
  });
}
