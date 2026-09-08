import { Character, Effect, Power, Weapon } from '../../../../api.service';
import { isMirarActiveForWeapon } from './mirar';

// Mira Apurada (id 266, GeneralPowerSeeder.php) — "Quando usa a ação
// mirar," so it rides Mirar's own state entirely, not its own applies_when
// (Mira Apurada isn't itself "about" ranged weapons). Never a checkbox —
// excluded from currentlyActivePowerRows()'s generic pipeline in attack-
// modal.ts (its own mod_hit tag would otherwise let it through
// unconditionally), resolved here instead.
const miraApuradaPowerId = 266;

export function resolveMiraApuradaEffects(character: Character, weapon: Weapon, powers: Power[]): Effect[] {
  const hasMiraApurada = (character.active_effects ?? []).some((e) => e.power_id === miraApuradaPowerId);
  if (!hasMiraApurada || !isMirarActiveForWeapon(character, weapon, powers)) {
    return [];
  }
  return powers.find((p) => p.id === miraApuradaPowerId)?.effects ?? [];
}
