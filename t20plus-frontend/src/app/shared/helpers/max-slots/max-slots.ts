import { Character, Power } from '../../../api.service';
import { getActiveEffects } from '../get-active-effects/get-active-effects';
import { resolveEffectSentinels } from '../resolve-effect-sentinels/resolve-effect-sentinels';
import { resolveTag } from '../tag-solver/tag-solver';

/**
 * Max carry slots — 10 + 2×Força for a positive Força, or 10 + 1×Força for
 * a negative one (asymmetric penalty, per claude-stuff/rules/inventory-slots.md),
 * plus any mod_inventory_space from active powers (e.g. Andarilho Carregado,
 * Familiar (T'peel)) — a pre-existing tag with no consumer until now. No
 * overloaded/double-limit handling — not requested yet.
 */
export function calculateMaxSlots(character: Character, powers: Power[]): number {
  const str = character.base_str;
  const baseline = str >= 0 ? 10 + 2 * str : 10 + 1 * str;
  return baseline + resolveTag(resolveEffectSentinels(getActiveEffects(character, powers), character, powers), 'mod_inventory_space');
}
