import { Effect } from '../../../api.service';

/** The saved/wizard-picked trained skill ids plus every skill an active effect trains (`{tag:'skill', op:'trains'}`). */
export function resolveTrainedSkillIds(baseTrainedSkillIds: Iterable<number>, activeEffects: Effect[]): Set<number> {
  const ids = new Set<number>(baseTrainedSkillIds);
  for (const effect of activeEffects) {
    if (effect.tag === 'skill' && effect.op === 'trains' && effect.skill_id !== undefined) {
      ids.add(effect.skill_id);
    }
  }
  return ids;
}
