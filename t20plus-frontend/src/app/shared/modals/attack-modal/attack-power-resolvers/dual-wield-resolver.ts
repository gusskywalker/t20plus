import { Character, Effect, Power } from '../../../../api.service';

// Ambidestria (id 77, ClassSharedPowerSeeder.php) and Estilo de Duas Armas
// (id 259, GeneralPowerSeeder.php) are twin powers with identical text — the
// -2 attack-both-weapons penalty is a single action, not something each
// power's own roll_active checkbox can self-report independently (checking
// both would wrongly stack to -4). Excluded from the generic
// attackPowerRows() checklist entirely (attack-modal.ts) in favor of the one
// bespoke checkbox this file resolves.
const ambidestriaPowerId = 77;
const estiloDeDuasArmasPowerId = 259;

// Only shown when the character owns exactly one of the two powers (owning
// both waives the penalty entirely — nothing to self-report) AND both
// hand_1/hand_2 hold a real weapon (not Desarmado, not a shield) — passed in
// already-resolved by the caller (attack-modal already has this logic for
// hand_2's two_hand hiding).
export function resolveDualWieldPower(character: Character, powers: Power[], hand1IsRealWeapon: boolean, hand2IsRealWeapon: boolean): Power | null {
  if (!hand1IsRealWeapon || !hand2IsRealWeapon) {
    return null;
  }
  const grantedIds = new Set((character.active_effects ?? []).map((e) => e.power_id));
  const hasAmbidestria = grantedIds.has(ambidestriaPowerId);
  const hasEstilo = grantedIds.has(estiloDeDuasArmasPowerId);
  if (hasAmbidestria === hasEstilo) {
    // Neither owned (nothing to show), or both owned (no penalty to
    // self-report) — same "hide it" outcome either way.
    return null;
  }
  return powers.find((p) => p.id === (hasAmbidestria ? ambidestriaPowerId : estiloDeDuasArmasPowerId)) ?? null;
}

// checked is seeded from the resolved power's own default_checked column at
// selectHand() — see attack-modal.ts's dualWieldChecked signal.
export function getDualWieldEffects(power: Power | null, checked: boolean): Effect[] {
  if (!power || !checked) {
    return [];
  }
  return [{ tag: 'mod_hit', op: 'add', value: -2 }];
}
