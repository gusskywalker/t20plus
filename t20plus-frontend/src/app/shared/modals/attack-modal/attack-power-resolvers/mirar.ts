import { Character, Power, Weapon } from '../../../../api.service';
import { matchesPowerReqs } from '../../../helpers/matches-power-reqs/matches-power-reqs';

// Mirar (id 253, GeneralActionPowerSeeder.php). Hardcoded id on purpose —
// same convention as marca-da-presa.ts's marcaDaPresaPowerIds.
const mirarPowerId = 253;

// Whether Mirar is currently in effect for the weapon in play — is_active
// (toggled from the sheet) AND matchesPowerReqs(mirar, weapon) (its
// applies_when, fired/thrown only). Shared by every Mirar-adjacent power
// (Mira Apurada today, more later) instead of each reimplementing this
// check — same role isMarcaDaPresaActive plays for Espreitar/Ponto Fraco.
export function isMirarActiveForWeapon(character: Character, weapon: Weapon, powers: Power[]): boolean {
  const isActive = (character.active_effects ?? []).some((e) => e.power_id === mirarPowerId && e.is_active);
  if (!isActive) {
    return false;
  }
  const mirarPower = powers.find((p) => p.id === mirarPowerId);
  return mirarPower ? matchesPowerReqs(mirarPower, weapon) : false;
}
