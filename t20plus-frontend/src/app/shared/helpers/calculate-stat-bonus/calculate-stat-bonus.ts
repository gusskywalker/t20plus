import { Character, Power } from '../../../api.service';
import { ActiveEffectsSource, getActiveEffects } from '../get-active-effects/get-active-effects';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * The base_* fields plus whatever ActiveEffectsSource itself needs —
 * narrowed the same way and for the same reason (see get-active-effects.ts):
 * so CharacterDraft can be passed in directly during character creation to
 * check a power's attribute prerequisite against the draft's current stats,
 * not just a real, already-created Character.
 */
export type StatBonusSource = ActiveEffectsSource & Pick<Character, 'base_str' | 'base_dex' | 'base_con' | 'base_int' | 'base_knw' | 'base_car'>;

/**
 * A character's effective value for one attribute ('str'/'dex'/'con'/'int'/
 * 'knw'/'car') — base_* (already has the race's fixed mod and the mod_other
 * point baked in, see character-payload.ts) plus any mod_str/mod_dex/etc.
 * from active powers (e.g. Aumentar Atributo). Every place that used to
 * read character.base_* directly for a live calculation (max PV, skill
 * bonus, Defesa's DEX term) should go through this instead, so a power's
 * attribute bump actually counts everywhere the attribute is used.
 */
export function calculateStatBonus(character: StatBonusSource, attribute: string, powers: Power[]): number {
  const baseValues: Record<string, number> = {
    str: character.base_str,
    dex: character.base_dex,
    con: character.base_con,
    int: character.base_int,
    knw: character.base_knw,
    car: character.base_car,
  };

  return (baseValues[attribute] ?? 0) + resolveTag(getActiveEffects(character, powers), `mod_${attribute}`);
}
