import { Character } from '../../../api.service';

/**
 * Max PV — CON is added at EVERY level, level 1 included. Level 1 (the
 * character's very first level, whichever class) uses that class's
 * initial_pv + CON; every level after that uses THAT level's class's
 * level_pv + CON, even a new class's own first level in a multiclass
 * build (T20 rule: "Quando você ganha o primeiro nível em uma nova
 * classe, ganha os PV de um nível subsequente, não do primeiro" — see
 * claude-stuff/rules/levels-and-experience.md). CON here is
 * character.base_con directly — T20 attributes are the modifier itself,
 * no score-to-modifier conversion, and base_con already has the race's
 * mod baked in (see character-payload.ts).
 */
export function calculateMaxPv(character: Character): number {
  const levels = [...(character.levels ?? [])].sort((a, b) => a.level - b.level);

  return levels.reduce((total, level, index) => {
    const characterClass = level.character_class;
    if (!characterClass) {
      return total;
    }
    const pv = index === 0 ? characterClass.initial_pv : characterClass.level_pv;
    return total + pv + character.base_con;
  }, 0);
}
