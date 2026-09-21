// Skill ids the size table applies to: Furtividade (passive) and Luta
// (maneuvers, see the "Tamanho" row in skill-roll-modal.ts).
export const FURTIVIDADE_SKILL_ID = 11;
export const LUTA_SKILL_ID = 19;

// character-size-rules.md — keyed by the same -2 Minúsculo .. +3 Colossal
// number as characters.current_size / resolveCurrentSize. Médio (0) has none.
export const CHARACTER_SIZE_MODIFIERS: Record<number, { furtividade: number; manobras: number }> = {
  [-2]: { furtividade: 5, manobras: -5 },
  [-1]: { furtividade: 2, manobras: -2 },
  [0]: { furtividade: 0, manobras: 0 },
  [1]: { furtividade: -2, manobras: 2 },
  [2]: { furtividade: -5, manobras: 5 },
  [3]: { furtividade: -10, manobras: 10 },
};
