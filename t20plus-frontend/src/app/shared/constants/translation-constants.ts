// Every enum-value -> Portuguese-label lookup shared across the app, in one
// place so no two screens can drift apart on the same piece of text.

export const DAMAGE_TYPE_LABELS: Record<string, string> = {
  slashing: 'Corte',
  bludgeoning: 'Impacto',
  piercing: 'Perfuração',
  acid: 'Ácido',
  electricity: 'Eletricidade',
  fire: 'Fogo',
  cold: 'Frio',
  light: 'Luz',
  poison: 'Veneno',
  darkness: 'Trevas',
  essence: 'Essência',
  magic: 'Magia',
  psychic: 'Psíquico',
};

export const SPELL_SCHOOL_LABELS: Record<string, string> = {
  abjuracao: 'Abjuração',
  adivinhacao: 'Adivinhação',
  convocacao: 'Convocação',
  encantamento: 'Encantamento',
  evocacao: 'Evocação',
  ilusao: 'Ilusão',
  necromancia: 'Necromancia',
  transmutacao: 'Transmutação',
};

export const SPELL_TYPE_LABELS: Record<string, string> = {
  arcana: 'Arcana',
  divina: 'Divina',
  universal: 'Universal',
  specific: '',
};

// str/dex/con/int/knw/car -> full attribute name.
export const ATTRIBUTE_NAME_LABELS: Record<string, string> = {
  str: 'Força',
  dex: 'Destreza',
  con: 'Constituição',
  int: 'Inteligência',
  knw: 'Conhecimento',
  car: 'Carisma',
};

// mod_str/mod_dex/... -> attribute abbreviation, for a compact stat readout.
export const ATTRIBUTE_ABBREVIATION_LABELS: Record<string, string> = {
  mod_str: 'FOR',
  mod_dex: 'DEX',
  mod_con: 'CON',
  mod_int: 'INT',
  mod_knw: 'SAB',
  mod_car: 'CAR',
};

// races.base_size / characters.current_size — -2 Minúsculo through +3 Colossal.
export const CHARACTER_SIZE_LABELS: Record<number, string> = {
  [-2]: 'Minúsculo',
  [-1]: 'Pequeno',
  [0]: 'Médio',
  [1]: 'Grande',
  [2]: 'Enorme',
  [3]: 'Colossal',
};

// weapons_rules.md — Reduzida -1, Normal 0, Aumentada 1, Gigante 2.
export const WEAPON_SIZE_LABELS: Record<number, string> = {
  [-1]: 'Tamanho Reduzido',
  [0]: 'Tamanho Normal',
  [1]: 'Tamanho Aumentado',
  [2]: 'Tamanho Gigante',
};

export const WEAPON_PURPOSE_LABELS: Record<string, string> = {
  melee: 'Corpo a Corpo',
  thrown: 'Arremesso',
  fired: 'Disparo',
};

export const WEAPON_GRIP_LABELS: Record<string, string> = {
  light: 'Leve - Uma Mão',
  one_hand: 'Uma Mão',
  two_hand: 'Duas Mãos',
  natural: 'Natural',
};

export const ACTION_COST_LABELS: Record<string, string> = {
  standard: 'Ação Padrão',
  movement: 'Ação de Movimento',
  complete: 'Ação Completa',
  extra: 'Ação Extra',
  free: 'Ação Livre',
  none: 'Nenhuma',
  reaction: 'Reação',
};

export const RESISTANCE_LABELS: Record<string, string> = {
  fortitude: 'Fortitude',
  reflexos: 'Reflexos',
  vontade: 'Vontade',
};
