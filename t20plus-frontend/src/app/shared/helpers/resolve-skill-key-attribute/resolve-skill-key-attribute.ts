import { Character, Power, Skill } from '../../../api.service';
import { getActiveEffects } from '../get-active-effects/get-active-effects';
import { resolveCasterKeyAttribute } from '../resolve-spell-caster-info/resolve-spell-caster-info';

/** The attribute currently governing a skill: its own key_attribute unless an active skill_attribute override applies (last matching one wins). */
export function resolveSkillKeyAttribute(character: Character, skill: Skill, powers: Power[]): string {
  let keyAttribute = skill.key_attribute;
  for (const effect of getActiveEffects(character, powers)) {
    if (effect.tag === 'skill_attribute' && effect.skill_id === skill.id && typeof effect.value === 'string') {
      keyAttribute = effect.value === 'key_attribute' ? resolveCasterKeyAttribute(character, powers) : effect.value;
    }
  }
  return keyAttribute;
}
