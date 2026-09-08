import { Character, Effect, Power, Weapon } from '../../../../api.service';
import { isMirarActiveForWeapon } from './mirar';

// Tiro de Abate (id 254, ClassCacadorPowerSeeder.php) — "Quando usa a ação
// mirar," rides Mirar's own state, same shape as Mira Apurada. Also flags
// Marca da Presa's die for crit-multiplication (markPassed()'s dedicated
// marca_da_presa_dice handling) — see isTiroDeAbateActive.
const tiroDeAbatePowerId = 254;

export function isTiroDeAbateActive(character: Character, weapon: Weapon, powers: Power[]): boolean {
  const hasTiroDeAbate = (character.active_effects ?? []).some((e) => e.power_id === tiroDeAbatePowerId);
  return hasTiroDeAbate && isMirarActiveForWeapon(character, weapon, powers);
}

export function resolveTiroDeAbateEffects(character: Character, weapon: Weapon, powers: Power[]): Effect[] {
  if (!isTiroDeAbateActive(character, weapon, powers)) {
    return [];
  }
  return powers.find((p) => p.id === tiroDeAbatePowerId)?.effects ?? [];
}
