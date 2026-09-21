import { naturalWeaponSize } from '../natural-weapon-size/natural-weapon-size';

const MIN_CHARACTER_SIZE = -2;
const MAX_CHARACTER_SIZE = 3;

/**
 * What size an owned weapon is once the character's size has changed (see
 * resolveCurrentSize): the weapon is tied to a row of the character-size ->
 * weapon-size table (the row with its size that sits nearest the base
 * size), then moves that many rows with the character. So a Grande with an
 * Aumentada weapon growing to Enorme keeps it (same weapon size on both
 * rows), while a Pequeno with a Normal weapon growing two sizes ends up
 * with an Aumentada one. The stored weapon_size is never changed, this is
 * only what it currently counts as.
 */
export function effectiveWeaponSize(storedWeaponSize: number, baseSize: number, liveSize: number): number {
  if (liveSize === baseSize) {
    return storedWeaponSize;
  }
  const rows: number[] = [];
  for (let size = MIN_CHARACTER_SIZE; size <= MAX_CHARACTER_SIZE; size++) {
    if (naturalWeaponSize(size) === storedWeaponSize) {
      rows.push(size);
    }
  }
  if (rows.length === 0) {
    return storedWeaponSize;
  }
  const weaponRow = Math.min(Math.max(baseSize, Math.min(...rows)), Math.max(...rows));
  const movedRow = Math.min(MAX_CHARACTER_SIZE, Math.max(MIN_CHARACTER_SIZE, weaponRow + (liveSize - baseSize)));
  return naturalWeaponSize(movedRow);
}
