import { Power } from '../../../api.service';

// A caster's own path power (Bruxo/Feiticeiro/Mago — carries
// starting_spell_count) stays source: 'class' so it renders normally on the
// character sheet, but must never show up in a manually-browsable power
// dropdown — it's only ever picked through its own dedicated UI
// (ArcanistaPathSection) at the class's own level 1. Centralized here so
// every dropdown (class-power, general-power, level-up alike) gets this
// exclusion automatically instead of each needing its own copy.
function isHiddenFromDropdowns(power: Power): boolean {
  return (power.effects ?? []).some((effect) => effect.tag === 'starting_spell_count');
}

/** Source-matching rule for a class-level-up dropdown at a specific class/classLevel — 'general'/'tormenta'/'group' always qualify, 'class' needs a matching class prerequisite whose min_level is at or below this class-relative level, 'race_optional' needs a matching race. */
export function matchesClassPower(power: Power, classId: number, classLevel: number, raceId: number | null): boolean {
  if (power.source === 'general' || power.source === 'tormenta' || power.source === 'group') {
    return true;
  }
  if (power.source === 'class') {
    return (power.prerequisites ?? []).some(
      (prerequisite) => prerequisite.type === 'class' && (prerequisite.class_ids ?? []).includes(classId) && classLevel >= (prerequisite.min_level ?? 0),
    );
  }
  if (power.source === 'race_optional') {
    return (power.prerequisites ?? []).some((prerequisite) => prerequisite.type === 'race' && (prerequisite.race_ids ?? []).includes(raceId ?? -1));
  }
  return false;
}

/** Source-matching rule for a "poder geral" dropdown (Complicação/Adulto/Ambição Herdada) — 'general' always qualifies, 'race_optional' needs a matching race, plus whatever extra sources the caller allows (e.g. Ambição Herdada also allows 'origin_granted'). */
export function matchesGeneralPower(power: Power, raceId: number | null, extraSources: string[] = []): boolean {
  if (power.source === 'general' || extraSources.includes(power.source)) {
    return true;
  }
  if (power.source === 'race_optional') {
    return (power.prerequisites ?? []).some((prerequisite) => prerequisite.type === 'race' && (prerequisite.race_ids ?? []).includes(raceId ?? -1));
  }
  return false;
}

/**
 * Shared fetch/filter for every power-picking dropdown (character-creation
 * step 9's per-level and general dropdowns, level-change-modal's level-up
 * dropdown): excludes already-granted powers (with an own-pick carve-out so
 * the dropdown can still show its current value, and an optional
 * repeatable-power carve-out for Golpe Pessoal), excludes anything hidden
 * from manual picking (see isHiddenFromDropdowns), then applies the
 * caller's own source-matching and prerequisite rules — those two stay
 * screen-specific since they read from different data (a wizard's
 * in-progress draft vs. a real saved Character), everything else is
 * identical across every dropdown.
 */
export function resolveAvailablePowers(params: {
  powers: Power[];
  granted: Set<number>;
  ownPickId: number | null;
  repeatableIds?: Set<number>;
  matchesSource: (power: Power) => boolean;
  checkPrerequisites: (power: Power) => boolean;
}): Power[] {
  const { powers, granted, ownPickId, repeatableIds, matchesSource, checkPrerequisites } = params;
  return powers
    .filter((power) => {
      if (granted.has(power.id) && power.id !== ownPickId && !(repeatableIds?.has(power.id) ?? false)) {
        return false;
      }
      if (isHiddenFromDropdowns(power)) {
        return false;
      }
      return matchesSource(power) && checkPrerequisites(power);
    })
    .sort((a, b) => a.name.localeCompare(b.name, 'pt-BR'));
}
