import { WEAPON_SIZE_LABELS } from '../../constants/translation-constants';

// Shared so item-details-modal's display and buy-item-modal's Tamanho
// dropdown can't drift apart on the label text.
export function weaponSizeLabel(weaponSize: number): string {
  return WEAPON_SIZE_LABELS[weaponSize] ?? 'Tamanho Normal';
}

/** The 4 weapon sizes as {id, name} items, ready for app-searchable-dropdown. */
export const WEAPON_SIZE_ITEMS = [-1, 0, 1, 2].map((size) => ({ id: size, name: weaponSizeLabel(size) }));
