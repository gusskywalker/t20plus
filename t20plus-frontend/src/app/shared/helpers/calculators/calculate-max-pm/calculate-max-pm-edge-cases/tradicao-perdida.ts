import { Character, CharacterClass, Effect, Power } from '../../../../../api.service';
import { PATAMAR_LEVELS } from '../../../scale-level-effect/scale-level-effect';
import { CASTER_CLASS_POWER_IDS, resolveCasterClassId } from './caster-class-powers';

const BASE_ATTRIBUTE_LIMIT = 6;
const ATTRIBUTE_LIMIT_PER_PATAMAR = 2;

export function isTradicaoPerdidaPower(power: Power | undefined): boolean {
  return (power?.effects ?? []).some((effect) => effect.tag === 'caster_pm_attribute');
}

export function buildTradicaoPerdidaCustomEffect(classId: number): Effect[] {
  return [{ tag: 'caster_pm_class', op: 'set', class_id: classId }];
}

export function resolveTradicaoPerdidaClassOptions(classes: CharacterClass[], powers: Power[], ownedClassIds: number[]): { id: number; name: string }[] {
  const casterClassIds = new Set(
    powers.filter((power) => CASTER_CLASS_POWER_IDS.includes(power.id)).map((power) => resolveCasterClassId(power)),
  );
  return classes.filter((characterClass) => casterClassIds.has(characterClass.id) && ownedClassIds.includes(characterClass.id)).map((characterClass) => ({ id: characterClass.id, name: characterClass.name }));
}

export const TRADICAO_PERDIDA_APRIMORADA_POWER_ID = 17133;

export function resolveTradicaoPerdidaLimit(character: Character): number {
  return BASE_ATTRIBUTE_LIMIT + ATTRIBUTE_LIMIT_PER_PATAMAR * PATAMAR_LEVELS.filter((level) => character.level >= level).length;
}

export function resolveTradicaoPerdidaAprimoradaKeyAttribute(character: Character, powers: Power[], classId: number): { attribute: string; limit: number } | undefined {
  const ownsAprimorada = (character.active_effects ?? []).some((row) => row.power_id === TRADICAO_PERDIDA_APRIMORADA_POWER_ID);
  const override = ownsAprimorada ? resolveTradicaoPerdidaOverrides(character, powers).get(classId) : undefined;
  return override ? { attribute: override.attribute, limit: resolveTradicaoPerdidaLimit(character) } : undefined;
}

export function resolveTradicaoPerdidaOverrides(character: Character, powers: Power[]): Map<number, { attribute: string; value: number }> {
  const limit = resolveTradicaoPerdidaLimit(character);
  const baseValues: Record<string, number> = {
    str: character.base_str,
    dex: character.base_dex,
    con: character.base_con,
    int: character.base_int,
    knw: character.base_knw,
    car: character.base_car,
  };
  const overrides = new Map<number, { attribute: string; value: number }>();
  for (const row of character.active_effects ?? []) {
    const attribute = powers.find((power) => power.id === row.power_id)?.effects?.find((effect) => effect.tag === 'caster_pm_attribute')?.value;
    const classId = (row.custom_effect ?? []).find((effect) => effect.tag === 'caster_pm_class')?.class_id;
    if (typeof attribute === 'string' && classId !== undefined) {
      overrides.set(classId, { attribute, value: Math.min(baseValues[attribute] ?? 0, limit) });
    }
  }
  return overrides;
}
