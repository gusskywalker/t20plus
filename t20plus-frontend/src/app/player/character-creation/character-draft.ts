import { Injectable, computed, signal } from '@angular/core';

/**
 * In-progress character being built across the creation wizard's steps.
 * Provided at the character-creation route, not root — a fresh instance
 * is created on entering the flow and discarded on leaving it, so there's
 * no stale draft to clean up.
 */
@Injectable()
export class CharacterDraft {
  name = signal('');
  raceId = signal<number | null>(null);
  originId = signal<number | null>(null);
  godId = signal<number | null>(null);
  level = signal<number | null>(null);
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

  /** Step 7: Maduro's required extra-level class pick — separate from classIds (step 3), which is sized to draft.level(), not level+1. */
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
}
