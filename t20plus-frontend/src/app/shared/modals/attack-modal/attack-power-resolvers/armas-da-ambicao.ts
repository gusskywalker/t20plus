import { Character, Effect, Power, Weapon } from '../../../../api.service';
import { isProficientWithWeapon } from '../../../helpers/proficiency-penalty-solver/proficiency-penalty-solver';

// Armas da Ambição (id 277, DivineGrantedPowerSeeder.php) — "com armas nas
// quais é proficiente," the mirror image of the universal proficiency
// penalty (proficiency-penalty-solver.ts): a bonus instead, and only while
// owning this one power, so it's resolved here instead of through the
// generic checkedEffects pipeline.
const armasDaAmbicaoPowerId = 277;

export function resolveArmasDaAmbicaoEffects(character: Character, weapon: Weapon, powers: Power[]): Effect[] {
  const hasArmasDaAmbicao = (character.active_effects ?? []).some((e) => e.power_id === armasDaAmbicaoPowerId);
  if (!hasArmasDaAmbicao || !isProficientWithWeapon(weapon, character)) {
    return [];
  }
  return powers.find((p) => p.id === armasDaAmbicaoPowerId)?.effects ?? [];
}
