export interface AgeBracketItem {
  id: string | null;
  name: string;
  mod_str?: number;
  mod_dex?: number;
  mod_con?: number;
  mod_int?: number;
  mod_knw?: number;
  mod_car?: number;
  // Names of the bracket's other age_granted powers (not attribute mods) —
  // e.g. Criança's Tamanho Menor/Protegido dos Deuses/Sem Origem.
  extraPowers?: string[];
  // Same powers as extraPowers, by id — matches the ids PowerSeeder.php
  // seeded for each age_granted power, hardcoded here rather than fetched
  // (this is the only place in the app that needs it, doesn't justify a
  // whole StaticRegistry entry — there is no age_brackets DB table).
  // Includes the bracket's own baseline marker power too (e.g. 36
  // "Adulto"), not just its named extras — every bracket-mod power
  // attached to this bracket.
  powerIds?: number[];
}

// Fixed list, not DB-backed — T20's faixas etárias. Stat mods/extra powers
// are filled in per bracket as its own age_granted powers get seeded;
// still-bare entries just haven't been done yet. Shared between step 7
// (display) and shared/helpers/character-payload (resolving power_ids at
// save time).
export const AGE_BRACKETS: AgeBracketItem[] = [
  { id: null, name: 'Nenhuma' },
  {
    id: 'criança',
    name: 'Criança',
    mod_str: -2,
    mod_con: -1,
    mod_knw: -1,
    extraPowers: ['Tamanho Menor', 'Protegido dos Deuses', 'Sem Origem'],
    powerIds: [28, 29, 31, 30],
  },
  {
    id: 'adolescente',
    name: 'Adolescente',
    mod_knw: -1,
    extraPowers: ['Ímpeto Juvenil', 'Origem em Construção'],
    powerIds: [32, 33, 34],
  },
  { id: 'jovem', name: 'Jovem', powerIds: [35] },
  {
    id: 'adulto',
    name: 'Adulto',
    extraPowers: ['Poder Geral', 'Complicação (idade)'],
    powerIds: [36],
  },
  {
    id: 'maduro',
    name: 'Maduro',
    extraPowers: ['Nível Extra', 'Duas Complicações (Idade)'],
    powerIds: [37],
  },
  {
    id: 'velho',
    name: 'Velho',
    mod_str: -1,
    mod_dex: -1,
    mod_con: -1,
    extraPowers: [
      'Dois Níveis Extras',
      'Três Complicações (Idade)',
      'Aumento de Atributo bloqueado para atributos físicos',
    ],
    powerIds: [38],
  },
  {
    id: 'anciao',
    name: 'Ancião',
    mod_str: -2,
    mod_dex: -2,
    mod_con: -2,
    powerIds: [39],
    extraPowers: [
      'Três Níveis Extras',
      'Quatro Complicações (Idade)',
      'Aumento de Atributo bloqueado para atributos físicos',
    ],
  },
];
