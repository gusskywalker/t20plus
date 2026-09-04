import { Character, Power } from '../../../api.service';
import { calculateStatBonus } from '../calculate-stat-bonus/calculate-stat-bonus';
import { getActiveEffects } from '../get-active-effects/get-active-effects';
import { resolveEffectSentinels } from '../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * Max PV — CON is added at EVERY level, level 1 included. Level 1 (the
 * character's very first level, whichever class) uses that class's
 * initial_pv + CON; every level after that uses THAT level's class's
 * level_pv + CON, even a new class's own first level in a multiclass
 * build (T20 rule: "Quando você ganha o primeiro nível em uma nova
 * classe, ganha os PV de um nível subsequente, não do primeiro" — see
 * claude-stuff/rules/levels-and-experience.md). CON here is
 * calculateStatBonus's effective value (base_con plus any live mod_con
 * from active powers, e.g. Aumento de Atributo) — T20 attributes are the
 * modifier itself, no score-to-modifier conversion. Plus any mod_max_pv
 * from active powers (e.g. Vitalidade, Abatido).
 */
export function calculateMaxPv(character: Character, powers: Power[]): number {
  const levels = [...(character.levels ?? [])].sort((a, b) => a.level - b.level);
  const con = calculateStatBonus(character, 'con', powers);

  const baseline = levels.reduce((total, level, index) => {
    const characterClass = level.character_class;
    if (!characterClass) {
      return total;
    }
    const pv = index === 0 ? characterClass.initial_pv : characterClass.level_pv;
    return total + pv + con;
  }, 0);

  return baseline + resolveTag(resolveEffectSentinels(getActiveEffects(character, powers), character, powers), 'mod_max_pv');
}
