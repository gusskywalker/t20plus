// Luta and Pontaria — the two skills a T20 attack roll is actually made
// with. Used to exclude combat skills from an `all_skills_no_combat` tag.
export const COMBAT_SKILL_IDS = [19, 25];

// A `skill_id` value on an effect (skill_attribute) meaning every skill
// except the combat ones.
export const ALL_SKILLS_NO_COMBAT = 'all_skills_no_combat';

// Whether an effect's `skill_id` targets this skill: the exact id, or the
// ALL_SKILLS_NO_COMBAT scope for any non-combat skill.
export function effectSkillIdMatches(effectSkillId: number | typeof ALL_SKILLS_NO_COMBAT | undefined, skillId: number): boolean {
  return effectSkillId === skillId || (effectSkillId === ALL_SKILLS_NO_COMBAT && !COMBAT_SKILL_IDS.includes(skillId));
}
