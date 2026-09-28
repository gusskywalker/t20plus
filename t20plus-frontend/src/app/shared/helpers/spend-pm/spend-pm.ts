import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Deducts PM the standard way — PATCH current_pm (and temp_pm), then write
 * the new values straight into the cache — same pattern confirmPm() already
 * uses for a manual edit. Shared because PM gets spent from multiple
 * screens: the attack modal (checked powers/golpes/Ataque Especial),
 * activating an 'active'/'roll_active' power, and future spells — each
 * computes its own cost, this just does the spend. temp_pm is drained
 * first, current_pm only takes whatever the cost exceeds it by. No-ops for
 * cost <= 0 so a free power never touches either value.
 */
export function spendPm(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, cost: number): void {
  if (cost <= 0) {
    return;
  }
  const spentFromTemp = Math.min(character.temp_pm, cost);
  const temp_pm = character.temp_pm - spentFromTemp;
  const current_pm = (character.current_pm ?? 0) - (cost - spentFromTemp);
  apiService.updateCharacter(character.id, { temp_pm, current_pm }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { temp_pm, current_pm });
  });
}
