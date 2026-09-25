import { Character, Power } from '../../../../api.service';
import { attributeCode } from '../../attribute-code/attribute-code';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../../get-active-effects/get-active-effects';
import { resolveEffectSentinels } from '../../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveTag } from '../../tag-solver/tag-solver';
import { CASTER_CLASS_POWER_IDS, resolveCasterClassId } from './calculate-max-pm-edge-cases/caster-class-powers';
import { resolveTradicaoPerdidaOverrides } from './calculate-max-pm-edge-cases/tradicao-perdida';

/**
 * Max PM — same level 1 vs. every-level-after split as calculateMaxPv:
 * initial_pm for the first level, level_pm for every level after (a plain
 * sum across classes, see claude-stuff/rules/levels-and-experience.md), plus
 * every distinct caster key attribute added once each (flat, not per level —
 * read off each CASTER_CLASS_POWER_IDS power the character has, its own
 * spell_key_attribute, at calculateStatBonus's effective value; two classes
 * sharing an attribute count it once, the lower value), a class covered by
 * Tradição Perdida using its chosen attribute instead (see
 * calculate-max-pm-edge-cases/tradicao-perdida.ts), plus any mod_max_pm from
 * active powers (e.g. Vontade de Ferro, Ímpeto Juvenil).
 */
export function calculateMaxPm(character: Character, powers: Power[]): number {
  const levels = [...(character.levels ?? [])].sort((a, b) => a.level - b.level);

  const baseline = levels.reduce((total, level, index) => {
    const characterClass = level.character_class;
    if (!characterClass) {
      return total;
    }
    return total + (index === 0 ? characterClass.initial_pm : characterClass.level_pm);
  }, 0);

  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const tradicaoPerdidaOverrides = resolveTradicaoPerdidaOverrides(character, powers);
  const keyAttributeValues = new Map<string, number>();
  powers
    .filter((power) => CASTER_CLASS_POWER_IDS.includes(power.id) && grantedPowerIds.has(power.id))
    .forEach((power) => {
      const classId = resolveCasterClassId(power);
      const override = classId === undefined ? undefined : tradicaoPerdidaOverrides.get(classId);
      const rawAttribute = override?.attribute ?? power.effects?.find((effect) => effect.tag === 'spell_key_attribute')?.value;
      if (typeof rawAttribute !== 'string') {
        return;
      }
      const attribute = attributeCode(rawAttribute);
      const value = override?.value ?? calculateStatBonus(character, attribute);
      keyAttributeValues.set(attribute, Math.min(keyAttributeValues.get(attribute) ?? value, value));
    });
  const keyAttributeBonus = [...keyAttributeValues.values()].reduce((sum, value) => sum + value, 0);

  return baseline + keyAttributeBonus + resolveTag(resolveEffectSentinels(getActiveEffects(character), character, powers), 'mod_max_pm');
}
