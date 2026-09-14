import { Character } from '../../../api.service';

const ARCANISTA_CLASS_ID = 3;

// How many Arcanista class-relative levels this character has — Poder
// Mágico's own scaling ("+1 PM por nível de arcanista"). Tied to one
// specific class rather than a generic attribute/caster concept, so it gets
// its own small file instead of living inline in resolve-effect-sentinels.ts
// (which stays domain-agnostic on purpose), same reasoning as key_attribute
// delegating out to resolve-spell-caster-info.ts.
export function resolveArcanistaLevels(character: Character): number {
  return (character.levels ?? []).filter((level) => level.class_id === ARCANISTA_CLASS_ID).length;
}
