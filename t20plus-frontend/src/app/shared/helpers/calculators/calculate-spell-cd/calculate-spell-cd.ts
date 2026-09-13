import { Character, Power } from '../../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';

/**
 * A spell's CD — 10 + half the character's total level (rounded down) +
 * the caster's bonus in whichever attribute governs that spell (see
 * resolve-spell-caster-info.ts for how that attribute is determined) +
 * any granted passive power's mod_cd bonus scoped to this spell's own
 * school (e.g. Especialista em Escola). Kept as its own calculator (not
 * inlined in the casting modal) since spells-basics.md flags CD as "the
 * core stat for any caster."
 */
export function calculateSpellCd(character: Character, keyAttribute: string, powers: Power[], school: string): number {
  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const schoolBonus = powers
    .filter((power) => grantedPowerIds.has(power.id) && power.usability === 'passive' && (power.applies_when?.spell_schools ?? []).includes(school))
    .flatMap((power) => power.effects ?? [])
    .filter((effect) => effect.tag === 'mod_cd' && effect.op === 'add')
    .reduce((sum, effect) => sum + Number(effect.value ?? 0), 0);
  return 10 + Math.floor(character.level / 2) + calculateStatBonus(character, keyAttribute, powers) + schoolBonus;
}
