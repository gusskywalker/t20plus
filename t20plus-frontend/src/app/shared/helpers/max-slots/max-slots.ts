import { Character } from '../../../api.service';

/**
 * Max carry slots — 10 + 2×Força for a positive Força, or 10 + 1×Força for
 * a negative one (asymmetric penalty, per claude-stuff/rules/inventory-slots.md).
 * No overloaded/double-limit handling — not requested yet.
 */
export function calculateMaxSlots(character: Character): number {
  const str = character.base_str;
  return str >= 0 ? 10 + 2 * str : 10 + 1 * str;
}
