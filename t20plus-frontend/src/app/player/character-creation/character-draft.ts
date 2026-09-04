import { Injectable, computed, inject, signal } from '@angular/core';
import { StaticRegistry } from '../../shared/hooks/static-registry';
import { AGE_BRACKETS } from '../../shared/constants/age-brackets';
import { CharacterActiveEffectRow } from '../../api.service';

/**
 * In-progress character being built across the creation wizard's steps.
 * Provided at the character-creation route, not root — a fresh instance
 * is created on entering the flow and discarded on leaving it, so there's
 * no stale draft to clean up.
 */
@Injectable()
export class CharacterDraft {
  private staticRegistry = inject(StaticRegistry);
  name = signal('');
  raceId = signal<number | null>(null);
  originId = signal<number | null>(null);
  godId = signal<number | null>(null);
  /** Step 1's raw level pick — the character's base level BEFORE age-bracket bonus levels. See totalLevel for the real final level, and the `level` getter further down for the Character-shape one calculateStatBonus/getActiveEffects read. */
  baseLevel = signal<number | null>(null);
  portraitId = signal<number | null>(null);

  /** Which raceId portraitId currently belongs to — same reasoning as originChoicesOriginId, since a portrait's available set depends on race. */
  portraitIdRaceId = signal<number | null>(null);

  /** One class id per character level, index 0 = level 1 (Classe Inicial). */
  classIds = signal<(number | null)[]>([]);

  baseStr = signal(0);
  baseDex = signal(0);
  baseCon = signal(0);
  baseInt = signal(0);
  baseKnw = signal(0);
  baseCar = signal(0);

  /** Attribute keys ('str', 'dex', ...) chosen for a race's mod_other points. */
  otherAttributes = signal<string[]>([]);

  /** Step 4: per origin choice-group (index-aligned), indices into that group's options[] that were picked. */
  originChoices = signal<number[][]>([]);

  /** Which originId originChoices currently belongs to — lets step 4 tell "origin changed, stale choices" apart from "same origin, re-rendering," even across remounting the step 4 component on navigation. */
  originChoicesOriginId = signal<number | null>(null);

  /** Step 5: power ids picked from the chosen god's divine_granted powers. */
  godPowerIds = signal<number[]>([]);

  /** Which godId godPowerIds currently belongs to — same reasoning as originChoicesOriginId. */
  godPowerIdsGodId = signal<number | null>(null);

  /** Step 6: per class skill-group (index-aligned), skill ids picked (options are already skill ids, unlike origin's GrantOption). */
  classSkillChoices = signal<number[][]>([]);

  /** Which race/class/origin/god combo classSkillChoices currently belongs to — same reasoning as originChoicesOriginId, but keyed on all four since each can change what's already trained (race/origin/god) or what the groups even are (class). */
  classSkillChoicesSourceKey = signal<string | null>(null);

  /** Step 7: the chosen general complication, or null for "Nenhuma" — this is the real default, not an unset marker. */
  generalComplicationId = signal<number | null>(null);

  /** Step 7: the bonus Poder Geral picked in exchange for the general complication. Only meaningful while generalComplicationId isn't null. */
  generalComplicationPowerId = signal<number | null>(null);

  /** Step 7: freely-chosen character age (years) — not derived from race/anything else. */
  age = signal<number | null>(null);

  /** Step 7: age bracket (criança/adolescente/jovem/adulto/maduro/velho/ancião) — fixed list, not DB-backed, see AGE_BRACKETS in character-creation-step-7.ts. */
  ageBracket = signal<string | null>(null);

  /**
   * Step 7: Origem em Construção's "unmark 1" pick, storing whichever id(s)
   * the player unchecked — an origin choice-group option index, or a class
   * skill id, depending which case applied (see character-creation-step-7.ts).
   * Empty = no override in effect (does nothing). How this actually strips
   * the pick from originChoices/classSkillChoices at creation time isn't
   * decided yet — this only records the player's intent for now.
   */
  adolescenteOverride = signal<number[]>([]);

  /** Step 7: Adulto's required bonus Poder Geral pick — no "Nenhuma," both this and adultoAgeComplicationId are mandatory whenever ageBracket is 'adulto'. */
  adultoPowerId = signal<number | null>(null);

  /** Step 7: Adulto's required age-typed Complicação pick — see adultoPowerId. */
  adultoAgeComplicationId = signal<number | null>(null);

  /** Step 7: Maduro's required extra-level class pick — separate from classIds (step 3), which is sized to draft.baseLevel(), not level+1. */
  maduroClassId = signal<number | null>(null);

  /** Step 7: Maduro's two required age-typed Complicação picks. */
  maduroAgeComplicationIds = signal<(number | null)[]>([null, null]);

  /** Step 7: Velho's two required extra-level class picks — same reasoning as maduroClassId, just two levels instead of one. */
  velhoClassIds = signal<(number | null)[]>([null, null]);

  /** Step 7: Velho's three required age-typed Complicação picks. */
  velhoAgeComplicationIds = signal<(number | null)[]>([null, null, null]);

  /** Step 7: Ancião's three required extra-level class picks — same reasoning as maduroClassId/velhoClassIds. */
  anciaoClassIds = signal<(number | null)[]>([null, null, null]);

  /** Step 7: Ancião's four required age-typed Complicação picks. */
  anciaoAgeComplicationIds = signal<(number | null)[]>([null, null, null, null]);

  // Whichever age-bracket-granted extra class picks are currently active
  // (Maduro=1, Velho=2, Ancião=3, everything else=0), in the order they
  // were entered — these come AFTER step 3's classIds, never before, since
  // they represent levels gained from aging on top of the base level.
  private readonly ageBracketExtraClassIds = computed<(number | null)[]>(() => {
    switch (this.ageBracket()) {
      case 'maduro':
        return [this.maduroClassId()];
      case 'velho':
        return this.velhoClassIds();
      case 'anciao':
        return this.anciaoClassIds();
      default:
        return [];
    }
  });

  /**
   * The full ordered list of classes across every level the character
   * has — step 3's classIds (index 0 = level 1, in order) followed by
   * whichever age-bracket bonus levels apply. This is the single source
   * of truth for "every class at every level," kept as a derived read
   * rather than merged back into classIds itself, so step 3's own data
   * stays exactly what step 3 wrote.
   */
  readonly orderedClassIds = computed<(number | null)[]>(() => [
    ...this.classIds(),
    ...this.ageBracketExtraClassIds(),
  ]);

  /** Total character level — classIds' base level plus any age-bracket bonus levels. */
  readonly totalLevel = computed(() => this.orderedClassIds().length);

  /** Step 8: starting Arma Simples pick — always required. */
  startingSimpleWeaponId = signal<number | null>(null);

  /** Step 8: starting Arma Marcial pick — only required/shown while the character has Proficiência - Armas Marciais from some source. */
  startingMartialWeaponId = signal<number | null>(null);

  /** Step 8: starting free armor pick — always required (arcanist exception not modeled yet, no caster-type data exists). */
  startingArmorId = signal<number | null>(null);

  /** Step 8: starting free Escudo Leve pick — only required/shown while the character has Proficiência - Escudos from some source; pre-picked since it's the only option. */
  startingShieldId = signal<number | null>(null);

  /**
   * Step 8: Comprar Item picks — one entry per purchase slot, always with
   * one trailing null so there's an empty dropdown ready for the next
   * purchase (see shared/helpers/buy-item's growPurchaseSlots). Each
   * entry is a synthetic "source:id" string (e.g. "weapon:2"), not a bare
   * number, since weapons/armors/shields/accessories each have their own
   * independent id sequence and a plain numeric id would collide across
   * catalogs — parseShopItemKey resolves one back.
   */
  purchasedItemKeys = signal<(string | null)[]>([null]);

  /** Step 8: the read-only Tibares field's own computed value, written through by step 8 whenever it changes — the character's actual gold at creation, so nothing downstream (character-payload.ts) needs to redo the base-tibares-minus-purchases math itself. */
  remainingTibares = signal(0);

  /**
   * Step 9: chosen class-pool power id per entry of orderedClassIds
   * (index-aligned — same index means same level). Only meaningful at
   * indices where that class has already had at least one prior level
   * (class-relative level 2+, see character-creation-step-9.ts) — every
   * other index stays null since that level never offers a choice.
   */
  classPowerIds = signal<(number | null)[]>([]);

  /** Serialized orderedClassIds this classPowerIds array was built against — same stale-reset reasoning as originChoicesOriginId, but keyed on the whole ordered list since inserting/removing a level anywhere shifts every later index's meaning. */
  classPowerIdsSourceKey = signal<string | null>(null);

  /**
   * Every power id currently on the draft, from every source at once —
   * origin grants, god powers, the general/adulto bonus power picks,
   * the starting class's automatic proficiency_ids, the age bracket's
   * powerIds, every picked complication's power_ids, every level-up pick in
   * classPowerIds, and every class_granted power the character's class
   * levels already qualify for (e.g. Ataque Especial). Doesn't distinguish
   * where a given id came from — every power-picking dropdown just needs "is this id already on the
   * character," so that's all this tracks. Each dropdown's own items list
   * still needs to add its own current pick back in (this set can't tell
   * "granted elsewhere" from "this is what I myself just picked").
   */
  readonly grantedPowerIds = computed<Set<number>>(() => {
    const ids = new Set<number>();

    const origin = this.staticRegistry.origins.find((o) => o.id === this.originId());
    const originGroups = origin?.grants ?? [];
    const originChoices = this.originChoices();
    originGroups.forEach((group, groupIndex) => {
      (originChoices[groupIndex] ?? []).forEach((optionIndex) => {
        const option = group.options[optionIndex];
        if (option?.tag === 'power' && option.power_id !== undefined) {
          ids.add(option.power_id);
        }
      });
    });

    this.godPowerIds().forEach((id) => ids.add(id));

    const generalComplicationPowerId = this.generalComplicationPowerId();
    if (generalComplicationPowerId !== null) {
      ids.add(generalComplicationPowerId);
    }
    const adultoPowerId = this.adultoPowerId();
    if (adultoPowerId !== null) {
      ids.add(adultoPowerId);
    }

    const startingClass = this.staticRegistry.classes.find((c) => c.id === this.classIds()[0]);
    (startingClass?.proficiency_ids ?? []).forEach((id) => ids.add(id));

    const ageBracket = AGE_BRACKETS.find((b) => b.id === this.ageBracket());
    (ageBracket?.powerIds ?? []).forEach((id) => ids.add(id));

    const complicationIds = [
      this.generalComplicationId(),
      this.adultoAgeComplicationId(),
      ...this.maduroAgeComplicationIds(),
      ...this.velhoAgeComplicationIds(),
      ...this.anciaoAgeComplicationIds(),
    ].filter((id): id is number => id !== null);
    complicationIds.forEach((complicationId) => {
      const complication = this.staticRegistry.complications.find((c) => c.id === complicationId);
      (complication?.power_ids ?? []).forEach((id) => ids.add(id));
    });

    this.classPowerIds().forEach((id) => {
      if (id !== null) {
        ids.add(id);
      }
    });

    // class_granted powers (e.g. Ataque Especial) — automatic, no player
    // choice involved, so they're never in classPowerIds. Granted the
    // moment a class-relative level reaches the power's own 'class'-type
    // prerequisite (class_ids is an OR list, min_level is that class's own
    // relative level — same reasoning as step 9's typeMatches). Uses the
    // FINAL class-relative level per class (not per-row), since every tier
    // up to the character's current level is already granted, not just the
    // one matching this exact level.
    const classLevelCounts = new Map<number, number>();
    this.orderedClassIds().forEach((classId) => {
      if (classId === null) {
        return;
      }
      classLevelCounts.set(classId, (classLevelCounts.get(classId) ?? 0) + 1);
    });
    this.staticRegistry.powers.forEach((power) => {
      if (power.source !== 'class_granted') {
        return;
      }
      const qualifies = (power.prerequisites ?? []).some(
        (prerequisite) =>
          prerequisite.type === 'class' &&
          (prerequisite.class_ids ?? []).some((classId) => (classLevelCounts.get(classId) ?? 0) >= (prerequisite.min_level ?? 0)),
      );
      if (qualifies) {
        ids.add(power.id);
      }
    });

    return ids;
  });

  // Character-creation-time character-modifier prerequisite checks — a
  // power's { type: 'attribute', attribute, min } prerequisite (e.g.
  // Esquiva's Des 1) needs to be checked against the draft's CURRENT stats,
  // including whatever Aumento de Atributo picks are already sitting in
  // classPowerIds — not just the raw base_* wizard input. Rather than
  // building a separate draft-only stat resolver, these getters make
  // CharacterDraft itself satisfy StatBonusSource (see
  // shared/helpers/calculate-stat-bonus.ts), the same narrowed shape a real
  // Character already satisfies, so calculateStatBonus/getActiveEffects can
  // be called with `this` directly from the wizard steps. Every other field
  // real Characters have (id, portrait, inventory...) doesn't exist here on
  // purpose — nothing in calculateStatBonus/getActiveEffects touches them.
  get base_str(): number {
    return this.effectiveBase('str');
  }
  get base_dex(): number {
    return this.effectiveBase('dex');
  }
  get base_con(): number {
    return this.effectiveBase('con');
  }
  get base_int(): number {
    return this.effectiveBase('int');
  }
  get base_knw(): number {
    return this.effectiveBase('knw');
  }
  get base_car(): number {
    return this.effectiveBase('car');
  }

  // Mirrors character-payload.ts's base_* formula exactly (raw wizard input
  // + the "other" bonus point + the race's own fixed mod) — this is what a
  // real Character's base_* ends up holding once creation actually runs.
  private effectiveBase(attribute: string): number {
    const rawValues: Record<string, number> = {
      str: this.baseStr(),
      dex: this.baseDex(),
      con: this.baseCon(),
      int: this.baseInt(),
      knw: this.baseKnw(),
      car: this.baseCar(),
    };
    const race = this.staticRegistry.races.find((r) => r.id === this.raceId());
    const raceMods: Record<string, number | undefined> = {
      str: race?.mod_str,
      dex: race?.mod_dex,
      con: race?.mod_con,
      int: race?.mod_int,
      knw: race?.mod_knw,
      car: race?.mod_car,
    };
    const other = this.otherAttributes().includes(attribute) ? 1 : 0;
    return (rawValues[attribute] ?? 0) + other + (raceMods[attribute] ?? 0);
  }

  // getActiveEffects only ever reads .power_id off these — id/character_id
  // are meaningless placeholders, this draft was never persisted.
  get active_effects(): CharacterActiveEffectRow[] {
    return [...this.grantedPowerIds()].map((power_id) => {
      const power = this.staticRegistry.powers.find((p) => p.id === power_id);
      return { id: 0, character_id: 0, power_id, is_active: power?.usability === 'passive' };
    });
  }

  get level(): number {
    return this.totalLevel();
  }

  /**
   * Puts every signal back to its starting value. Not strictly needed —
   * this service is provided at the character-creation route (see the
   * class comment), so leaving the route already destroys the instance —
   * but step 9 calls this explicitly right after a successful save, so
   * the draft can't accidentally read as "in progress" for the instant
   * before navigation actually tears the injector down.
   */
  reset(): void {
    this.name.set('');
    this.raceId.set(null);
    this.originId.set(null);
    this.godId.set(null);
    this.baseLevel.set(null);
    this.portraitId.set(null);
    this.portraitIdRaceId.set(null);
    this.classIds.set([]);
    this.baseStr.set(0);
    this.baseDex.set(0);
    this.baseCon.set(0);
    this.baseInt.set(0);
    this.baseKnw.set(0);
    this.baseCar.set(0);
    this.otherAttributes.set([]);
    this.originChoices.set([]);
    this.originChoicesOriginId.set(null);
    this.godPowerIds.set([]);
    this.godPowerIdsGodId.set(null);
    this.classSkillChoices.set([]);
    this.classSkillChoicesSourceKey.set(null);
    this.generalComplicationId.set(null);
    this.generalComplicationPowerId.set(null);
    this.age.set(null);
    this.ageBracket.set(null);
    this.adolescenteOverride.set([]);
    this.adultoPowerId.set(null);
    this.adultoAgeComplicationId.set(null);
    this.maduroClassId.set(null);
    this.maduroAgeComplicationIds.set([null, null]);
    this.velhoClassIds.set([null, null]);
    this.velhoAgeComplicationIds.set([null, null, null]);
    this.anciaoClassIds.set([null, null, null]);
    this.anciaoAgeComplicationIds.set([null, null, null, null]);
    this.startingSimpleWeaponId.set(null);
    this.startingMartialWeaponId.set(null);
    this.startingArmorId.set(null);
    this.startingShieldId.set(null);
    this.purchasedItemKeys.set([null]);
    this.remainingTibares.set(0);
    this.classPowerIds.set([]);
    this.classPowerIdsSourceKey.set(null);
  }
}
