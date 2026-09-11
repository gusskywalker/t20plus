import { Power } from '../../api.service';
import { calculateSpellSlotCircleCaps, SpellSlot } from '../../shared/helpers/calculators/calculate-spell-slot-circle-caps/calculate-spell-slot-circle-caps';
import { CharacterDraft } from './character-draft';

/**
 * Resolves the current draft's known-spell slots across EVERY caster class
 * the character has (a build can multiclass into more than one caster, e.g.
 * Arcanista + Bardo) — used by both step 10 (to render one dropdown per
 * slot) and character-payload.ts (to group the player's picks back onto the
 * right character_levels row by class_id + classLevel). Kept in one place
 * so the two never drift out of sync on how a caster's starting/growth
 * numbers get read.
 *
 * A class is "a caster" purely by having a granted power that carries
 * `starting_spell_count`/`spell_count_growth` effects (e.g. Arcanista's own
 * Bruxo/Feiticeiro/Mago path powers) — nothing here hardcodes which class
 * ids are casters, so a future caster class (Clérigo, Druida, Bardo...)
 * works automatically as soon as its own granted power carries those same
 * tags, no changes needed here.
 */
export function resolveCasterSpellSlots(draft: CharacterDraft, powers: Power[]): SpellSlot[] {
  const slots: SpellSlot[] = [];

  for (const powerId of draft.grantedPowerIds()) {
    const power = powers.find((p) => p.id === powerId);
    if (!power) {
      continue;
    }

    const startingEffect = (power.effects ?? []).find((e) => e.tag === 'starting_spell_count');
    const growthEffect = (power.effects ?? []).find((e) => e.tag === 'spell_count_growth');
    if (!startingEffect || !growthEffect) {
      continue;
    }

    const classId = (power.prerequisites ?? []).find((p) => p.type === 'class')?.class_ids?.[0];
    if (classId === undefined) {
      continue;
    }

    const classLevel = draft.orderedClassIds().filter((id) => id === classId).length;
    const startingSpellCount = Number(startingEffect.value ?? 0);
    const growthValue = Number(growthEffect.value ?? 0);
    const growthPerLevels = growthEffect.per_levels ?? 1;

    slots.push(...calculateSpellSlotCircleCaps(classId, classLevel, startingSpellCount, growthValue, growthPerLevels));
  }

  return slots;
}
