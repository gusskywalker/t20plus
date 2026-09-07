import { Character, Effect, Power } from '../../../../api.service';
import { resolveTag } from '../../../helpers/tag-solver/tag-solver';

// Ataque Especial (power ids 1-5, ClassGuerreiroPowerSeeder.php) — every
// tier the character has ever unlocked stays granted (class_granted, one
// row per tier reached), so a high-level Guerreiro can have several of
// these ids as separate active_effects at once. Hardcoded id list on
// purpose — that's the actual shape of this power in the data, not
// something derivable from a shared tag (other powers could use
// mod_hit_or_dmg too, that's not what identifies Ataque Especial itself).
const ataqueEspecialPowerIds = [1, 2, 3, 4, 5];

export type AtaqueEspecialMode = 'hit' | 'dmg' | 'split';

// Only the tiers this character actually has granted — highest bonus first,
// which the component uses as its default pick. id/name match
// SearchableDropdown's expected item shape.
export function getAtaqueEspecialOptions(character: Character, powers: Power[]): { id: number; name: string; bonus: number }[] {
  const grantedIds = new Set((character.active_effects ?? []).map((e) => e.power_id));
  return ataqueEspecialPowerIds
    .filter((id) => grantedIds.has(id))
    .map((id) => {
      const power = powers.find((p) => p.id === id);
      return { id, name: `[${power?.pm_cost ?? 0}PM] ${power?.name ?? ''}`, bonus: resolveTag(power?.effects ?? [], 'mod_hit_or_dmg') };
    })
    .sort((a, b) => b.bonus - a.bonus);
}

// 0 when nothing is selected (id null) — same "no bonus" shape as any
// unchecked power.
export function getAtaqueEspecialBonus(options: { id: number; bonus: number }[], selectedId: number | null): number {
  if (selectedId === null) {
    return 0;
  }
  return options.find((option) => option.id === selectedId)?.bonus ?? 0;
}

// Turns the tier + split choice into ordinary mod_hit/mod_dmg effect
// entries — feeds straight into the same checkedEffects list every other
// checked power already goes through (resolveTag/calculateHit/
// calculateDamage), instead of duplicating the split math at every call
// site that needs a hit-side or dmg-side number.
export function getAtaqueEspecialEffects(bonus: number, mode: AtaqueEspecialMode): Effect[] {
  if (bonus === 0) {
    return [];
  }
  const effects: Effect[] = [];
  if (mode !== 'dmg') {
    effects.push({ tag: 'mod_hit', op: 'add', value: mode === 'split' ? bonus / 2 : bonus });
  }
  if (mode !== 'hit') {
    effects.push({ tag: 'mod_dmg', op: 'add', value: mode === 'split' ? bonus / 2 : bonus });
  }
  return effects;
}
