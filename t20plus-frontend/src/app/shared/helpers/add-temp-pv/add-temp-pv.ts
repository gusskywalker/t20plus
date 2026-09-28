import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Adds to temp_pv the standard way — PATCH temp_pv, then write the new
 * value straight into the cache — mirrors restorePv, but temp_pv has no
 * max cap. No-ops for amount <= 0.
 */
export function addTempPv(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, amount: number): void {
  if (amount <= 0) {
    return;
  }
  const temp_pv = character.temp_pv + amount;
  apiService.updateCharacter(character.id, { temp_pv }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { temp_pv });
  });
}
