import { Character, Power } from '../../../../api.service';
import { findCheckedMarcaDaPresa, isInimigoChecked } from './marca-da-presa';

// Espreitar (Combate) — power_granted child of Espreitar (id 225, see
// ClassCacadorPowerSeeder.php). Never appears as a checkbox (passive powers
// are excluded from attackPowerRows entirely) — auto-applied whenever the
// character has it AND a Marca da Presa tier is checked this roll, doubled
// if an Inimigo de (Criatura) is also checked.
const espreitarCombatePowerId = 226;

// 0 when the character doesn't have it or no Marca da Presa tier is
// checked — same "no line shown" treatment as any other zero bonus.
export function resolveEspreitarBonus(character: Character, checkedPowerRows: { power: Power }[]): number {
  const hasEspreitarCombate = (character.active_effects ?? []).some((e) => e.power_id === espreitarCombatePowerId);
  const marcaDaPresaRow = findCheckedMarcaDaPresa(checkedPowerRows);
  if (!hasEspreitarCombate || !marcaDaPresaRow) {
    return 0;
  }
  return (marcaDaPresaRow.power.pm_cost ?? 0) * (isInimigoChecked(checkedPowerRows) ? 2 : 1);
}
