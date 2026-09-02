import { Effect } from '../../../api.service';

/**
 * Combines every entry matching `tag` (and, if given, `matcher`) in an
 * already-collected Effect[] into one number. Subject-agnostic on purpose —
 * doesn't know about Character/Power/active_effects, just the list it's
 * handed, so it's reusable for a character's passive effects today and a
 * weapon's/spell's effects later. `add_per_level` is expected to already be
 * pre-scaled into a flat `add` by whatever collected the list (that's the
 * one op that needs a level, which this function deliberately doesn't have).
 */
export function resolveTag(effects: Effect[], tag: string, matcher?: (effect: Effect) => boolean): number {
  let total = 0;

  for (const effect of effects) {
    if (effect.tag !== tag) {
      continue;
    }
    if (matcher && !matcher(effect)) {
      continue;
    }

    switch (effect.op) {
      case 'add':
        total += Number(effect.value ?? 0);
        break;
      case 'set':
      case 'override':
        total = Number(effect.value ?? 0);
        break;
    }
  }

  return total;
}
