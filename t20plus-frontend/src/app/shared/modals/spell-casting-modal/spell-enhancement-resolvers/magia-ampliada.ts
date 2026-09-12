import { Spell } from '../../../../api.service';

export const MAGIA_AMPLIADA_POWER_ID = 352;

// "Aumenta o alcance da magia em um passo (de curto para médio, de médio
// para longo) ou dobra a área de efeito" — only eligible for a spell that
// has one of those two things: a range that can still step up (curto or
// médio — nothing above longo to step into), or a spatial area to double.
// A genuine OR across two different Spell fields, which applies_when can't
// express generically (its own fields are always AND'd together — see
// AppliesWhen's own comment), so this gets its own small resolver instead,
// same convention as spell-edge-cases/ for per-spell bespoke behavior.
export function isMagiaAmpliadaEligible(spell: Spell): boolean {
  return spell.range === 'curto' || spell.range === 'médio' || spell.info_affected_area !== null;
}
