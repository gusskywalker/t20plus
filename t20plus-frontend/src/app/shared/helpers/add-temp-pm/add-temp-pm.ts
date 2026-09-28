import { ApiService, Character } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Adds to temp_pm the standard way — PATCH temp_pm, then write the new
 * value straight into the cache — mirrors restorePm, but temp_pm has no
 * max cap. No-ops for amount <= 0.
 */
export function addTempPm(apiService: ApiService, useCharacter: UseCharacter, id: string, character: Character, amount: number): void {
  if (amount <= 0) {
    return;
  }
  const temp_pm = character.temp_pm + amount;
  apiService.updateCharacter(character.id, { temp_pm }).subscribe(() => {
    useCharacter.patchCharacterCache(id, { temp_pm });
  });
}
