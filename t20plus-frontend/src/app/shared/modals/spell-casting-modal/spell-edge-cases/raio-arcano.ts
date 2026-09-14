import { Character, Power } from '../../../../api.service';
import { resolveCasterMaxCircle } from '../../../helpers/resolve-spell-caster-info/resolve-spell-caster-info';

// Raio Arcano and its 5 elemental variants (ArcanaSpellSeeder.php) — one
// real spell per damage type rather than a single spell with a "pick your
// type" enhancement, so each variant's own spell.damage_type still works
// correctly with every existing type-gated bonus (Dracônica's PM discount/
// dmg-per-die/immunity all key off that field directly — an enhancement-
// driven type swap wouldn't be visible to any of them without yet another
// resolver just to reconcile it).
export const RAIO_ARCANO_SPELL_IDS = [21, 22, 23, 24, 25, 26];

// ClassArcanistaPowerSeeder.php — pure marker powers, no effects of their
// own; checked here by id instead.
const RAIO_PODEROSO_POWER_ID = 2081;
export const RAIO_DIVIDIDO_POWER_ID = 2102;

/**
 * Raio Arcano's own base damage — "1d8, +1d8 per círculo de magia acima do
 * 1º que você puder lançar" is exactly `casterMaxCircle` d8 (1 at circle 1,
 * 2 at circle 2, ...), not a fixed die notation like every other spell's
 * own base_spell_dmg effect, so it's computed here at cast time instead of
 * stored on the Spell row. Raio Poderoso steps the die from d8 to d12 (dice
 * count unchanged) — same "hardcode the exception, dedicated resolver"
 * convention as sono.ts/arma-de-jade.ts, just for a whole spell family
 * instead of one spell.
 */
export function raioArcanoDiceNotation(character: Character, powers: Power[]): string {
  const circle = Math.max(1, resolveCasterMaxCircle(character, powers));
  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const dieSize = grantedPowerIds.has(RAIO_PODEROSO_POWER_ID) ? 12 : 8;
  return `${circle}d${dieSize}`;
}

// Raio Arcano's own base is free to cast — no PM cost stated in its own
// text, unlike every other spell's circle-based BASE_PM_COST_BY_CIRCLE
// floor of 1. Hardcoded by spell id rather than a new generic Spell field,
// since this is the only spell that needs it so far.
export function raioArcanoMinPmCost(spellId: number): number {
  return RAIO_ARCANO_SPELL_IDS.includes(spellId) ? 0 : 1;
}
