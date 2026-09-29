# Skill roll modal

`t20plus-frontend/src/app/shared/modals/skill-roll-modal/skill-roll-modal.ts`. One skill check: power checklist, then Rolar (d20 carousel), then the total with a breakdown. No Passou/Falhou step. Attacks never come through here (attack modal).

## Inputs
- `skillId` — the clicked skill row; `skill()` looks it up in the registry.
- Checked state is `checkedPowerIds` (per modal open, effect-id keyed, no persistence).

## Checklist (`skillPowerRows`)
- Source: every `roll_active` row from `getRollActivePowers` (own powers, owned general items, worn items, spell-buff synthetic rows). Passive and toggled-on powers are not here — they are already inside the skill's bonus via `calculateSkillBonusBreakdown`.
- A row is kept when at least one of its effects passes `matchesThisSkill(effect, skillId)`:
  - `skill` with this `skill_id`
  - `all_skills`
  - `all_skills_no_combat` (not Luta/Pontaria, `COMBAT_SKILL_IDS`)
  - `skill_group` whose `attribute` equals this skill's resolved key attribute (`resolveSkillKeyAttribute`, so a `skill_attribute` swap counts) and this skill is not in `exclude_skill_ids`
  - `skill_attribute` override matching this skill (`effectSkillIdMatches`)
  - `advantage` / `disadvantage` with `scope: 'skill'` (exact `skill_id`, or every skill under an `attribute` minus `exclude_skill_ids`)
- A non-spell row must also pass `matchesTrainedState`: `applies_when.skill_trained` / `skill_not_trained` compare against whether this skill is trained now.
- Spell-buff rows are narrowed to just their matching effects.
- Luta rolls get a synthetic "Tamanho" row (size Manobras modifier, id -900001) unless the size modifier is 0.
- Label is `[N PM] name` from `resolvePowerPmCost` when the cost is above 0.

## Roll (`roll`)
1. Spends the checked rows' summed PM cost up front (`spendPm`).
2. Advantage/disadvantage (`hasAdvantage` / `hasDisadvantage`, read from the checked rows' effects): both cancel out; exactly one rolls two dice, keeping the best (advantage) or worst (disadvantage).
3. `forceTrained` when a checked row has `skill op trains` for this skill.
4. Total = d20 + `calculateSkillBonusBreakdown` parts + the checked rows' bonuses.
5. Result and breakdown are revealed after the carousel animation (`carouselTransitionMs`, 1400).

## Checked-row bonus, per row
- Effects are filtered by `isTriggerSatisfied` and passed through `resolveEffectSentinels` first (`attribute_*` / `int` style values become numbers).
- Value = `resolveTag` of `skill` (this id) + `all_skills` + `all_skills_no_combat` + `skill_group` (same match as the checklist) + the `skill_attribute` swap difference (new attribute bonus minus the skill's current key attribute bonus).
- One breakdown line per checked row with a nonzero value, named after the power.

## Notes
- The carousel state and SCSS are copies of the attack modal's; the numbers must stay in sync.
