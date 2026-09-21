import { Power } from '../../../../../api.service';

//TODO add the other caster classes' power ids here
export const CASTER_CLASS_POWER_IDS = [328, 329, 330];

export function resolveCasterClassId(power: Power): number | undefined {
  return power.prerequisites?.find((prerequisite) => prerequisite.type === 'class')?.class_ids?.[0];
}
