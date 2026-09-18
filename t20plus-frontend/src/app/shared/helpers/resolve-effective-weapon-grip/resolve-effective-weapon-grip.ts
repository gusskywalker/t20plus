import { Character, Power, Weapon } from '../../../api.service';

/**
 * Arsenal do Oceano-style "receive proficiency again -> now a leve weapon"
 * upgrade. weapon.grip itself is never mutated — resolved fresh everywhere
 * grip matters, from whichever granted power carries a tag: 'mod_weapon_grip'
 * effect covering this weapon's id, gated by that power's own
 * applies_when.power_id (the character must ALSO separately have that other
 * power granted, e.g. the real "Proficiência - Arpão", not just Arsenal do
 * Oceano's own waiver). See RaceGrantedPowerSeeder.php's hidden
 * "Arsenal do Oceano (...)" children and hiddenFromPowersListIds in
 * character-main.ts, which keeps them off the sheet entirely.
 */
export function resolveEffectiveWeaponGrip(character: Character, weapon: Weapon, powers: Power[]): string {
  const grantedPowerIds = new Set((character.active_effects ?? []).map((e) => e.power_id));

  for (const activeEffect of character.active_effects ?? []) {
    if (!activeEffect.is_active) {
      continue;
    }
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (!power) {
      continue;
    }
    if (power.applies_when?.power_id !== undefined && !grantedPowerIds.has(power.applies_when.power_id)) {
      continue;
    }
    const override = (power.effects ?? []).find(
      (effect) => effect.tag === 'mod_weapon_grip' && effect.op === 'set' && (effect.weapon_ids ?? []).includes(weapon.id),
    );
    if (override) {
      return String(override.value);
    }
  }

  return weapon.grip;
}
