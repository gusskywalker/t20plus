import { Character, Effect, Power } from '../../../api.service';
import { calculateMaxSpellCircle } from '../calculators/calculate-max-spell-circle/calculate-max-spell-circle';

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
 * The character's own spell key attribute code ('int'/'car'/'knw'), read
 * off whichever granted power carries `spell_key_attribute` (Bruxo/
 * Feiticeiro/Mago) — character-wide, not tied to casting one particular
 * spell, unlike resolveSpellCasterInfo below (which needs a specific class
 * disambiguated by spellId, for a multi-caster character casting different
 * classes' spells). Used for a character-wide override like Familiar
 * (Rato)'s skill_attribute pick, or resolve-effect-sentinels.ts's own
 * 'key_attribute' sentinel (e.g. Familiar (Sapo)'s mod_max_pv).
 */
export function resolveCasterKeyAttribute(character: Character, powers: Power[]): string {
  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const casterPower = powers.find((power) => grantedPowerIds.has(power.id) && (power.effects ?? []).some((effect) => effect.tag === 'spell_key_attribute'));
  return String(casterPower?.effects?.find((effect) => effect.tag === 'spell_key_attribute')?.value ?? 'int');
}

/**
 * The character's own highest currently-accessible spell círculo,
 * character-wide same as resolveCasterKeyAttribute above (not tied to
 * casting one particular spell) — finds the granted caster power's own
 * class, then reuses calculateMaxSpellCircle for that class's current
 * level. Used by applies_when.caster_min_circle (e.g. Fortalecimento
 * Arcano's own +1 stacking to +2 once circle 4 is reachable).
 */
export function resolveCasterMaxCircle(character: Character, powers: Power[]): number {
  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const casterPower = powers.find((power) => grantedPowerIds.has(power.id) && (power.effects ?? []).some((effect) => effect.tag === 'spell_key_attribute'));
  const classId = casterPower?.prerequisites?.find((prerequisite) => prerequisite.type === 'class')?.class_ids?.[0];
  if (classId === undefined) {
    return 0;
  }
  const classLevel = (character.levels ?? []).filter((level) => level.class_id === classId).length;
  return calculateMaxSpellCircle(classId, classLevel);
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
 *
 * `spellEffects` (the spell's own `effects`) lets the SPELL declare its own
 * fixed `spell_key_attribute` (e.g. Olhar Atordoante: CD Car regardless of
 * caster) — checked before the class-based lookup below, since a power
 * granted with no real class (ManagesPowers lands it on the character's
 * FIRST level, whatever class that is) would otherwise resolve against an
 * unrelated class with no caster power at all. classId/classLevel still
 * come from that landing level either way (pmLimit/casterMaxCircle), which
 * is harmless for a spell with no enhancements to cap.
 */
export function resolveSpellCasterInfo(character: Character, spellId: number, powers: Power[], spellEffects: Effect[] | null = null): SpellCasterInfo | null {
  // other_source_spell_ids (e.g. Pakk granting Explosão de Chamas) is
  // treated exactly like spell_ids here — whichever level row carries the
  // id, in either array, is "which class taught this spell." See
  // CharacterLevelRow.other_source_spell_ids in api.service.ts.
  const levelRow = (character.levels ?? []).find(
    (level) => (level.spell_ids ?? []).includes(spellId) || (level.other_source_spell_ids ?? []).includes(spellId),
  );
  if (!levelRow) {
    return null;
  }

  const classId = levelRow.class_id;
  const classLevel = (character.levels ?? []).filter((level) => level.class_id === classId).length;

  const ownKeyAttribute = (spellEffects ?? []).find((effect) => effect.tag === 'spell_key_attribute')?.value;
  if (ownKeyAttribute !== undefined) {
    return { classId, classLevel, keyAttribute: String(ownKeyAttribute) };
  }

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
