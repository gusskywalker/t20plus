import { Character, Effect, Power } from '../../../api.service';
import { calculateMaxSpellCircle } from '../calculators/calculate-max-spell-circle/calculate-max-spell-circle';
import { calculateMaxCasterCircle } from '../calculators/calculate-max-caster-circle/calculate-max-caster-circle';
import { matchesSpellAppliesWhen } from '../matches-spell-applies-when/matches-spell-applies-when';
import { resolveTradicaoPerdidaAprimoradaKeyAttribute } from '../calculators/calculate-max-pm/calculate-max-pm-edge-cases/tradicao-perdida';

export interface SpellCasterInfo {
  classId: number;
  // Current class-relative level in whichever class taught this spell —
  // the PM limit for casting it ("seu limite é o seu nível da classe em
  // que você aprendeu aquela magia," spells-basics.md). Not the level it
  // was learned AT — a level-8 Arcanista's limit for a spell learned at
  // Arcanista level 2 is still 8.
  classLevel: number;
  // The level the PM limit is read from: classLevel for a class-taught
  // spell, the character's total level for one that only comes from a power.
  pmLimitLevel: number;
  // Which attribute ('int'/'car'/'knw') governs this spell's CD — read off
  // the same class's own caster power (spell_key_attribute), not assumed
  // from the spell itself.
  keyAttribute: string;
  // Caps the key attribute's value in the CD (Tradição Perdida Aprimorada).
  keyAttributeLimit?: number;
  // Set when the granting power carries spell_circle_as_class for this spell —
  // the círculo reached as that class at the character's total level.
  maxCircleOverride?: number;
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
 * Resolves one active_effect row's own grant_or_reduce_spell_pm_cost_by_1
 * spell_id(s) — a fixed spell_id is used as-is, a null one (an open player
 * choice, e.g. Tatuagem Mística/Canção dos Mares) is filled in order from
 * that SAME row's own custom_effect, mirroring Power::
 * grantedOtherSourceSpellIds()'s own null-slot fill on the backend.
 */
function resolveActiveEffectGrantedSpellIds(power: Power, customEffect: Effect[] | null | undefined): number[] {
  const overrideSpellIds = (customEffect ?? [])
    .filter((effect) => effect.tag === 'grant_or_reduce_spell_pm_cost_by_1' && effect.op === 'grant' && effect.spell_id !== undefined)
    .map((effect) => effect.spell_id as number);
  let overrideIndex = 0;

  return (power.effects ?? [])
    .filter((effect) => effect.tag === 'grant_or_reduce_spell_pm_cost_by_1' && effect.op === 'grant')
    .map((effect) => effect.spell_id ?? overrideSpellIds[overrideIndex++])
    .filter((spellId): spellId is number => spellId !== undefined && spellId !== null);
}

/**
 * Which granted power's own grant_or_reduce_spell_pm_cost_by_1 effect
 * actually put spellId into other_source_spell_ids — re-derived from
 * active_effects rather than tracked separately anywhere (character_levels
 * only stores the flat id array, not provenance). Used both for
 * power_granted_spell_key_attribute below and, on the casting modal, to
 * label the double-known -1 PM discount with its real source (e.g.
 * "[-1PM] Tatuagem Mística" instead of an unlabeled generic bonus).
 */
export function resolveOtherSourceGrantingPower(character: Character, spellId: number, powers: Power[]): Power | undefined {
  for (const activeEffect of character.active_effects ?? []) {
    const power = powers.find((p) => p.id === activeEffect.power_id);
    if (power && resolveActiveEffectGrantedSpellIds(power, activeEffect.custom_effect).includes(spellId)) {
      return power;
    }
  }
  return undefined;
}

/**
 * spell_key_attribute_override (e.g. Eiradaan's Magia Instintiva) — an active
 * granted power replaces the class-derived key attribute for every spell its
 * own applies_when matches (spell_types). The first matching one wins.
 */
function resolveSpellKeyAttributeOverride(character: Character, powers: Power[], spellType: string | null): string | undefined {
  for (const activeEffect of character.active_effects ?? []) {
    if (!activeEffect.is_active) {
      continue;
    }
    const power = powers.find((p) => p.id === activeEffect.power_id);
    const override = power?.effects?.find((effect) => effect.tag === 'spell_key_attribute_override' && effect.op === 'set');
    if (power && override && matchesSpellAppliesWhen(power.applies_when, { school: null, type: spellType })) {
      return String(override.value);
    }
  }
  return undefined;
}

/**
 * spell_circle_as_class (e.g. Duende's Enfeitiçar) — the granting power says
 * this one spell (a null spell_id: every spell it granted) reaches the
 * círculos of a given class at the character's total level, whatever class
 * actually carries the spell's level row. A granted power that replaces the
 * granting one (replaces_power) speaks for it too.
 */
function resolveSpellCircleAsClass(character: Character, spellId: number, powers: Power[]): number | undefined {
  const grantingPower = resolveOtherSourceGrantingPower(character, spellId, powers);
  if (!grantingPower) {
    return undefined;
  }
  const grantedPowerIds = new Set((character.active_effects ?? []).map((activeEffect) => activeEffect.power_id));
  const replacingPowers = powers.filter(
    (power) => grantedPowerIds.has(power.id) && (power.effects ?? []).some((effect) => effect.tag === 'replaces_power' && effect.power_id === grantingPower.id),
  );
  const effect = [grantingPower, ...replacingPowers]
    .flatMap((power) => power.effects ?? [])
    .find((candidate) => candidate.tag === 'spell_circle_as_class' && (candidate.spell_id ?? spellId) === spellId);
  return effect?.class_id !== undefined ? calculateMaxSpellCircle(effect.class_id, character.level) : undefined;
}

/**
 * power_granted_spell_key_attribute (e.g. Tatuagem Mística/Canção dos Mares/
 * Luz Sagrada) — a power whose OWN grant of a spell is Carisma-governed
 * regardless of the character's real caster class, whether that spell_id
 * was player-chosen or fixed on the power itself. Deliberately a separate
 * tag from spell_key_attribute: that one is blindly scanned for by
 * resolveCasterKeyAttribute/resolveCasterMaxCircle (character-wide caster
 * lookups, e.g. Familiar (Sapo)'s mod_max_pv) which assume it only ever
 * lives on a real class caster power with its own class prerequisite —
 * reusing it here would make those two silently misresolve for a character
 * who is both, say, a Qareen and a Mago. This is a scoped-to-one-spell
 * lookup instead, via resolveOtherSourceGrantingPower above.
 */
function resolvePowerGrantedSpellKeyAttribute(character: Character, spellId: number, powers: Power[]): string | undefined {
  const power = resolveOtherSourceGrantingPower(character, spellId, powers);
  const value = power?.effects?.find((effect) => effect.tag === 'power_granted_spell_key_attribute')?.value;
  return value !== undefined ? String(value) : undefined;
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
export function resolveSpellCasterInfo(character: Character, spellId: number, powers: Power[], spellEffects: Effect[] | null = null, spellType: string | null = null): SpellCasterInfo | null {
  // A spell learned through a class (spell_ids) is taught by that row's
  // class. One that only comes from a power (other_source_spell_ids, e.g.
  // Pakk granting Explosão de Chamas) has no teaching class: the row it
  // landed on only supplies classId for círculo access and the key-attribute
  // lookup, and its PM limit is the character's total level instead.
  // See CharacterLevelRow.other_source_spell_ids in api.service.ts.
  const levels = character.levels ?? [];
  const classTaughtRow = levels.find((level) => (level.spell_ids ?? []).includes(spellId));
  const levelRow = classTaughtRow ?? levels.find((level) => (level.other_source_spell_ids ?? []).includes(spellId));
  if (!levelRow) {
    return null;
  }

  const classId = levelRow.class_id;
  const classLevel = levels.filter((level) => level.class_id === classId).length;
  const pmLimitLevel = classTaughtRow ? classLevel : character.level;
  // A spell that only comes from a power belongs to the character, not to
  // the class row it landed on: it reaches the best círculo any of the
  // character's caster classes reaches (0 for a pure Guerreiro), or the
  // spell_circle_as_class círculo when that's higher.
  const asClassCircle = resolveSpellCircleAsClass(character, spellId, powers);
  const maxCircleOverride = classTaughtRow
    ? asClassCircle
    : Math.max(
        asClassCircle ?? 0,
        calculateMaxCasterCircle(
          (character.active_effects ?? []).map((effect) => effect.power_id),
          (casterClassId) => levels.filter((level) => level.class_id === casterClassId).length,
          powers,
        ),
      );

  const ownKeyAttribute = (spellEffects ?? []).find((effect) => effect.tag === 'spell_key_attribute')?.value;
  if (ownKeyAttribute !== undefined) {
    return { classId, classLevel, pmLimitLevel, keyAttribute: String(ownKeyAttribute), maxCircleOverride };
  }

  const powerGrantedKeyAttribute = resolvePowerGrantedSpellKeyAttribute(character, spellId, powers);
  if (powerGrantedKeyAttribute !== undefined) {
    return { classId, classLevel, pmLimitLevel, keyAttribute: powerGrantedKeyAttribute, maxCircleOverride };
  }

  const grantedPowerIds = new Set((character.active_effects ?? []).map((effect) => effect.power_id));
  const casterPower = powers.find(
    (power) =>
      grantedPowerIds.has(power.id) &&
      (power.effects ?? []).some((effect) => effect.tag === 'spell_key_attribute') &&
      (power.prerequisites ?? []).some((prerequisite) => prerequisite.type === 'class' && (prerequisite.class_ids ?? []).includes(classId)),
  );
  const spellKeyAttributeOverride = resolveSpellKeyAttributeOverride(character, powers, spellType);
  const tradicaoPerdidaAprimorada = spellKeyAttributeOverride === undefined ? resolveTradicaoPerdidaAprimoradaKeyAttribute(character, powers, classId) : undefined;
  const keyAttribute = spellKeyAttributeOverride ?? tradicaoPerdidaAprimorada?.attribute ?? String(casterPower?.effects?.find((effect) => effect.tag === 'spell_key_attribute')?.value ?? 'int');

  return { classId, classLevel, pmLimitLevel, keyAttribute, keyAttributeLimit: tradicaoPerdidaAprimorada?.limit, maxCircleOverride };
}
