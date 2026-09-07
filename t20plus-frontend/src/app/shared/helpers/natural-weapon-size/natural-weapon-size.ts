// Character size -> weapon size (Weapon Sizes, claude-stuff/rules/weapon-
// rules.md) — the two scales are different lengths (character: Minúsculo
// -2 .. Colossal +3, weapon: Reduzida -1 .. Gigante +2), so this is a real
// lookup table, not a formula: Pequeno/Médio both grip Normal, Grande/
// Enorme both grip Aumentada.
const NATURAL_WEAPON_SIZE_BY_CHARACTER_SIZE: Record<number, number> = {
  [-2]: -1, // Minúsculo -> Reduzida
  [-1]: 0, // Pequeno -> Normal
  [0]: 0, // Médio -> Normal
  [1]: 1, // Grande -> Aumentada
  [2]: 1, // Enorme -> Aumentada
  [3]: 2, // Colossal -> Gigante
};

/** The weapon size a character of the given size naturally wields, no penalty. */
export function naturalWeaponSize(characterSize: number): number {
  return NATURAL_WEAPON_SIZE_BY_CHARACTER_SIZE[characterSize] ?? 0;
}
