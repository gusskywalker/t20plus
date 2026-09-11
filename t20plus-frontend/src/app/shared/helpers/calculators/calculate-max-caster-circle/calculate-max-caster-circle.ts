import { Power } from '../../../../api.service';
import { calculateMaxSpellCircle } from '../calculate-max-spell-circle/calculate-max-spell-circle';

/**
 * Highest spell círculo reachable through ANY caster class the character
 * (or draft) currently has — used by the 'available_spell_circle' prerequisite type
 * (e.g. Magia Acelerada's "lançar magias de 2º círculo"). A class counts as
 * a caster purely by having a granted power that carries
 * `starting_spell_count` (same detection resolve-caster-spell-slots.ts
 * uses), so no class id is hardcoded here either.
 *
 * `classLevelForClassId` abstracts over the one thing that differs between
 * a CharacterDraft (orderedClassIds().filter(...).length) and a real
 * Character (levels.filter(...).length) — everything else is identical.
 */
export function calculateMaxCasterCircle(grantedPowerIds: Iterable<number>, classLevelForClassId: (classId: number) => number, powers: Power[]): number {
  const granted = new Set(grantedPowerIds);
  let maxCircle = 0;

  for (const power of powers) {
    if (!granted.has(power.id)) {
      continue;
    }
    if (!(power.effects ?? []).some((effect) => effect.tag === 'starting_spell_count')) {
      continue;
    }
    const classId = (power.prerequisites ?? []).find((prerequisite) => prerequisite.type === 'class')?.class_ids?.[0];
    if (classId === undefined) {
      continue;
    }
    maxCircle = Math.max(maxCircle, calculateMaxSpellCircle(classId, classLevelForClassId(classId)));
  }

  return maxCircle;
}
