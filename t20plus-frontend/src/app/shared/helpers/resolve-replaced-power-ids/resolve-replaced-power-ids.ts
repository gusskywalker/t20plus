import { Power } from '../../../api.service';

/**
 * replaces_power — the ids of granted powers a granted upgrade power stands in
 * for (Golpe dos Titãs over Força dos Titãs). A replaced power stays owned
 * (still on the sheet, still satisfies prerequisites) but its effects and
 * checkboxes are skipped by every effect/roll consumer.
 */
export function resolveReplacedPowerIds(grantedPowerIds: Set<number>, powers: Power[]): Set<number> {
  const replaced = new Set<number>();
  powers
    .filter((power) => grantedPowerIds.has(power.id))
    .flatMap((power) => power.effects ?? [])
    .filter((effect) => effect.tag === 'replaces_power' && effect.op === 'grant' && effect.power_id !== undefined)
    .forEach((effect) => replaced.add(effect.power_id as number));
  return replaced;
}
