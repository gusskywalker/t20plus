import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Mirror of spend-pm.ts's own restorePm, for current_pv instead (e.g.
 * Regeneração Vegetal's restore_pv, op: 'add', value 5). No-op for
 * amount <= 0, same reasoning as spendPm's own cost <= 0 guard.
 */
export function restorePv(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, amount: number): void {
  if (amount <= 0) {
    return;
  }
  const current_pv = (character.current_pv ?? 0) + amount;
  apiService.updateCharacter(character.id, { current_pv }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { current_pv });
  });
}
