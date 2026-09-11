import { Effect } from '../../../../api.service';

// Enhancement index 2 ("Apenas Devotos de Lin-Wu: muda o bônus de dano do
// aprimoramento acima para +2d4") doesn't grant its own effect — it
// REWRITES enhancement 0's own mod_dmg/extra_die value (1d4 -> 2d4), which
// the generic system has no way to express (see
// SpellEnhancement.requires_enhancement_index/requires_god_id for how the
// pick itself is still gated generically — checkable only once enhancement
// 0 is checked and only by a Lin-Wu devotee). Same "hardcode the
// exception, call a dedicated resolver" convention as sono.ts.
export const ARMA_DE_JADE_SPELL_ID = 16;
const LIN_WU_UPGRADE_ENHANCEMENT_INDEX = 2;

export function applyArmaDeJadeUpgrade(buffEffects: Effect[], counts: Record<number, number>): Effect[] {
  if ((counts[LIN_WU_UPGRADE_ENHANCEMENT_INDEX] ?? 0) === 0) {
    return buffEffects;
  }
  return buffEffects.map((effect) => (effect.tag === 'mod_dmg' && effect.op === 'extra_die' ? { ...effect, value: '2d4' } : effect));
}
