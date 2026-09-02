import { Component, computed, effect, inject } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { TextInput } from '../../../shared/inputs/text-input/text-input';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { TormentaDivider } from '../../../shared/tormenta-divider/tormenta-divider';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import {
  buildShopItems,
  calculateRemainingTibares,
  growPurchaseSlots,
  NENHUM_SHOP_ITEM,
  shopItemNameColor,
  shopItemPrice,
} from '../../../shared/helpers/buy-item/buy-item';
import { CharacterDraft } from '../character-draft';

// Prepended so each free-item dropdown can be explicitly left on "none"
// rather than forced to take the free item — arma/armadura are feminine,
// escudo is masculine.
const NENHUMA = { id: null, name: 'Nenhuma' };
const NENHUM = { id: null, name: 'Nenhum' };

const PROFICIENCIA_ARMAS_MARCIAIS = 40;
const PROFICIENCIA_ARMADURAS_PESADAS = 42;
const PROFICIENCIA_ESCUDOS = 43;

// Uma armadura de couro, couro batido ou gibão de peles, a sua escolha —
// the 3 baseline free-armor options (ids into the armors table). Hardcoded
// on purpose: this is specifically the rule's own fixed choice list, not
// "every light armor in the catalog" (which will grow via Comprar Item).
const STARTING_ARMOR_IDS = [2, 3, 4];
// Se você tiver proficiência com armaduras pesadas, em vez disso pode
// começar com uma brunea.
const BRUNEA_ARMOR_ID = 5;
// Se tiver proficiência com escudos, começa também com um escudo leve.
const ESCUDO_LEVE_ID = 1;

// Dinheiro Inicial by Nível. Level 1 is "4d6" in the sourcebook — hardcoded
// to 14 (the roll's median) rather than actually rolled.
const TIBARES_BY_LEVEL: Record<number, number> = {
  1: 14,
  2: 300,
  3: 600,
  4: 1000,
  5: 2000,
  6: 3000,
  7: 5000,
  8: 7000,
  9: 10000,
  10: 13000,
  11: 19000,
  12: 27000,
  13: 36000,
  14: 49000,
  15: 66000,
  16: 88000,
  17: 110000,
  18: 150000,
  19: 200000,
  20: 260000,
};

@Component({
  selector: 'app-character-creation-step-8',
  imports: [CardHeader, TextInput, SearchableDropdown, TormentaDivider],
  templateUrl: './character-creation-step-8.html',
  styleUrl: './character-creation-step-8.scss',
})
export class CharacterCreationStep8 {
  private staticRegistry = inject(StaticRegistry);
  private draft = inject(CharacterDraft);
  private router = inject(Router);

  constructor() {
    // Clear the martial weapon pick once the character no longer has
    // martial weapon proficiency from any source.
    effect(() => {
      if (!this.hasMartialWeaponProficiency() && this.draft.startingMartialWeaponId() !== null) {
        this.draft.startingMartialWeaponId.set(null);
      }
    });

    // Clear the free armor pick if it's Brunea and heavy armor proficiency
    // goes away (the other 3 options never depend on proficiency, so they
    // never need clearing).
    effect(() => {
      if (
        !this.hasHeavyArmorProficiency() &&
        this.draft.startingArmorId() === BRUNEA_ARMOR_ID
      ) {
        this.draft.startingArmorId.set(null);
      }
    });

    // Clear the shield pick once the character no longer has shield
    // proficiency from any source — same pattern as the martial weapon.
    // Defaults to Nenhum otherwise, same as every other free-item dropdown
    // — no longer auto-granted just because proficiency is true.
    effect(() => {
      if (!this.hasShieldProficiency() && this.draft.startingShieldId() !== null) {
        this.draft.startingShieldId.set(null);
      }
    });

    // Dev convenience: pre-select the first option in every free-item
    // dropdown so this screen doesn't need manual clicking through every
    // test run. Only fires while each field is still empty, so it never
    // overwrites a real pick or fights the clearing effects above.
    // TODO: remove once this stops being useful during development.
    effect(() => {
      const items = this.simpleWeaponItems();
      if (items.length > 0 && this.draft.startingSimpleWeaponId() === null) {
        this.draft.startingSimpleWeaponId.set(items[0].id);
      }
    });
    effect(() => {
      const items = this.martialWeaponItems();
      if (
        this.hasMartialWeaponProficiency() &&
        items.length > 0 &&
        this.draft.startingMartialWeaponId() === null
      ) {
        this.draft.startingMartialWeaponId.set(items[0].id);
      }
    });
    effect(() => {
      const items = this.armorItems();
      if (items.length > 0 && this.draft.startingArmorId() === null) {
        this.draft.startingArmorId.set(items[0].id);
      }
    });

    // Writes the read-only Tibares field's own value through to the draft
    // whenever it changes, so character-payload.ts can just read
    // draft.remainingTibares() at save time instead of redoing this math.
    effect(() => {
      this.draft.remainingTibares.set(this.remainingTibares());
    });
  }

  // Base tibares off the starting table only — origin/etc. bonuses aren't
  // folded in yet, tracked separately once that's built. Keyed by
  // totalLevel (base classIds levels + any age-bracket bonus levels), not
  // the raw draft.baseLevel(), since a Maduro/Velho/Ancião character starts
  // with more levels than they picked in step 1/3.
  private readonly baseTibares = computed(() => TIBARES_BY_LEVEL[this.draft.totalLevel()] ?? 0);

  // Base minus every Comprar Item purchase's price. Can go negative;
  // nothing blocks overspending yet — the Tibares field just paints red
  // (see tibaresInvalid) and unaffordable shop items paint red too (see
  // shopItemNameColorFn/shopItemPriceFn).
  protected readonly remainingTibares = computed(() =>
    calculateRemainingTibares(
      this.baseTibares(),
      this.draft.purchasedItemKeys(),
      this.shopItems(),
    ),
  );

  protected readonly tibaresDisplay = computed(() => this.remainingTibares().toLocaleString('pt-BR'));

  protected readonly tibaresInvalid = computed(() => this.remainingTibares() < 0);

  // Every power id the character currently has, from every source we
  // track on the draft so far — the starting class's own proficiency_ids
  // (checked against the starting class only, same convention as
  // divine_power_picks), origin choice-group grants, god powers, and the
  // two step-7 bonus-power fields. Nothing here reaches into StaticRegistry
  // from CharacterDraft itself — the draft only ever stores the ids the
  // player actually picked, this aggregation lives in whichever step needs
  // it.
  private readonly characterPowerIds = computed<Set<number>>(() => {
    const ids = new Set<number>();

    const startingClassId = this.draft.classIds()[0] ?? null;
    const startingClass = this.staticRegistry.classes.find((c) => c.id === startingClassId);
    (startingClass?.proficiency_ids ?? []).forEach((id) => ids.add(id));

    const origin = this.staticRegistry.origins.find((o) => o.id === this.draft.originId());
    const originGroups = origin?.grants ?? [];
    const originChoices = this.draft.originChoices();
    originGroups.forEach((group, gi) => {
      (originChoices[gi] ?? []).forEach((optionIndex) => {
        const option = group.options[optionIndex];
        if (option?.tag === 'power' && option.op === 'grant' && option.power_id) {
          ids.add(option.power_id);
        }
      });
    });

    this.draft.godPowerIds().forEach((id) => ids.add(id));

    const generalComplicationPowerId = this.draft.generalComplicationPowerId();
    if (generalComplicationPowerId !== null) {
      ids.add(generalComplicationPowerId);
    }

    const adultoPowerId = this.draft.adultoPowerId();
    if (adultoPowerId !== null) {
      ids.add(adultoPowerId);
    }

    return ids;
  });

  protected readonly hasMartialWeaponProficiency = computed(() =>
    this.characterPowerIds().has(PROFICIENCIA_ARMAS_MARCIAIS),
  );

  protected readonly hasHeavyArmorProficiency = computed(() =>
    this.characterPowerIds().has(PROFICIENCIA_ARMADURAS_PESADAS),
  );

  protected readonly hasShieldProficiency = computed(() =>
    this.characterPowerIds().has(PROFICIENCIA_ESCUDOS),
  );

  protected readonly simpleWeaponItems = computed(() => [
    NENHUMA,
    ...this.staticRegistry.weapons.filter((w) => w.proficiency_id === null),
  ]);

  protected readonly martialWeaponItems = computed(() => [
    NENHUMA,
    ...this.staticRegistry.weapons.filter((w) => w.proficiency_id === PROFICIENCIA_ARMAS_MARCIAIS),
  ]);

  protected readonly armorItems = computed(() => {
    const ids = this.hasHeavyArmorProficiency()
      ? [...STARTING_ARMOR_IDS, BRUNEA_ARMOR_ID]
      : STARTING_ARMOR_IDS;
    return [NENHUMA, ...this.staticRegistry.armors.filter((a) => ids.includes(a.id))];
  });

  protected readonly shieldItems = computed(() => [
    NENHUM,
    ...this.staticRegistry.shields.filter((s) => s.id === ESCUDO_LEVE_ID),
  ]);

  protected get draftStartingSimpleWeaponId() {
    return this.draft.startingSimpleWeaponId;
  }

  protected get draftStartingMartialWeaponId() {
    return this.draft.startingMartialWeaponId;
  }

  protected get draftStartingArmorId() {
    return this.draft.startingArmorId;
  }

  protected get draftStartingShieldId() {
    return this.draft.startingShieldId;
  }

  // Every purchasable item across all four catalogs, merged into one list —
  // see shared/helpers/buy-item for the shape and why the id is a
  // synthetic "source:id" string instead of a bare number.
  private readonly shopItems = computed(() =>
    buildShopItems(
      this.staticRegistry.weapons,
      this.staticRegistry.armors,
      this.staticRegistry.shields,
      this.staticRegistry.accessories,
    ),
  );

  // "Nenhum" prepended so each purchase slot can be explicitly left empty
  // (id null) rather than forced to pick something.
  protected readonly shopDropdownItems = computed(() => [NENHUM_SHOP_ITEM, ...this.shopItems()]);

  protected readonly shopItemPriceFn = computed(() => shopItemPrice(this.remainingTibares()));

  protected readonly shopItemNameColorFn = computed(() =>
    shopItemNameColor(this.remainingTibares()),
  );

  protected get draftPurchasedItemKeys() {
    return this.draft.purchasedItemKeys;
  }

  protected purchasedItemKeyAt(index: number): string | null {
    return this.draft.purchasedItemKeys()[index] ?? null;
  }

  // Buying multiples of the same item is normal shopping (two daggers is
  // fine), so unlike the mutually-exclusive picks elsewhere in this app,
  // slots never filter each other's options out. growPurchaseSlots keeps
  // exactly one trailing empty dropdown available after the last real pick.
  protected setPurchasedItemKeyAt(index: number, value: number | string | null): void {
    const current = [...this.draft.purchasedItemKeys()];
    current[index] = (value as string | null) ?? null;
    this.draft.purchasedItemKeys.set(growPurchaseSlots(current));
  }

  back(): void {
    this.router.navigate(['/character-creation-step-7']);
  }

  continue(): void {
    this.router.navigate(['/character-creation-step-9']);
  }
}
