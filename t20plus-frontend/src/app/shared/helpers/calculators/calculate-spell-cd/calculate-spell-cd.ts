import { Character, Power } from '../../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { resolveCasterMaxCircle } from '../../resolve-spell-caster-info/resolve-spell-caster-info';
import { matchesSpellAppliesWhen } from '../../matches-spell-applies-when/matches-spell-applies-when';

/**
 * A spell's CD — 10 + half the character's total level (rounded down) +
 * the caster's bonus in whichever attribute governs that spell (see
 * resolve-spell-caster-info.ts for how that attribute is determined) +
 * any granted passive power's mod_cd bonus scoped to this spell's own
 * school and/or resistance (e.g. Especialista em Escola, Familiar
 * (Borboleta)) or to it being known via more than one source at once
 * (O Próprio Sangue) — every applies_when filter the power actually
 * carries must match, same AND-across-present-fields rule applies_when
 * already follows for spell_enhancement powers. Kept as its own calculator (not
 * inlined in the casting modal) since spells-basics.md flags CD as "the
 * core stat for any caster."
 */
export function calculateSpellCd(character: Character, keyAttribute: string, powers: Power[], school: string | null, resistance: string | null, doubleKnown = false, grantedByPowerId?: number, extraSchools: string[] = []): number {
  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const casterMaxCircle = resolveCasterMaxCircle(character, powers);
  const modCdBonus = powers
    .filter((power) => {
      if (!grantedPowerIds.has(power.id) || power.usability !== 'passive') {
        return false;
      }
      return matchesSpellAppliesWhen(power.applies_when, { school, extraSchools, resistance, casterMaxCircle, doubleKnown, grantedByPowerId });
    })
    .flatMap((power) => power.effects ?? [])
    .filter((effect) => effect.tag === 'mod_cd' && effect.op === 'add')
    .reduce((sum, effect) => sum + Number(effect.value ?? 0), 0);
  return 10 + Math.floor(character.level / 2) + calculateStatBonus(character, keyAttribute, powers) + modCdBonus;
}
