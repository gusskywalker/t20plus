import { Character, Power, Spell } from '../../../../api.service';
import { calculateStatBonus } from '../../../helpers/calculators/calculate-stat-bonus/calculate-stat-bonus';

// Herança Aprimorada (Abençoada) — "Suas magias divinas de círculo igual ou
// menor que sua Sabedoria custam –1 PM." Comparing a spell's own circle
// against a live ATTRIBUTE SCORE (not a fixed spell property, not a
// caster's max circle) doesn't fit any existing applies_when dimension, so
// this gets its own dedicated resolver instead of a new generic field, same
// "hardcode the exception, call a dedicated resolver" convention as
// sono.ts/arma-de-jade.ts — just keyed by power id instead of spell id.
export const HERANCA_APRIMORADA_ABENCOADA_POWER_ID = 2051;

export function herancaAprimoradaAbencoadaPmDiscount(spell: Spell, character: Character, powers: Power[]): number {
  if (spell.type !== 'divina') {
    return 0;
  }
  const sabedoria = calculateStatBonus(character, 'knw', powers);
  return spell.circle <= sabedoria ? -1 : 0;
}
