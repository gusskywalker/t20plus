import { CharacterActiveEffectRow, Effect } from '../../../api.service';

/** An effect carrying trigger 'on_other_sources_satisfied' only counts once its granting row's other_sources_state is 'satisfied'; every other effect always counts. */
export function isTriggerSatisfied(effect: Effect, otherSourcesState: CharacterActiveEffectRow['other_sources_state']): boolean {
  return effect.trigger !== 'on_other_sources_satisfied' || otherSourcesState === 'satisfied';
}
