import { Power } from '../../../api.service';

export interface SkillBonusChoicePower {
  power: Power;
  slotCount: number;
  bonus: number;
  skillIds: number[];
}

/** Every granted power carrying a choice_bonus_to_skills effect (e.g. Esperteza Vulpina). The picks become {tag:'skill', op:'add'} custom_effects on that power at save time. */
export function resolveSkillBonusChoicePowers(grantedPowerIds: Set<number>, powers: Power[]): SkillBonusChoicePower[] {
  return powers
    .filter((power) => grantedPowerIds.has(power.id))
    .flatMap((power) => {
      const effect = (power.effects ?? []).find((e) => e.tag === 'choice_bonus_to_skills');
      const slotCount = Number(effect?.value ?? 0);
      if (!effect || slotCount <= 0 || !effect.skill_ids || effect.skill_ids.length === 0) {
        return [];
      }
      return [{ power, slotCount, bonus: Number(effect.bonus ?? 0), skillIds: effect.skill_ids }];
    });
}
