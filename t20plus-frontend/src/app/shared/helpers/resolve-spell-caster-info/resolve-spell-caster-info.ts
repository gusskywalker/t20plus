import { Character, Power } from '../../../api.service';

export interface SpellCasterInfo {
  classId: number;
  // Current class-relative level in whichever class taught this spell —
  // the PM limit for casting it ("seu limite é o seu nível da classe em
  // que você aprendeu aquela magia," spells-basics.md). Not the level it
  // was learned AT — a level-8 Arcanista's limit for a spell learned at
  // Arcanista level 2 is still 8.
  classLevel: number;
  // Which attribute ('int'/'car'/'knw') governs this spell's CD — read off
  // the same class's own caster power (spell_key_attribute), not assumed
  // from the spell itself.
  keyAttribute: string;
}

/**
 * Traces a known spell_id back to whichever character_levels row granted
 * it, to answer "which class taught this spell" — the PM-limit and CD-
 * attribute rules both key off that class specifically, not the character
 * as a whole (a Arcanista 8/Bardo 3 character's Arcanista-taught spells
 * use Arcanista's own level/attribute, not some blended number). Returns
 * null if the spell isn't actually known (shouldn't happen from the
 * casting modal, which only ever opens for a spell already in the Magias
 * list, but callers should still handle it).
 */
export function resolveSpellCasterInfo(character: Character, spellId: number, powers: Power[]): SpellCasterInfo | null {
  const levelRow = (character.levels ?? []).find((level) => (level.spell_ids ?? []).includes(spellId));
  if (!levelRow) {
    return null;
  }

  const classId = levelRow.class_id;
  const classLevel = (character.levels ?? []).filter((level) => level.class_id === classId).length;

  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const casterPower = powers.find(
    (power) =>
      grantedPowerIds.has(power.id) &&
      (power.effects ?? []).some((effect) => effect.tag === 'spell_key_attribute') &&
      (power.prerequisites ?? []).some((prerequisite) => prerequisite.type === 'class' && (prerequisite.class_ids ?? []).includes(classId)),
  );
  const keyAttribute = String(casterPower?.effects?.find((effect) => effect.tag === 'spell_key_attribute')?.value ?? 'int');

  return { classId, classLevel, keyAttribute };
}
