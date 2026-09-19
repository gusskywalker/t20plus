import { Spell, SpellEnhancement } from '../../../api.service';

/** A spell's own `buff_affects` plus every target a checked enhancement adds via `add_buff_affects`/`grant` (e.g. Invisibilidade's touch enhancement opening allies). */
export function resolveEffectiveBuffAffects(spell: Spell, enhancements: SpellEnhancement[], counts: Record<number, number>): ('caster' | 'allies')[] {
  const affects = [...(spell.buff_affects ?? [])];
  for (let i = 0; i < enhancements.length; i++) {
    if ((counts[i] ?? 0) === 0) {
      continue;
    }
    for (const effect of enhancements[i].effects ?? []) {
      if (effect.tag === 'add_buff_affects' && effect.op === 'grant' && (effect.value === 'caster' || effect.value === 'allies') && !affects.includes(effect.value)) {
        affects.push(effect.value);
      }
    }
  }
  return affects;
}
