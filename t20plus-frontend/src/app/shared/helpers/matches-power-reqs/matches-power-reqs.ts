import { Power, Weapon } from '../../../api.service';

// Null applies_when = always relevant. Extracted from attack-modal.ts so
// it's not a private method duplicated elsewhere — this is the one place
// the weapon_grip/weapon_purpose/weapon_ability/weapon_any matching rules
// live. grantedPowerIds is only needed for applies_when.power_id (e.g.
// Arte da Guerra's hidden "+2 dano" child, which only counts once the
// character ALSO separately has real Proficiência - Armas Marciais) —
// absent power_id or absent grantedPowerIds is treated as "doesn't match"
// rather than silently ignored, so a caller that forgets to pass it never
// wrongly shows a gated bonus.
export function matchesPowerReqs(power: Power, weapon: Weapon, grantedPowerIds?: Set<number>): boolean {
  const reqs = power.applies_when;
  if (!reqs) {
    return true;
  }
  if (reqs.power_id !== undefined && !(grantedPowerIds?.has(reqs.power_id) ?? false)) {
    return false;
  }
  if (reqs.weapon_any) {
    return reqs.weapon_any.some((option) =>
      matchesWeaponCondition(weapon, option.grip, option.purpose ? [option.purpose] : undefined, option.ability, option.weapon_id !== undefined ? [option.weapon_id] : undefined),
    );
  }
  return matchesWeaponCondition(weapon, reqs.weapon_grip, reqs.weapon_purpose, reqs.weapon_ability, reqs.weapon_ids);
}

function matchesWeaponCondition(weapon: Weapon, grip?: string, purpose?: string[], ability?: number, ids?: number[]): boolean {
  if (grip && weapon.grip !== grip) {
    return false;
  }
  if (purpose && !purpose.includes(weapon.purpose)) {
    return false;
  }
  if (ability !== undefined && !(weapon.ability_ids ?? []).includes(ability)) {
    return false;
  }
  if (ids && !ids.includes(weapon.id)) {
    return false;
  }
  return true;
}
