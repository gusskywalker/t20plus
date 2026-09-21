import { ApiService } from '../../../api.service';
import { UseCharacter } from '../../hooks/use-character';

/**
 * Sequentially grants each id in remainingIds via addCharacterActiveEffect —
 * one at a time (not parallel) so each call's own active_effects snapshot
 * already includes whatever was added just before it, instead of racing
 * and dropping one from the cache. Shared by every place that grants a root
 * power and needs its own `tag: 'power', op: 'grant'` children to follow
 * (level-change-modal's own level-up pick, character-main's Adicionar
 * Poder) — was duplicated per-caller before, now lives in one place.
 */
export function grantChildPowers(
  apiService: ApiService,
  useCharacter: UseCharacter,
  characterId: number,
  cacheId: string,
  remainingIds: number[],
  onDone: () => void,
): void {
  const [nextId, ...rest] = remainingIds;
  if (nextId === undefined) {
    onDone();
    return;
  }
  apiService.addCharacterActiveEffect(characterId, nextId).subscribe((character) => {
    useCharacter.patchCharacterCache(cacheId, {
      active_effects: character.active_effects,
      golpes_pessoais: character.golpes_pessoais,
      hands: character.hands,
      base_str: character.base_str,
      base_dex: character.base_dex,
      base_con: character.base_con,
      base_int: character.base_int,
      base_knw: character.base_knw,
      base_car: character.base_car,
    });
    grantChildPowers(apiService, useCharacter, characterId, cacheId, rest, onDone);
  });
}
