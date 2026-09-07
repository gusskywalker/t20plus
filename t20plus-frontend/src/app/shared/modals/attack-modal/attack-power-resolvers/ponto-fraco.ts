import { Character, Effect, Power } from '../../../../api.service';
import { findCheckedMarcaDaPresa, isInimigoChecked } from './marca-da-presa';

// Ponto Fraco (id 233, ClassCacadorPowerSeeder.php) — same bespoke shape as
// Espreitar: a passive power, never a checkbox, whose bonus only counts
// when the character has it AND a Marca da Presa tier is checked this roll,
// doubled if an Inimigo de (Criatura) is also checked.
const pontoFracoPowerId = 233;

// Returned as a synthetic mod_margin effect (widening = negative, see
// tag-library.md) so it flows through calculateMargin the normal way
// instead of needing its own bespoke margin calculation at each call site.
// Empty array when the bonus doesn't apply — same "nothing to add" shape as
// any other checked-effects pool.
export function resolvePontoFracoMarginEffects(character: Character, checkedPowerRows: { power: Power }[]): Effect[] {
  const hasPontoFraco = (character.active_effects ?? []).some((e) => e.power_id === pontoFracoPowerId);
  const marcaDaPresaRow = findCheckedMarcaDaPresa(checkedPowerRows);
  if (!hasPontoFraco || !marcaDaPresaRow) {
    return [];
  }
  return [{ tag: 'mod_margin', op: 'add', value: -2 * (isInimigoChecked(checkedPowerRows) ? 2 : 1) }];
}
