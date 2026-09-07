import { Power } from '../../../api.service';

/**
 * Transitive closure of "power grants power" — a power's own effects can
 * carry {tag:'power', op:'grant', power_id} entries (source: 'power_granted'
 * on the granted side) whose children should be added to a character
 * alongside it, same as origin/god/complication/age-bracket grants already
 * are. Walks breadth-first so a granted power that itself grants another
 * still resolves, though nothing seeded needs more than one hop today.
 * Returns rootIds plus every id reachable through a grant chain — callers
 * that only want the NEW ids filter rootIds back out themselves.
 */
export function resolveGrantedPowerIds(rootIds: number[], powers: Power[]): Set<number> {
  const result = new Set<number>(rootIds);
  let frontier = [...rootIds];

  while (frontier.length > 0) {
    const next: number[] = [];
    for (const id of frontier) {
      const power = powers.find((p) => p.id === id);
      for (const effect of power?.effects ?? []) {
        if (effect.tag === 'power' && effect.op === 'grant' && effect.power_id !== undefined && !result.has(effect.power_id)) {
          result.add(effect.power_id);
          next.push(effect.power_id);
        }
      }
    }
    frontier = next;
  }

  return result;
}
