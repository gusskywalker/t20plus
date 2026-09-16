// Union of weapons.damage_type (physical) and spells.damage_type
// (elemental/other) — one shared lookup since both surface in the same
// kind of breakdown/label text.
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
