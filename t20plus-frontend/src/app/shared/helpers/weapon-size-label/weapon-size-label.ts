// Weapon Sizes (claude-stuff/rules/weapon-rules.md) — Reduzida -1, Normal 0,
// Aumentada 1, Gigante 2. Shared so item-details-modal's display and
// buy-item-modal's Tamanho dropdown can't drift apart on the label text.
export function weaponSizeLabel(weaponSize: number): string {
  const labels: Record<number, string> = {
    [-1]: 'Tamanho Reduzido',
    [0]: 'Tamanho Normal',
    [1]: 'Tamanho Aumentado',
    [2]: 'Tamanho Gigante',
  };
  return labels[weaponSize] ?? 'Tamanho Normal';
}

/** The 4 weapon sizes as {id, name} items, ready for app-searchable-dropdown. */
export const WEAPON_SIZE_ITEMS = [-1, 0, 1, 2].map((size) => ({ id: size, name: weaponSizeLabel(size) }));
