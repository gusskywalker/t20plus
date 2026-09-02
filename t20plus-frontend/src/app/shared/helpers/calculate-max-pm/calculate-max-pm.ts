import { Character, Power } from '../../../api.service';
import { getActiveEffects } from '../get-active-effects/get-active-effects';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * Max PM — same level 1 vs. every-level-after split as calculateMaxPv,
 * but PM never adds an attribute modifier (T20 rule: "Pontos de Mana:
 * Some os PM fornecidos por cada classe" — a plain sum, see
 * claude-stuff/rules/levels-and-experience.md), plus any mod_max_pm from
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

  return baseline + resolveTag(getActiveEffects(character, powers), 'mod_max_pm');
}
