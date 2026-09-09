// Bespoke per-ammo-id weapon compatibility — a fired weapon can only draw
// from ammo built for its own kind (bows take flechas, bestas take virotes,
// firearms take munição), never any generic "type: ammo" row. Hardcoded per
// ammo id since a weapon-side field (purpose/is_firearm) can't disambiguate
// e.g. bows from bestas, both of which are purpose: 'fired', is_firearm: false.
const AMMO_COMPATIBLE_WEAPON_IDS: Record<number, number[]> = {
  2: [6], // Flechas (20) — Arco Curto
  3: [7, 8], // Munição (20) — Pistola-Tambor, Pistola
};

export function isAmmoCompatibleWithWeapon(ammoGeneralItemId: number, weaponId: number): boolean {
  return (AMMO_COMPATIBLE_WEAPON_IDS[ammoGeneralItemId] ?? []).includes(weaponId);
}
