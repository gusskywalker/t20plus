import { ApiService, Character, Power } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';
import { calculateMaxPv } from '../calculators/calculate-max-pv/calculate-max-pv';

/**
 * Mirror of spend-pm.ts's own restorePm, for current_pv instead (e.g.
 * Regeneração Vegetal's restore_pv, op: 'add', value 5). No-op for
 * amount <= 0, same reasoning as spendPm's own cost <= 0 guard. Capped at
 * calculateMaxPv — restoring is never a way to exceed your own max, same as
 * every other current_pv write in the app.
 */
export function restorePv(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, amount: number, powers: Power[]): void {
  if (amount <= 0) {
    return;
  }
  const current_pv = Math.min((character.current_pv ?? 0) + amount, calculateMaxPv(character, powers));
  apiService.updateCharacter(character.id, { current_pv }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { current_pv });
  });
}
