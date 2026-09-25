// Qareen's Tatuagem Mística / Sereia-Tritão's Canção dos Mares
// (RaceGrantedPowerSeeder.php) — both grant a spell-step pick (spells-step-
// edge-cases) even for a character with no real caster class at all, so
// character-creation-powers-step's own "does this character need to visit
// spells-step" gate needs these ids too, not just resolveCasterSpellSlots.
export const TATUAGEM_MISTICA_POWER_ID = 16055;
export const CANCAO_DOS_MARES_POWER_ID = 16056;
export const MAGIA_DAS_FADAS_POWER_ID = 16063;

// Duende (Animal) — RaceGrantedPowerSeeder.php. Its "+1 em um atributo a sua
// escolha" is picked in the attributes step (duendeAnimalAttribute).
export const DUENDE_ANIMAL_POWER_ID = 16187;

// Duende Aleatório — RaceGrantedPowerSeeder.php, granted by the "Criado
// Aleatoriamente" checkbox in the basic-info step.
export const DUENDE_RANDOMLY_CREATED_POWER_ID = 16201;

// Golem's Chassi Mashin — RaceGrantedPowerSeeder.php. Caps Tamanho at Médio
// in the golem section.
export const GOLEM_MASHIN_CHASSI_POWER_ID = 16230;

// Golpe Pessoal ("outras vezes para golpes diferentes") and Conhecimento
// Mágico ("quantas vezes quiser") are both explicitly repeatable per the
// rulebook — every other power is a one-time pick. Shared by every
// power-picking dropdown (character-creation-powers-step, level-change-modal)
// so a new repeatable power only ever needs adding here once.
export const REPEATABLE_POWER_IDS = new Set([115, 2002, 17024]); // Golpe Pessoal, Conhecimento Mágico, Arma Natural Aprimorada

// Hardcoded per-power hint shown under a power-pick-row once that power is
// picked. Same sharing reasoning as REPEATABLE_POWER_IDS above.
export const POWER_PICK_HINTS: Record<number, string> = {
  115: 'Customize na página do personagem.', // Golpe Pessoal
  2047: 'Adicione o poder divino do 2º nível manualmente na página do personagem.', // Linhagem Abençoada (Básica)
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
