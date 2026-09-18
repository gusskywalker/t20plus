import { Power, Spell } from '../../../api.service';

export interface LimitedSpellChoicePower {
  power: Power;
  /** How many spells this power lets the character pick — one per null-spell_id grant_or_reduce_spell_pm_cost_by_1 effect. */
  slotCount: number;
  circle: number | undefined;
  school: string | undefined;
}

/**
 * Every granted power that hands out a chosen spell whose pool is narrowed
 * by a limit_spell_choices effect (e.g. Sapiência: 1st-circle divination).
 * The chosen spell ids become custom_effects on that power at save time,
 * same shape Tatuagem Mística/Magia das Fadas already use.
 */
export function resolveLimitedSpellChoicePowers(grantedPowerIds: Set<number>, powers: Power[]): LimitedSpellChoicePower[] {
  return powers
    .filter((power) => grantedPowerIds.has(power.id))
    .flatMap((power) => {
      const effects = power.effects ?? [];
      const limit = effects.find((effect) => effect.tag === 'limit_spell_choices');
      if (!limit) {
        return [];
      }
      const slotCount = effects.filter((effect) => effect.tag === 'grant_or_reduce_spell_pm_cost_by_1' && effect.op === 'grant' && (effect.spell_id === null || effect.spell_id === undefined)).length;
      return slotCount > 0 ? [{ power, slotCount, circle: limit.spell_circle, school: limit.spell_school }] : [];
    });
}

/** 'specific' spells are only ever reachable through their own dedicated granting power, never freely pickable — same exclusion Tatuagem Mística's pool applies. */
export function limitedSpellPool(spells: Spell[], circle: number | undefined, school: string | undefined): Spell[] {
  return spells.filter((spell) => spell.type !== 'specific' && (circle === undefined || spell.circle === circle) && (school === undefined || spell.school === school));
}
