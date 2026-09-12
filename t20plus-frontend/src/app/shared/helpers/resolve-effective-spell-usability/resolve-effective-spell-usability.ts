import { Spell, SpellEnhancement } from '../../../api.service';

/**
 * A spell's own `usability` column is its DEFAULT dispatch key, but a
 * checked enhancement carrying `change_usability`/`set` can override it for
 * THIS specific cast (e.g. Bênção's "muda o alvo para 1 cadáver" truque
 * turns it from a living-ally buff into a 'utility' effect — no ally to
 * open the (soon-to-exist) ally-picker step for). Both resolveCast's own
 * dispatch and that future ally-picker step need to ask this same question,
 * so it lives here instead of being duplicated in each.
 *
 * Only the first matching checked enhancement is used — spells aren't
 * expected to author more than one change_usability entry across their own
 * enhancements.
 */
export function resolveEffectiveSpellUsability(spell: Spell, enhancements: SpellEnhancement[], counts: Record<number, number>): Spell['usability'] {
  for (let i = 0; i < enhancements.length; i++) {
    if ((counts[i] ?? 0) === 0) {
      continue;
    }
    const override = (enhancements[i].effects ?? []).find((effect) => effect.tag === 'change_usability' && effect.op === 'set');
    if (override) {
      return override.value as Spell['usability'];
    }
  }
  return spell.usability;
}
