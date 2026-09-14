import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Deducts PM the standard way — PATCH current_pm, then write the new value
 * straight into the cache — same pattern confirmPm() already uses for a
 * manual edit. Shared because PM gets spent from multiple screens: the
 * attack modal (checked powers/golpes/Ataque Especial), activating an
 * 'active'/'roll_active' power, and future spells — each computes its own
 * cost, this just does the spend. No-ops for cost <= 0 so a free power
 * never touches current_pm.
 */
export function spendPm(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, cost: number): void {
  if (cost <= 0) {
    return;
  }
  const current_pm = (character.current_pm ?? 0) - cost;
  apiService.updateCharacter(character.id, { current_pm }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { current_pm });
  });
}

/**
 * Mirror of spendPm above, for a power that restores current PM instead
 * (e.g. Sifão de Mana's restore_pm, op: 'add', on a successful cast). No-op
 * for amount <= 0, same reasoning as spendPm's own cost <= 0 guard.
 */
export function restorePm(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, amount: number): void {
  if (amount <= 0) {
    return;
  }
  const current_pm = (character.current_pm ?? 0) + amount;
  apiService.updateCharacter(character.id, { current_pm }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { current_pm });
  });
}
