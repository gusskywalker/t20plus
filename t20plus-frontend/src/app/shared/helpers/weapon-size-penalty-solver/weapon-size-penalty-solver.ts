import { Character, Effect, Power } from '../../../api.service';
import { getActiveEffects } from '../get-active-effects/get-active-effects';

export type WeaponSizeStatus = 'none' | 'penalty' | 'blocked';

// Hardcoded per weapon-rules.md's "Weapon Sizes" section — NOT a formula.
// Weapon sizes compress two character sizes into one category at each end
// (Pequeno/Médio -> Normal, Grande/Enorme -> Aumentada), so the -5 penalty
// tier only ever appears for the "second half" of a compressed pair (Médio,
// Enorme) — every other character size jumps straight from no penalty to
// can't-wield-at-all one weapon-size category past natural. Worked example
// confirmed against weapon-rules.md: a Grande (+1) character can wield
// Reduzida/Normal/Aumentada with no penalty and simply cannot wield Gigante
// — there's no -5 tier for Grande at all.
const WEAPON_SIZE_STATUS_BY_CHARACTER_SIZE: Record<number, Record<number, WeaponSizeStatus>> = {
  [-2]: { [-1]: 'none', [0]: 'penalty', [1]: 'blocked', [2]: 'blocked' }, // Minúsculo
  [-1]: { [-1]: 'none', [0]: 'none', [1]: 'blocked', [2]: 'blocked' }, // Pequeno
  [0]: { [-1]: 'none', [0]: 'none', [1]: 'penalty', [2]: 'blocked' }, // Médio
  [1]: { [-1]: 'none', [0]: 'none', [1]: 'none', [2]: 'blocked' }, // Grande
  [2]: { [-1]: 'none', [0]: 'none', [1]: 'none', [2]: 'penalty' }, // Enorme
  [3]: { [-1]: 'none', [0]: 'none', [1]: 'none', [2]: 'none' }, // Colossal
};

export function weaponSizeStatus(characterSize: number, weaponSize: number): WeaponSizeStatus {
  return WEAPON_SIZE_STATUS_BY_CHARACTER_SIZE[characterSize]?.[weaponSize] ?? 'none';
}

const defaultWeaponSizePenalty = -5;

// Empunhadura Poderosa grants reduce_weapon_size_penalty (op set) —
// overrides the default -5 to whatever value it sets (-2). Checked via the
// shared getActiveEffects tag pool, not a hardcoded power_id, same
// convention as allow_dual_wield_full. Returns [] when the pair's status
// isn't 'penalty' (either 'none', or 'blocked' — equip-time gating handles
// that case, not a hit-roll effect).
export function resolveWeaponSizePenaltyEffects(characterSize: number, weaponSize: number, character: Character, powers: Power[]): Effect[] {
  if (weaponSizeStatus(characterSize, weaponSize) !== 'penalty') {
    return [];
  }
  const override = getActiveEffects(character, powers).find((e) => e.tag === 'reduce_weapon_size_penalty');
  return [{ tag: 'mod_hit', op: 'add', value: override ? Number(override.value) : defaultWeaponSizePenalty }];
}

// Breakdown-line label — "Empunhadura Poderosa" once the override actually
// applies (so the player sees which power is reducing it), "Tamanho da
// Arma" otherwise. Duplicates the override lookup rather than threading it
// out of resolveWeaponSizePenaltyEffects, same "duplicate over nest/share"
// convention as everywhere else in this codebase.
export function weaponSizePenaltyLabel(character: Character, powers: Power[]): string {
  const hasOverride = getActiveEffects(character, powers).some((e) => e.tag === 'reduce_weapon_size_penalty');
  return hasOverride ? 'Empunhadura Poderosa' : 'Tamanho da Arma';
}
