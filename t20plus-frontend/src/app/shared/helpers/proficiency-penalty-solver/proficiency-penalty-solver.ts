import { Character, Effect, Weapon } from '../../../api.service';

// -5 mod_hit when wielding a weapon whose proficiency_id isn't among the
// character's granted powers — weapon.proficiency_id === null means a
// simple weapon everyone can use, no penalty ever; martial/exotic weapons
// (a real proficiency_id) always incur it unless that exact power_id is
// owned. See combat-interactions.md's Weapon Proficiency section. Not tied
// to any one specific power — checked against whichever proficiency power
// the weapon happens to require, so this isn't a
// shared/modals/attack-modal/attack-power-resolvers file (those are each
// about one specific named power's own bespoke mechanic).
export function resolveProficiencyPenaltyEffects(weapon: Weapon, character: Character): Effect[] {
  if (weapon.proficiency_id === null) {
    return [];
  }
  const hasProficiency = (character.active_effects ?? []).some((e) => e.power_id === weapon.proficiency_id);
  if (hasProficiency) {
    return [];
  }
  return [{ tag: 'mod_hit', op: 'add', value: -5 }];
}
