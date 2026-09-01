import { Character } from '../../../api.service';

/**
 * Max PM — same level 1 vs. every-level-after split as calculateMaxPv,
 * but PM never adds an attribute modifier (T20 rule: "Pontos de Mana:
 * Some os PM fornecidos por cada classe" — a plain sum, see
 * claude-stuff/rules/levels-and-experience.md).
 */
export function calculateMaxPm(character: Character): number {
  const levels = [...(character.levels ?? [])].sort((a, b) => a.level - b.level);

  return levels.reduce((total, level, index) => {
    const characterClass = level.character_class;
    if (!characterClass) {
      return total;
    }
    return total + (index === 0 ? characterClass.initial_pm : characterClass.level_pm);
  }, 0);
}
