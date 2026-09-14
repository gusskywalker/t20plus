// Golpe Pessoal ("outras vezes para golpes diferentes") and Conhecimento
// Mágico ("quantas vezes quiser") are both explicitly repeatable per the
// rulebook — every other power is a one-time pick. Shared by every
// power-picking dropdown (character-creation-powers-step, level-change-modal)
// so a new repeatable power only ever needs adding here once.
export const REPEATABLE_POWER_IDS = new Set([115, 2002]); // Golpe Pessoal, Conhecimento Mágico

// Hardcoded per-power hint shown under a power-pick-row once that power is
// picked. Same sharing reasoning as REPEATABLE_POWER_IDS above.
export const POWER_PICK_HINTS: Record<number, string> = {
  115: 'Customize na página do personagem.', // Golpe Pessoal
  2002: 'Adicione as magias manualmente na página do personagem.', // Conhecimento Mágico
  2047: 'Adicione a magia extra e o poder manualmente na página do personagem.', // Linhagem Abençoada (Básica)
  2049: 'Adicione a magia manualmente na página do personagem.', // Linhagem Feérica (Básica)
  2050: 'Adicione o poder manualmente na página do personagem.', // Linhagem Rubra
  2052: 'Adicione as magias manualmente na tela do personagem.', // Herança Superior (Abençoada)
  2083: 'Escolha as magias manualmente.', // Apoteose Celestial (Abençoada)
  // Aumentar Atributo (Inteligência)'s 4 patamar tiers — bumping Int grows
  // step 6's bonus skill-pick count (effectiveInt), which the player might
  // not otherwise notice from this screen alone.
  58: 'Selecione mais uma perícia!',
  59: 'Selecione mais uma perícia!',
  60: 'Selecione mais uma perícia!',
  61: 'Selecione mais uma perícia!',
};
