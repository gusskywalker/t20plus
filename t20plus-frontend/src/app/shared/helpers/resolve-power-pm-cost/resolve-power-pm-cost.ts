import { CharacterActiveEffectRow, Power } from '../../../api.service';
import { resolveTag } from '../tag-solver/tag-solver';

// A power's own pm_cost, discounted by its own mod_own_pm_cost effect once
// other_sources_state is 'satisfied' (e.g. Engenhosidade: -1 PM if granted
// again from elsewhere). See tag-system.md's other_sources_state section.
export function resolvePowerPmCost(power: Power, effect: Pick<CharacterActiveEffectRow, 'other_sources_state'>): number {
  if (effect.other_sources_state !== 'satisfied') {
    return power.pm_cost;
  }
  return power.pm_cost + resolveTag(power.effects ?? [], 'mod_own_pm_cost');
}
