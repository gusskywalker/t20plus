import { DAMAGE_TYPE_COLORS } from '../../constants/translation-constants';

// undefined (no color) falls back to whatever the breakdown line's own CSS
// already sets — only elemental/other damage types are in DAMAGE_TYPE_COLORS
// at all (see its own comment), physical ones always resolve to undefined.
export function damageTypeColor(damageType?: string | null): string | undefined {
  return damageType ? DAMAGE_TYPE_COLORS[damageType] : undefined;
}
