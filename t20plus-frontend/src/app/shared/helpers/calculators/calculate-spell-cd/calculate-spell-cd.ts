import { Character, Power } from '../../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';

/**
 * A spell's CD — 10 + half the character's total level (rounded down) +
 * the caster's bonus in whichever attribute governs that spell (see
 * resolve-spell-caster-info.ts for how that attribute is determined).
 * Kept as its own calculator (not inlined in the casting modal) since
 * spells-basics.md flags CD as "the core stat for any caster" — future
 * powers that bump CD directly will have somewhere real to plug into,
 * instead of every caller re-deriving the base formula by hand.
 */
export function calculateSpellCd(character: Character, keyAttribute: string, powers: Power[]): number {
  return 10 + Math.floor(character.level / 2) + calculateStatBonus(character, keyAttribute, powers);
}
