import { Character, Effect, Power, Weapon } from '../../../api.service';
import { getActiveEffects } from '../get-active-effects/get-active-effects';

// -5 mod_hit when wielding a weapon whose proficiency_id isn't among the
// character's granted powers — weapon.proficiency_id === null means a
// simple weapon everyone can use, no penalty ever; martial/exotic weapons
// (a real proficiency_id) always incur it unless that exact power_id is
// owned, OR a waive_weapon_proficiency effect covers this exact weapon id
// (e.g. Arquearia Élfica: "todos os arcos são armas simples" — the
// character isn't proficient with Armas Marciais as a whole, just this
// specific weapon, so a real power_id grant would be too broad). See
// combat-interactions.md's Weapon Proficiency section. Not tied to any one
// specific power — checked against whichever proficiency power the weapon
// happens to require, so this isn't a shared/modals/attack-modal/attack-
// power-resolvers file (those are each about one specific named power's
// own bespoke mechanic). Shared with attack-power-resolvers/armas-da-
// ambicao.ts, the mirror-image bonus for actually being proficient.
export function isProficientWithWeapon(weapon: Weapon, character: Character, powers: Power[]): boolean {
  if (weapon.proficiency_id === null) {
    return true;
  }
  if ((character.active_effects ?? []).some((e) => e.power_id === weapon.proficiency_id)) {
    return true;
  }
  return getActiveEffects(character, powers).some((e) => e.tag === 'waive_weapon_proficiency' && (e.weapon_ids ?? []).includes(weapon.id));
}

export function resolveProficiencyPenaltyEffects(weapon: Weapon, character: Character, powers: Power[]): Effect[] {
  if (isProficientWithWeapon(weapon, character, powers)) {
    return [];
  }
  return [{ tag: 'mod_hit', op: 'add', value: -5 }];
}
