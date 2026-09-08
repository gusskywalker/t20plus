import { Character, Power } from '../../../../api.service';
import { stepDieNotation } from '../../../helpers/step-extra-die/step-extra-die';

// Marca da Presa's 5 tiers (ClassCacadorPowerSeeder.php ids 196-200) — a
// leveled-up Caçador holds every tier they've ever unlocked as a separate
// active_effects row at once (class_granted, same shape as Ataque
// Especial), but only one can ever be is_active at a time (see
// CharacterActiveEffectController::update()'s own exclusivity check).
// Hardcoded id list on purpose — that's the actual shape of this power in
// the data, not something derivable from a shared tag.
const marcaDaPresaPowerIds = [196, 197, 198, 199, 200];

// Whether any tier is currently Ativado — used to gate Mestre Caçador's own
// visibility in the checklist (see attack-modal.ts's attackPowerRows): its
// margin-widen only makes sense "quando usa a habilidade," so it shouldn't
// even be offered as a checkbox otherwise.
export function isMarcaDaPresaActive(character: Character): boolean {
  return (character.active_effects ?? []).some((e) => marcaDaPresaPowerIds.includes(e.power_id) && e.is_active);
}

// Whichever tier is currently checked, if any — single source of truth so
// callers (Espreitar, Ponto Fraco, attack-modal.ts's own marca_da_presa_dice
// resolution) all agree on the same row instead of each re-finding it.
export function findCheckedMarcaDaPresa(checkedPowerRows: { power: Power }[]): { power: Power } | undefined {
  return checkedPowerRows.find((row) => marcaDaPresaPowerIds.includes(row.power.id));
}

// The checked tier's own die notation (e.g. '1d8') — tag mod_dmg, op
// marca_da_presa_dice (a dedicated op, not the generic extra_die bucket —
// this die doubles with Inimigo and crit-multiplies with Tiro de Abate,
// neither of which apply to ordinary extra_die entries). '0' when no tier
// is checked, same as rolling nothing.
export function marcaDaPresaDiceNotation(checkedPowerRows: { power: Power }[]): string {
  const row = findCheckedMarcaDaPresa(checkedPowerRows);
  if (!row) {
    return '0';
  }
  return String((row.power.effects ?? []).find((e) => e.tag === 'mod_dmg' && e.op === 'marca_da_presa_dice')?.value ?? '0');
}

// Inimigo de (Criatura) — one power per creature-type option (ids 219-224,
// ClassCacadorPowerSeeder.php), each granting doubles_marca_da_presa_dice.
// Also doubles Espreitar/Ponto Fraco's own bonuses (see those files).
export function isInimigoChecked(checkedPowerRows: { power: Power }[]): boolean {
  return checkedPowerRows.some((row) => (row.power.effects ?? []).some((e) => e.tag === 'doubles_marca_da_presa_dice'));
}

function doubleDieCount(notation: string): string {
  const match = notation.match(/^(\d+)d(\d+)$/);
  if (!match) {
    return notation;
  }
  return `${Number(match[1]) * 2}d${match[2]}`;
}

// The checked tier's die, stepped by all_die_step_increase then doubled by
// Inimigo — in that order, since stepDieNotation looks up the raw
// single-count notation on its own ladder (doubling first would make e.g.
// '2d8' unfindable there). What markPassed() actually rolls.
export function marcaDaPresaFinalDiceNotation(checkedPowerRows: { power: Power }[], allDieStepIncrease: number): string {
  const stepped = stepDieNotation(marcaDaPresaDiceNotation(checkedPowerRows), allDieStepIncrease);
  return isInimigoChecked(checkedPowerRows) ? doubleDieCount(stepped) : stepped;
}
