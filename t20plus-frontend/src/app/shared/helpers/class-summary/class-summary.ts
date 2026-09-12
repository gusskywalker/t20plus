import { Character } from '../../../api.service';

// "Gue 2/Bár 3/Caç 6" — first 3 letters of each class name + how many
// character_levels rows belong to it, in the order each class first
// appears (level order), not alphabetical, so it reads the way the
// character was actually built. Abbreviated so a multiclass character
// still fits on one row next to a "Classes" label.
export function classSummary(character: Character): string {
  const counts = new Map<number, { name: string; count: number }>();
  for (const level of character.levels ?? []) {
    const existing = counts.get(level.class_id);
    if (existing) {
      existing.count++;
    } else {
      counts.set(level.class_id, { name: level.character_class?.name ?? '', count: 1 });
    }
  }
  return [...counts.values()].map(({ name, count }) => `${name.slice(0, 3)} ${count}`).join('/');
}
