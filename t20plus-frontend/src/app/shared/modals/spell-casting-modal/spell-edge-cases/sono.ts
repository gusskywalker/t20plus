// Sono's success-side outcome branches on whether the target was in combat
// or another dangerous situation — not expressible through the generic
// trigger/tag/op effect system, so it gets its own resolver here, same
// "hardcode the exception, call a dedicated resolver" convention as
// attack-modal's own attack-power-resolvers/. The Falhou side ("se passar,
// fica fatigado") doesn't depend on combat state, so it stays fully
// generic (trigger: 'on_spell_fail') and never reaches this file.
export const SONO_SPELL_ID = 4;

// Inconsciente(6)+Caído(8) apply together (the rulebook's "e"). In combat,
// only Exausto(11) is reported — "fica exausto por 1 rodada, DEPOIS
// fatigado" is sequential, not simultaneous, so Fatigado is just what
// naturally follows once Exausto ends, not a second thing to report as
// caused right now. (The "Alvo em Combate" checkbox is
// spell.enhancements[0], a context_flag/target_in_combat entry, not a
// real mechanical enhancement.)
export function resolveSonoConditionIds(targetInCombat: boolean): number[] {
  return targetInCombat ? [11] : [6, 8];
}
