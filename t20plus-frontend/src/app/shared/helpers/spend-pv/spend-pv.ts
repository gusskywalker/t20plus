import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Deducts PV the standard way — PATCH current_pv (and temp_pv), then write
 * the new values straight into the cache — mirrors spendPm exactly, just
 * the PV field. Kept as its own file rather than a shared "spend a stat"
 * abstraction, same as calculate-max-pv/calculate-max-pm staying separate
 * despite near-identical shape. temp_pv is drained first, current_pv only
 * takes whatever the cost exceeds it by. No-ops for cost <= 0.
 */
export function spendPv(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, cost: number): void {
  if (cost <= 0) {
    return;
  }
  const spentFromTemp = Math.min(character.temp_pv, cost);
  const temp_pv = character.temp_pv - spentFromTemp;
  const current_pv = (character.current_pv ?? 0) - (cost - spentFromTemp);
  apiService.updateCharacter(character.id, { temp_pv, current_pv }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { temp_pv, current_pv });
  });
}
