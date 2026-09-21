import { Character, Power } from '../../../api.service';
import { resolveOtherSourceGrantingPower } from '../resolve-spell-caster-info/resolve-spell-caster-info';

/**
 * add_spell_school — the schools the power that granted this spell adds to it
 * on top of its own (Glamour: ilusão).
 */
export function resolveSpellExtraSchools(character: Character, spellId: number, powers: Power[]): string[] {
  const grantingPower = resolveOtherSourceGrantingPower(character, spellId, powers);
  return (grantingPower?.effects ?? []).filter((effect) => effect.tag === 'add_spell_school' && effect.op === 'add').map((effect) => String(effect.value));
}
