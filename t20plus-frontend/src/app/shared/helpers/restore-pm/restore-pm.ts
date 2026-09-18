import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Mirrors spendPm exactly, restoring current_pm instead (e.g. Sifão de
 * Mana's restore_pm, op: 'add', on a successful cast). Kept as its own
 * file rather than living inside spend-pm.ts, same as spend-pv/restore-pv
 * staying split. No-op for amount <= 0.
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
