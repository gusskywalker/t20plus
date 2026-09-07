import { Effect, Weapon } from '../../../../api.service';

/**
 * Which attribute (str/dex/etc — an attribute code, see tag-library.md) adds
 * to a weapon's damage roll, or null for none. Melee and thrown weapons
 * default to 'str' (drawing a thrown weapon still tests Força), fired
 * weapons default to null. A mod_dmg_attribute effect (op 'set') overrides
 * this default — e.g. a future power letting a fired weapon add 'dex'
 * instead. value: 'none' explicitly cancels any attribute bonus.
 */
export function calculateAttributeDmg(weapon: Weapon, effects: Effect[]): string | null {
  const override = [...effects].reverse().find((e) => e.tag === 'mod_dmg_attribute' && e.op === 'set');
  if (override) {
    return override.value === 'none' ? null : String(override.value);
  }
  return weapon.purpose === 'fired' ? null : 'str';
}
