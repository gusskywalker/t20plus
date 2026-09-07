import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Deducts tibares the standard way — PATCH tibares, then write the new
 * value straight into the cache — mirrors spendPm/spendPv exactly. No-ops
 * for cost <= 0 so a free purchase/improvement never touches tibares.
 */
export function spendTibares(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, cost: number): void {
  if (cost <= 0) {
    return;
  }
  const tibares = character.tibares - cost;
  apiService.updateCharacter(character.id, { tibares }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { tibares });
  });
}
