import { Character, CharacterActiveEffectRow, Effect } from '../../../api.service';
import { getStaticRegistry } from '../../hooks/static-registry';
import { ATTRIBUTE_CODES, resolveNumericSentinels } from '../resolve-numeric-sentinels/resolve-numeric-sentinels';
import { resolveTag } from '../tag-solver/tag-solver';
import { isTriggerSatisfied } from '../is-trigger-satisfied/is-trigger-satisfied';
import { resolveReplacedPowerIds } from '../resolve-replaced-power-ids/resolve-replaced-power-ids';
import { resolveTrainedSkillIds } from '../resolve-trained-skill-ids/resolve-trained-skill-ids';
import { scaleLevelEffect } from '../scale-level-effect/scale-level-effect';

// The Character fields getActiveEffects reads; a CharacterDraft can be passed in too.
export type ActiveEffectsSource = Pick<Character, 'active_effects' | 'active_spell_effects' | 'level' | 'base_str' | 'base_dex' | 'base_con' | 'base_int' | 'base_knw' | 'base_car'> &
  Partial<Pick<Character, 'inventory' | 'trained_skill_ids'>>;

interface CachedActiveEffects {
  catalogs: unknown[];
  effects: Effect[];
}

const activeEffectsCache = new WeakMap<object, CachedActiveEffects>();

// Flattens the character's is_active power rows and spell buffs into one Effect[]; see claude-stuff/system-parts/get-active-effects.md.
export function getActiveEffects(character: ActiveEffectsSource): Effect[] {
  const registry = getStaticRegistry();
  const catalogs: unknown[] = registry ? [registry.powers] : [];
  const isCachedCharacter = (character as Partial<Character>).id !== undefined;
  const cached = isCachedCharacter ? activeEffectsCache.get(character) : undefined;
  if (cached && cached.catalogs.length === catalogs.length && cached.catalogs.every((catalog, i) => catalog === catalogs[i])) {
    return cached.effects;
  }
  const effects = computeActiveEffects(character, registry);
  if (isCachedCharacter) {
    activeEffectsCache.set(character, { catalogs, effects });
  }
  return effects;
}

function computeActiveEffects(character: ActiveEffectsSource, registry: ReturnType<typeof getStaticRegistry>): Effect[] {
  const powers = registry?.powers ?? [];
  const effects: Effect[] = [];
  const activePowerIds = new Set((character.active_effects ?? []).filter((row) => row.is_active).map((row) => row.power_id));
  const replacedPowerIds = resolveReplacedPowerIds(new Set((character.active_effects ?? []).map((row) => row.power_id)), powers);

  const activeRowsByPowerId = new Map<number, CharacterActiveEffectRow[]>();
  for (const activeEffect of character.active_effects ?? []) {
    if (activeEffect.is_active) {
      activeRowsByPowerId.set(activeEffect.power_id, [...(activeRowsByPowerId.get(activeEffect.power_id) ?? []), activeEffect]);
    }
  }

  for (const rows of activeRowsByPowerId.values()) {
    const ownRow = rows.find((row) => row.source_inventory_id == null && row.source_companion_id == null);
    const activeEffect = ownRow ?? rows[0];
    const inventoryRowIds = rows.filter((row) => row.source_inventory_id != null).map((row) => row.source_inventory_id as number);
    const sourceInventoryIds = ownRow || inventoryRowIds.length === 0 ? undefined : inventoryRowIds;
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (!power || replacedPowerIds.has(power.id)) {
      continue;
    }
    // applies_when.active_power_id — this power's effects only count while
    // that other power is toggled on (e.g. Arsenal de Allihanna's Defesa
    // bonus, only while Armadura de Allihanna is active).
    if (power.applies_when?.active_power_id !== undefined && !activePowerIds.has(power.applies_when.active_power_id)) {
      continue;
    }
    // custom_effect (character_active_effects' own column) is per-character
    // customization for this specific granted-power instance — shaped
    // exactly like power.effects, so it goes through the same op-scaling
    // below. Used for open-ended player choices a shared Power row can't
    // represent (e.g. Espião's freely chosen skill_attribute target).
    for (const effect of [...(power.effects ?? []), ...(activeEffect.custom_effect ?? [])]) {
      // Only counts once THIS row's own other_sources_state says a second,
      // different-source grant actually happened (e.g. Empatia Selvagem) —
      // an 'open' or null row skips it entirely. See tag-system.md.
      if (!isTriggerSatisfied(effect, activeEffect.other_sources_state)) {
        continue;
      }
      effects.push({ ...scaleLevelEffect(effect, character.level), source_power_id: power.id, ...(sourceInventoryIds ? { source_inventory_ids: sourceInventoryIds } : {}) });
    }
  }

  // Spell buffs (character_active_spell_effects) — already the final,
  // resolved effects for that one casting (no power to join against, no
  // per-level scaling; see CharacterActiveSpellEffectRow). A row's mere
  // existence means it's active (no is_active flag — Remover deletes the
  // row outright, nothing ever needs "present but suspended"), but only
  // the passive-shaped entries within it fold in here — a 'roll_active'
  // one only applies to a specific roll and belongs in skill-roll-modal's
  // own checklist instead, never blanket-applied like this.
  for (const activeSpellEffect of character.active_spell_effects ?? []) {
    for (const effect of activeSpellEffect.effects) {
      if (effect.usability === 'roll_active') {
        continue;
      }
      effects.push({ ...effect, source_spell_id: activeSpellEffect.spell_id });
    }
  }

  // applies_when.trained_skill_id — needs every effect collected above
  // (some power may train the skill), so it runs as a pass over the result
  // instead of inside the per-power loop.
  const trainedSkillIds = resolveTrainedSkillIds(character.trained_skill_ids ?? [], effects);
  const gatedEffects = effects.filter((effect) => {
    const requiredSkillId = powers.find((p) => p.id === effect.source_power_id)?.applies_when?.trained_skill_id;
    return requiredSkillId === undefined || trainedSkillIds.has(requiredSkillId);
  });
  effects.length = 0;
  effects.push(...gatedEffects);

  const baseValues: Record<string, number> = {
    str: character.base_str,
    dex: character.base_dex,
    con: character.base_con,
    int: character.base_int,
    knw: character.base_knw,
    car: character.base_car,
  };
  const attributeValues: Record<string, number> = {};
  for (const code of ATTRIBUTE_CODES) {
    attributeValues[code] = baseValues[code] + resolveTag(effects, `mod_${code}`);
  }
  return effects.map((effect) => resolveNumericSentinels(effect, character.level, (code) => attributeValues[code] ?? 0));
}
