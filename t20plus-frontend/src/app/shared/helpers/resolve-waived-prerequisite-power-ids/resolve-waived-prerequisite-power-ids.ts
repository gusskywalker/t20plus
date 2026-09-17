import { Power } from '../../../api.service';

// Which power ids get their own prerequisites skipped entirely (e.g. Ginete
// Natural letting a Centauro pick Carga de Cavalaria without Ginete) — both
// character-creation-powers-step's and level-change-modal's own
// checkPrerequisites take a plain Set<number> of already-granted ids rather
// than a full Character, so this stays equally pure instead of going
// through getActiveEffects.
export function resolveWaivedPrerequisitePowerIds(grantedPowerIds: Set<number>, powers: Power[]): Set<number> {
  const waived = new Set<number>();
  powers
    .filter((power) => grantedPowerIds.has(power.id))
    .flatMap((power) => power.effects ?? [])
    .filter((effect) => effect.tag === 'waive_prerequisites' && effect.op === 'grant')
    .forEach((effect) => (effect.power_ids ?? []).forEach((id) => waived.add(id)));
  return waived;
}
