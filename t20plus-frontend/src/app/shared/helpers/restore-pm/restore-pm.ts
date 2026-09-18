import { ApiService, Character, Power } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';
import { calculateMaxPm } from '../calculators/calculate-max-pm/calculate-max-pm';

/**
 * Mirrors spendPm exactly, restoring current_pm instead (e.g. Sifão de
 * Mana's restore_pm, op: 'add', on a successful cast). Kept as its own
 * file rather than living inside spend-pm.ts, same as spend-pv/restore-pv
 * staying split. No-op for amount <= 0. Capped at calculateMaxPm — same
 * "restoring never exceeds max" rule as restorePv.
 */
export function restorePm(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, amount: number, powers: Power[]): void {
  if (amount <= 0) {
    return;
  }
  const current_pm = Math.min((character.current_pm ?? 0) + amount, calculateMaxPm(character, powers));
  apiService.updateCharacter(character.id, { current_pm }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { current_pm });
  });
}
