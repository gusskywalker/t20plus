// Bespoke per-ammo-id weapon compatibility — a fired weapon can only draw
// from ammo built for its own kind (bows take flechas, bestas take virotes,
// firearms take munição), never any generic "type: ammo" row. Hardcoded per
// ammo id since a weapon-side field (purpose/is_firearm) can't disambiguate
// e.g. bows from bestas, both of which are purpose: 'fired', is_firearm: false.
const AMMO_COMPATIBLE_WEAPON_IDS: Record<number, number[]> = {
  2: [5, 6, 78, 79, 127], // Flechas (20) — Arco de Guerra, Arco Curto, Arco Longo, Arco Montado, Arco Élfico
  3: [7, 8, 92, 132, 134, 135, 136, 137, 141, 142], // Munição (20) — Pistola-Tambor, Pistola, Espada-Calibre (Pistola), Garrucha, Pistola-Punhal (Pistola), Traque, Arcabuz, Bacamarte, Lança de Fogo (Fogo), Mosquete
  66: [36, 37, 80, 81, 130, 131], // Virotes (20) — Besta de Mão, Besta Leve, Besta Dupla, Besta Pesada, Balestra, Besta de Repetição
  67: [38], // Dardos (10) — Zarabatana
  68: [11], // Pedras (20) — Funda
  69: [138], // Bomba (Munição) — Bazuca
  70: [139], // Bola de Ferro (Munição) — Canhão Portátil
  71: [5, 6, 78, 79, 127], // Flechas Assobiadoras (20) — Arco de Guerra, Arco Curto, Arco Longo, Arco Montado, Arco Élfico
  72: [5, 6, 78, 79, 127], // Flechas de Caça (20) — Arco de Guerra, Arco Curto, Arco Longo, Arco Montado, Arco Élfico
  73: [5, 6, 78, 79, 127], // Flechas Pesadas (20) — Arco de Guerra, Arco Curto, Arco Longo, Arco Montado, Arco Élfico
  74: [36, 37, 80, 81, 130, 131], // Virotes Pesados (20) — Besta de Mão, Besta Leve, Besta Dupla, Besta Pesada, Balestra, Besta de Repetição
};

export function isAmmoCompatibleWithWeapon(ammoGeneralItemId: number, weaponId: number): boolean {
  return (AMMO_COMPATIBLE_WEAPON_IDS[ammoGeneralItemId] ?? []).includes(weaponId);
}
