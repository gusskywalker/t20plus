import { CharacterGolpePessoalRow, Effect, Power } from '../../../api.service';

/**
 * Merges a built golpe's power_ids into one flat Effect[] — each id's own
 * effects, concatenated, duplicates included (e.g. Elemental picked twice
 * contributes its extra_die entry twice). Resolved live from the current
 * powers list, same reasoning as everywhere else that reads power_ids:
 * never cached, so a menu item's own balance change picks up automatically.
 * The result just joins whatever pool of modifiers the caller (attack-modal
 * today, any future roll screen later) is already summing — no separate
 * resolution path, no golpe-specific handling downstream.
 *
 * Doesn't yet special-case non-linear stacking (e.g. Letal picked twice
 * should total -5, not -2-2=-4 — see PowerSeeder.php) — every effect here
 * is summed generically for now.
 */
export function resolveGolpePessoalEffects(golpe: CharacterGolpePessoalRow, powers: Power[]): Effect[] {
  return (golpe.power_ids ?? []).flatMap((id) => powers.find((power) => power.id === id)?.effects ?? []);
}
