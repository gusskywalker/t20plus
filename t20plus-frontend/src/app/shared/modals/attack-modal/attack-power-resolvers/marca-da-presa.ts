import { Character, Power } from '../../../../api.service';

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
// callers (the 'marca_da_presa_die' sentinel, Espreitar, Ponto Fraco) all
// agree on the same row instead of each re-finding it themselves.
export function findCheckedMarcaDaPresa(checkedPowerRows: { power: Power }[]): { power: Power } | undefined {
  return checkedPowerRows.find((row) => marcaDaPresaPowerIds.includes(row.power.id));
}

// The checked tier's own extra_die notation (e.g. '1d8') — what the
// 'marca_da_presa_die' sentinel resolves to (see markPassed()). '0' when no
// tier is checked, same as rolling nothing.
export function marcaDaPresaDiceNotation(checkedPowerRows: { power: Power }[]): string {
  const row = findCheckedMarcaDaPresa(checkedPowerRows);
  if (!row) {
    return '0';
  }
  return String((row.power.effects ?? []).find((e) => e.tag === 'mod_dmg' && e.op === 'extra_die')?.value ?? '0');
}

// Inimigo de (Criatura) — one power per creature-type option (ids 219-224,
// ClassCacadorPowerSeeder.php). Doubles Marca da Presa's own bonuses
// (Espreitar, Ponto Fraco) and its dice (marca_da_presa_die sentinel,
// resolved generically via calculate-weapon-dice.ts's extra_die handling —
// checking two of these tags just rolls that tier's die twice, not
// special-cased here).
const inimigoDeCriaturaPowerIds = [219, 220, 221, 222, 223, 224];

export function isInimigoChecked(checkedPowerRows: { power: Power }[]): boolean {
  return checkedPowerRows.some((row) => inimigoDeCriaturaPowerIds.includes(row.power.id));
}
