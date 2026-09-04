import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Deducts PV the standard way — PATCH current_pv, then write the new value
 * straight into the cache — mirrors spendPm exactly, just the PV field.
 * Kept as its own file rather than a shared "spend a stat" abstraction,
 * same as calculate-max-pv/calculate-max-pm staying separate despite
 * near-identical shape. No-ops for cost <= 0.
 */
export function spendPv(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, cost: number): void {
  if (cost <= 0) {
    return;
  }
  const current_pv = (character.current_pv ?? 0) - cost;
  apiService.updateCharacter(character.id, { current_pv }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { current_pv });
  });
}
