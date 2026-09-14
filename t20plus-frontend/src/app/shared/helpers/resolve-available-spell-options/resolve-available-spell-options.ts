import { Power, Spell } from '../../../api.service';

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
export function resolveAvailableSpellOptions(params: { spells: Spell[]; classId: number; cap: number; granted: Set<number>; powers: Power[] }): Spell[] {
  const { spells, classId, cap, granted, powers } = params;
  const baseTypes = availableSpellTypesForClass(classId);
  const grantedTypeCaps = powers
    .filter((power) => granted.has(power.id))
    .flatMap((power) => power.effects ?? [])
    .filter((effect) => effect.tag === 'grant_spell_type' && effect.op === 'grant' && effect.spell_type !== undefined && effect.max_circle !== undefined)
    .map((effect) => ({ type: effect.spell_type as string, maxCircle: effect.max_circle as number }));

  return spells.filter((spell) => {
    if (baseTypes.includes(spell.type)) {
      return spell.circle <= cap;
    }
    const grantedCap = grantedTypeCaps.find((entry) => entry.type === spell.type);
    return grantedCap !== undefined && spell.circle <= grantedCap.maxCircle;
  });
}
