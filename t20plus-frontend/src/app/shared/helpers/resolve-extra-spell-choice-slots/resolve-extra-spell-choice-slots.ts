import { Power } from '../../../api.service';
import { calculateMaxSpellCircle } from '../calculators/calculate-max-spell-circle/calculate-max-spell-circle';
import { SpellSlot } from '../calculators/calculate-spell-slot-circle-caps/calculate-spell-slot-circle-caps';

/**
 * add_spell_choices — the extra known-spell slots a power picked at this class
 * level adds on top of the class's own slots (Conhecimento Mágico: 2), each
 * capped at the círculo the class has unlocked at that level. An effect with
 * spell_schools/spell_types/max_circle makes a restricted slot (Linhagem Feérica).
 */
export function resolveExtraSpellChoiceSlots(classId: number, classLevel: number, power: Power | undefined): SpellSlot[] {
  return (power?.effects ?? [])
    .filter((effect) => effect.tag === 'add_spell_choices' && effect.op === 'add')
    .flatMap((effect) => {
      const cap = Math.min(calculateMaxSpellCircle(classId, classLevel), effect.max_circle ?? Infinity);
      return Array.from({ length: Number(effect.value ?? 0) }, () => ({
        classId,
        classLevel,
        cap,
        ...(power ? { sourceName: power.name } : {}),
        ...(effect.spell_schools ? { schools: effect.spell_schools } : {}),
        ...(effect.spell_types ? { types: effect.spell_types } : {}),
      }));
    });
}
