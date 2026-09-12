import { Component, effect, inject, input, signal } from '@angular/core';
import { Router } from '@angular/router';
import { AttackModal } from '../../../shared/modals/attack-modal/attack-modal';
import { BuyItemModal } from '../../../shared/modals/buy-item-modal/buy-item-modal';
import { GolpePessoalModal } from '../../../shared/modals/golpe-pessoal-modal/golpe-pessoal-modal';
import { JoinCampaignModal } from '../../../shared/modals/join-campaign-modal/join-campaign-modal';
import { LevelChangeModal } from '../../../shared/modals/level-change-modal/level-change-modal';
import { ImproveItemModal } from '../../../shared/modals/improve-item-modal/improve-item-modal';
import { ItemDetailsModal, SelectedItem } from '../../../shared/modals/item-details-modal/item-details-modal';
import { PowerDetailsModal, SelectedPower } from '../../../shared/modals/power-details-modal/power-details-modal';
import { SkillRollModal } from '../../../shared/modals/skill-roll-modal/skill-roll-modal';
import { SpellCastingModal } from '../../../shared/modals/spell-casting-modal/spell-casting-modal';
import { SelectedActiveSpellEffect, SpellActiveEffectsDetailsModal } from '../../../shared/modals/spell-active-effects-details-modal/spell-active-effects-details-modal';
import { CardHeader } from '../../../shared/card-header/card-header';
import { Modal } from '../../../shared/modals/modal/modal';
import { NumberInput } from '../../../shared/inputs/number-input/number-input';
import { SearchableDropdown } from '../../../shared/inputs/searchable-dropdown/searchable-dropdown';
import { TextInput } from '../../../shared/inputs/text-input/text-input';
import { UseCharacter } from '../../../shared/hooks/use-character';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import {
  Accessory,
  ApiService,
  Armor,
  Character,
  CharacterActiveEffectRow,
  CharacterActiveSpellEffectRow,
  CharacterInventoryRow,
  GeneralItem,
  Power,
  Shield,
  Skill,
  Spell,
  Weapon,
} from '../../../api.service';
import { calculateMaxPv } from '../../../shared/helpers/calculators/calculate-max-pv/calculate-max-pv';
import { matchesPowerReqs } from '../../../shared/helpers/matches-power-reqs/matches-power-reqs';
import { calculateMaxPm } from '../../../shared/helpers/calculators/calculate-max-pm/calculate-max-pm';
import { calculateMaxSlots } from '../../../shared/helpers/max-slots/max-slots';
import { calculateAmmoSlots } from '../../../shared/helpers/calculators/calculate-ammo-slots/calculate-ammo-slots';
import { calculateDefense } from '../../../shared/helpers/calculators/calculate-defense/calculate-defense';
import { calculateStatBonus } from '../../../shared/helpers/calculators/calculate-stat-bonus/calculate-stat-bonus';
import { calculateSkillBonus } from '../../../shared/helpers/calculators/calculate-skill-bonus/calculate-skill-bonus';
import { replaceTormenta0ToO } from '../../../shared/helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { classSummary } from '../../../shared/helpers/class-summary/class-summary';
import { environment } from '../../../../environments/environment';
import { initNewCharacter } from './init-new-character/init-new-character';

// Cumulative XP required to REACH each level (Nível de Personagem table,
// claude-stuff/rules/levels-and-experience.md) — not a formula, the
// per-level jumps aren't regular (e.g. level 6→7 needs +6.000, but 7→8
// only +7.000 while the skill-bonus column skips a step), so this has to
// stay a lookup.
const XP_BY_LEVEL: Record<number, number> = {
  1: 0,
  2: 1000,
  3: 3000,
  4: 6000,
  5: 10000,
  6: 15000,
  7: 21000,
  8: 28000,
  9: 36000,
  10: 45000,
  11: 55000,
  12: 66000,
  13: 78000,
  14: 91000,
  15: 105000,
  16: 120000,
  17: 136000,
  18: 153000,
  19: 171000,
  20: 190000,
};

@Component({
  selector: 'app-character-main',
  imports: [
    AttackModal,
    BuyItemModal,
    CardHeader,
    GolpePessoalModal,
    ImproveItemModal,
    ItemDetailsModal,
    JoinCampaignModal,
    LevelChangeModal,
    Modal,
    NumberInput,
    PowerDetailsModal,
    SearchableDropdown,
    SkillRollModal,
    SpellActiveEffectsDetailsModal,
    SpellCastingModal,
    TextInput,
  ],
  templateUrl: './character-main.html',
  styleUrl: './character-main.scss',
})
export class CharacterMain {
  private useCharacter = inject(UseCharacter);
  private apiService = inject(ApiService);
  private staticRegistry = inject(StaticRegistry);
  private router = inject(Router);

  // Bound straight from the :id route segment — see withComponentInputBinding() in app.config.ts.
  id = input.required<string>();

  protected readonly characterQuery = this.useCharacter.characterQuery(this.id);

  constructor() {
    // First time the sheet loads a character whose current_pv/current_pm
    // were never initialized (null, not 0 — see the characters migration
    // comment), initNewCharacter sets their starting PV/PM and auto-equips
    // their starting gear. Only fires once per character: after that PATCH
    // resolves, both fields are no longer null, so this effect's own guard
    // stops it from firing again.
    effect(() => {
      const character = this.characterQuery.data();
      if (!character || (character.current_pv !== null && character.current_pm !== null)) {
        return;
      }
      initNewCharacter(character, this.id(), this.apiService, this.useCharacter, this.staticRegistry.powers);
    });
  }

  protected portraitUrl(fileName: string): string {
    return `${environment.portraitsBaseUrl}/${fileName}`;
  }

  protected slotsLabel(slots: number): string {
    return slots === 1 ? 'Espaço' : 'Espaços';
  }

  // -1 is the catalog's "not purchasable" sentinel (armors/accessories) —
  // reads oddly as "T$ -1" on the sheet, so it displays as 0 here instead.
  protected displayPrice(price: number): number {
    return price === -1 ? 0 : price;
  }

  // icons.file_name already includes its subdir (e.g. "weapons/weapons_01.webp")
  // — see IconSeeder — so this is just a straight base-url join, same as portraitUrl.
  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  // One row per weapon in the character's inventory, joined against the
  // weapons catalog (for name/slots/price) and icons catalog (for the
  // card's icon).
  protected weaponRows(character: Character): { inventoryRow: CharacterInventoryRow; weapon: Weapon; iconFileName: string | undefined }[] {
    const rows: { inventoryRow: CharacterInventoryRow; weapon: Weapon; iconFileName: string | undefined }[] = [];
    for (const item of character.inventory ?? []) {
      if (item.item_type !== 'weapon') {
        continue;
      }
      const weapon = this.staticRegistry.weapons.find((w) => w.id === item.item_id);
      if (!weapon) {
        continue;
      }
      const iconFileName = weapon.icon_file_name ?? undefined;
      rows.push({ inventoryRow: item, weapon, iconFileName });
    }
    return rows;
  }

  // Same shape as weaponRows, against the shields catalog — shields share
  // weapons' whole hand-equip story (one arm slot, hand-based, worn kept
  // in sync the same way), so they list in the same section, weapons first.
  protected shieldRows(character: Character): { inventoryRow: CharacterInventoryRow; shield: Shield; iconFileName: string | undefined }[] {
    const rows: { inventoryRow: CharacterInventoryRow; shield: Shield; iconFileName: string | undefined }[] = [];
    for (const item of character.inventory ?? []) {
      if (item.item_type !== 'shield') {
        continue;
      }
      const shield = this.staticRegistry.shields.find((s) => s.id === item.item_id);
      if (!shield) {
        continue;
      }
      const iconFileName = shield.icon_file_name ?? undefined;
      rows.push({ inventoryRow: item, shield, iconFileName });
    }
    return rows;
  }

  // Same shape as weaponRows, against the armors catalog instead.
  protected armorRows(character: Character): { inventoryRow: CharacterInventoryRow; armor: Armor; iconFileName: string | undefined }[] {
    const rows: { inventoryRow: CharacterInventoryRow; armor: Armor; iconFileName: string | undefined }[] = [];
    for (const item of character.inventory ?? []) {
      if (item.item_type !== 'armor') {
        continue;
      }
      const armor = this.staticRegistry.armors.find((a) => a.id === item.item_id);
      if (!armor) {
        continue;
      }
      const iconFileName = armor.icon_file_name ?? undefined;
      rows.push({ inventoryRow: item, armor, iconFileName });
    }
    return rows;
  }

  // Same shape again, against the accessories catalog.
  protected accessoryRows(character: Character): { inventoryRow: CharacterInventoryRow; accessory: Accessory; iconFileName: string | undefined }[] {
    const rows: { inventoryRow: CharacterInventoryRow; accessory: Accessory; iconFileName: string | undefined }[] = [];
    for (const item of character.inventory ?? []) {
      if (item.item_type !== 'accessory') {
        continue;
      }
      const accessory = this.staticRegistry.accessories.find((a) => a.id === item.item_id);
      if (!accessory) {
        continue;
      }
      const iconFileName = accessory.icon_file_name ?? undefined;
      rows.push({ inventoryRow: item, accessory, iconFileName });
    }
    return rows;
  }

  // Same shape again, against the general_items catalog — tools/alchemic/
  // food/potion/ammo items bought via Comprar Item or granted at
  // creation. Stackable items (potion/ammo) show "N Restantes"
  // instead of a price — see generalItemPriceLine.
  protected generalItemRows(character: Character): { inventoryRow: CharacterInventoryRow; generalItem: GeneralItem; iconFileName: string | undefined }[] {
    const rows: { inventoryRow: CharacterInventoryRow; generalItem: GeneralItem; iconFileName: string | undefined }[] = [];
    for (const item of character.inventory ?? []) {
      if (item.item_type !== 'general_item') {
        continue;
      }
      const generalItem = this.staticRegistry.generalItems.find((g) => g.id === item.item_id);
      if (!generalItem) {
        continue;
      }
      const iconFileName = generalItem.icon_file_name ?? undefined;
      rows.push({ inventoryRow: item, generalItem, iconFileName });
    }
    return rows;
  }

  // Effective slots for one general_item row — ammo uses the
  // quantity-bucketed rule (calculateAmmoSlots), same as currentSlots()'s
  // own total; every other type just falls through to its flat catalog
  // value, same as weapons/armors/shields/accessories.
  protected generalItemSlots(row: { inventoryRow: CharacterInventoryRow; generalItem: GeneralItem }): number {
    return calculateAmmoSlots(row.generalItem.id, row.inventoryRow.quantity) ?? row.generalItem.slots;
  }

  // Potion/ammo stacks show remaining quantity instead of a price —
  // the price was already paid per-unit at purchase time, quantity is the
  // relevant fact once it's sitting in inventory. Every other general_item
  // type still shows its price, same as weapons/armors/shields/accessories.
  protected generalItemPriceLine(row: { inventoryRow: CharacterInventoryRow; generalItem: GeneralItem }): string {
    if (row.generalItem.type === 'potion' || row.generalItem.type === 'ammo') {
      return `${replaceTormenta0ToO(row.inventoryRow.quantity)} Restantes`;
    }
    return `T$ ${replaceTormenta0ToO(this.displayPrice(row.generalItem.cost))}`;
  }

  // usability values that carry real mechanical weight (pm_cost, effects)
  // — these get their own sub-groups (Ativáveis/
  // Condicionais) inside the one Poderes card. Everything else (passive/
  // roleplay) never gets an interactive resolution, so it sits in the
  // card's third group instead — see activeEffectRows below. All three
  // are presentational sub-groups of the same "these are your powers"
  // concept, not separate features — a player thinks of Vontade de Ferro
  // as a power, full stop, not an "effect" in some other bucket.
  private readonly powerUsabilities = ['active', 'roll_active'];

  // Powers the player never needs to see on their sheet — self-report-only
  // system rows resolved entirely inside another screen (id 262: attack-
  // modal's fired-into-melee checklist). Not a generic flag/tag, just a
  // picked id list — excluded from all three Poderes sub-groups below.
  private readonly hiddenFromPowersListIds = [262];

  private isHiddenFromPowersList(powerId: number): boolean {
    return this.hiddenFromPowersListIds.includes(powerId);
  }

  // Weapons.id 4 — synthetic, not a real owned item (see WeaponSeeder) —
  // same convention as attack-modal.ts's own unarmedWeaponId.
  private readonly unarmedWeaponId = 4;

  // Whichever weapon(s) the character currently has in hand_1/hand_2 —
  // falls back to the synthetic Unarmed weapon for an empty/disabled hand,
  // same as attack-modal.ts's own resolveHandWeapon, so a general_action
  // power gated by applies_when.weapon_purpose (e.g. Mirar: fired/thrown)
  // correctly hides itself while unarmed instead of always showing.
  private equippedWeapons(character: Character): Weapon[] {
    const inventory = character.inventory ?? [];
    const unarmed = this.staticRegistry.weapons.find((w) => w.id === this.unarmedWeaponId);
    return (character.hands ?? [])
      .filter((hand) => hand.enabled)
      .map((hand) => {
        const inventoryId = hand.inventory_ids?.[0];
        const inventoryRow = inventoryId !== undefined ? inventory.find((row) => row.id === inventoryId) : undefined;
        const weapon = inventoryRow && inventoryRow.item_type === 'weapon' ? this.staticRegistry.weapons.find((w) => w.id === inventoryRow.item_id) : undefined;
        return weapon ?? unarmed;
      })
      .filter((weapon): weapon is Weapon => weapon !== undefined);
  }

  // A power with no applies_when of its own always passes (matchesPowerReqs
  // itself already treats null as "always relevant") — this only actually
  // filters anything for a power that names a weapon_grip/weapon_purpose/
  // weapon_ability/weapon_any restriction, checked against ANY currently
  // equipped weapon (not a single "selected" one — there's no attack in
  // progress here, just "do you have a qualifying weapon in hand at all").
  private matchesEquippedWeapon(power: Power, character: Character): boolean {
    if (!power.applies_when) {
      return true;
    }
    return this.equippedWeapons(character).some((weapon) => matchesPowerReqs(power, weapon));
  }

  // Poderes' own "Armas/Escudos" split — Ativáveis (usability: active,
  // standalone, the player just decides to use it, e.g. Medicina) vs.
  // Condicionais (roll_active, only comes up riding a specific roll, e.g.
  // Ataque Especial, Rejeição Divina). Same shape as weaponRows/etc.
  // above, but keyed off power_id instead of item_id since active effects
  // aren't inventory items.
  protected favoritePowerRows(character: Character): { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] {
    const rows: { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] = [];
    for (const effect of character.active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power || !effect.is_favorite || power.usability === 'vessel' || this.isHiddenFromPowersList(power.id) || !this.matchesEquippedWeapon(power, character)) {
        continue;
      }
      const iconFileName = power.icon_file_name ?? undefined;
      rows.push({ effect, power, iconFileName });
    }
    return rows.sort((a, b) => a.power.name.localeCompare(b.power.name, 'pt-BR'));
  }

  protected activablePowerRows(character: Character): { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] {
    const rows: { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] = [];
    for (const effect of character.active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power || power.usability !== 'active' || effect.is_favorite || this.isHiddenFromPowersList(power.id) || !this.matchesEquippedWeapon(power, character)) {
        continue;
      }
      const iconFileName = power.icon_file_name ?? undefined;
      rows.push({ effect, power, iconFileName });
    }
    return rows.sort((a, b) => a.power.name.localeCompare(b.power.name, 'pt-BR'));
  }

  // Same shape as activablePowerRows, for roll_active.
  protected conditionalPowerRows(character: Character): { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] {
    const rows: { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] = [];
    for (const effect of character.active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power || power.usability !== 'roll_active' || effect.is_favorite || this.isHiddenFromPowersList(power.id) || !this.matchesEquippedWeapon(power, character)) {
        continue;
      }
      const iconFileName = power.icon_file_name ?? undefined;
      rows.push({ effect, power, iconFileName });
    }
    return rows.sort((a, b) => a.power.name.localeCompare(b.power.name, 'pt-BR'));
  }

  // Poderes' third sub-group — everything NOT in powerUsabilities
  // (passive/roleplay), the ones that just sit there with nothing to
  // interact with. Same shape/modal/remove-flow as the other two groups
  // (openPowerModal etc.) — it's a third list inside the same card, not a
  // separate section anymore.
  protected activeEffectRows(character: Character): { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] {
    const rows: { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] = [];
    for (const effect of character.active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      // vessel powers (Escaramuça, Espreitar, ...) carry no effect of
      // their own — they exist only to grant power_granted children, so
      // they never show up in any Poderes group, not even this catch-all.
      if (
        !power ||
        this.powerUsabilities.includes(power.usability) ||
        power.usability === 'vessel' ||
        effect.is_favorite ||
        this.isHiddenFromPowersList(power.id)
      ) {
        continue;
      }
      const iconFileName = power.icon_file_name ?? undefined;
      rows.push({ effect, power, iconFileName });
    }
    return rows.sort((a, b) => a.power.name.localeCompare(b.power.name, 'pt-BR'));
  }

  // XP needed to reach the NEXT level — the "z" in "XP y/z". Level 20 is
  // the cap (no level 21 row), so it just holds at its own threshold.
  protected xpForNextLevel(level: number): number {
    return XP_BY_LEVEL[level + 1] ?? XP_BY_LEVEL[20];
  }

  protected readonly classSummary = classSummary;

  // base_* is what character-payload.ts wrote at creation (race's fixed
  // mod_* + the "other" bonus point already baked in) — calculateStatBonus
  // adds whatever's live on top of that from active powers (e.g. Aumento
  // de Atributo).
  protected attributeRows(character: Character): { label: string; value: number }[] {
    return [
      { label: 'Força', value: calculateStatBonus(character, 'str', this.staticRegistry.powers) },
      { label: 'Destreza', value: calculateStatBonus(character, 'dex', this.staticRegistry.powers) },
      { label: 'Constituição', value: calculateStatBonus(character, 'con', this.staticRegistry.powers) },
      { label: 'Inteligência', value: calculateStatBonus(character, 'int', this.staticRegistry.powers) },
      { label: 'Sabedoria', value: calculateStatBonus(character, 'knw', this.staticRegistry.powers) },
      { label: 'Carisma', value: calculateStatBonus(character, 'car', this.staticRegistry.powers) },
    ];
  }

  protected characterDefense(character: Character): number {
    return calculateDefense(character, this.staticRegistry.armors, this.staticRegistry.shields, this.staticRegistry.powers);
  }

  // Every skill in the catalog, in seed order (canon order, not sorted).
  // The two icons are purely informative about the SKILL itself (can it
  // only be used if trained, does armor penalty apply to it at all) — not
  // about whether this particular character happens to be trained or is
  // currently wearing penalized gear. They always show for a matching
  // skill, e.g. Ladinagem always gets both regardless of who's viewing it.
  protected readonly skillSearch = signal('');

  protected skillRows(character: Character): { skill: Skill; bonus: number; characterIsTrained: boolean }[] {
    const search = this.skillSearch().trim().toLowerCase();
    return this.staticRegistry.skills
      .filter((skill) => skill.name.toLowerCase().includes(search))
      .map((skill) => ({
        skill,
        bonus: calculateSkillBonus(
          character,
          skill,
          this.staticRegistry.armors,
          this.staticRegistry.shields,
          this.staticRegistry.accessories,
          this.staticRegistry.generalItems,
          this.staticRegistry.itemImprovements,
          this.staticRegistry.itemEnchantments,
          this.staticRegistry.powers,
        ),
        characterIsTrained: character.trained_skill_ids?.includes(skill.id) ?? false,
      }));
  }

  protected maxPv(character: Character): number {
    return calculateMaxPv(character, this.staticRegistry.powers);
  }

  protected maxPm(character: Character): number {
    return calculateMaxPm(character, this.staticRegistry.powers);
  }

  protected readonly maxSlots = calculateMaxSlots;
  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  // Sum of each inventory item's own slots field times its quantity,
  // looked up in its catalog by item_type/item_id — quantity is always 1
  // for weapons/armors/shields/accessories (one row per physical instance)
  // so this only actually matters for stacked general_items. Tibares
  // contribute 0 for now — TODO implement different tibar coins and other
  // universalized weights; only slots declared on actual equipment count
  // today.
  protected currentSlots(inventory: CharacterInventoryRow[] | undefined): number {
    if (!inventory) {
      return 0;
    }
    return inventory.reduce((total, item) => {
      // Ammo doesn't scale linearly with quantity (a stack's space
      // shrinks in bucketed steps as it's spent, not per-arrow) — see
      // calculate-ammo-slots.ts. Only general_items can be a special-cased
      // ammo id, so every other item_type always falls through to the
      // generic multiply below.
      if (item.item_type === 'general_item') {
        const ammoSlots = calculateAmmoSlots(item.item_id, item.quantity);
        if (ammoSlots !== null) {
          return total + ammoSlots;
        }
      }
      const catalog =
        item.item_type === 'weapon'
          ? this.staticRegistry.weapons
          : item.item_type === 'armor'
            ? this.staticRegistry.armors
            : item.item_type === 'shield'
              ? this.staticRegistry.shields
              : item.item_type === 'accessory'
                ? this.staticRegistry.accessories
                : this.staticRegistry.generalItems;
      const entry = catalog.find((catalogItem) => catalogItem.id === item.item_id);
      return total + (entry?.slots ?? 0) * item.quantity;
    }, 0);
  }

  // Vida/Mana editing — Adicionar/Remover a delta, same treatment as
  // Tibares (see tibaresMode) — draftPv/draftPm start empty each open,
  // since it's an amount being added/removed, not the current total.
  // modeDefaults to 'add' on open and is flipped by a toggle button inside
  // the modal itself, not by which button opened it.
  protected readonly showPvModal = signal(false);
  protected readonly pvMode = signal<'add' | 'remove'>('add');
  protected readonly draftPv = signal<number | null>(null);

  protected openPvModal(): void {
    this.pvMode.set('add');
    this.draftPv.set(null);
    this.showPvModal.set(true);
  }

  protected togglePvMode(): void {
    this.pvMode.set(this.pvMode() === 'add' ? 'remove' : 'add');
  }

  protected confirmPv(character: Character): void {
    const delta = this.draftPv();
    if (delta === null) {
      return;
    }
    const current_pv = (character.current_pv ?? 0) + (this.pvMode() === 'add' ? delta : -delta);
    this.apiService.updateCharacter(character.id, { current_pv }).subscribe(() => {
      this.useCharacter.patchCharacterCache(this.id(), { current_pv });
    });
    this.showPvModal.set(false);
  }

  protected cancelPvModal(): void {
    this.showPvModal.set(false);
  }

  protected readonly showPmModal = signal(false);
  protected readonly pmMode = signal<'add' | 'remove'>('add');
  protected readonly draftPm = signal<number | null>(null);

  protected openPmModal(): void {
    this.pmMode.set('add');
    this.draftPm.set(null);
    this.showPmModal.set(true);
  }

  protected togglePmMode(): void {
    this.pmMode.set(this.pmMode() === 'add' ? 'remove' : 'add');
  }

  protected confirmPm(character: Character): void {
    const delta = this.draftPm();
    if (delta === null) {
      return;
    }
    const current_pm = (character.current_pm ?? 0) + (this.pmMode() === 'add' ? delta : -delta);
    this.apiService.updateCharacter(character.id, { current_pm }).subscribe(() => {
      this.useCharacter.patchCharacterCache(this.id(), { current_pm });
    });
    this.showPmModal.set(false);
  }

  protected cancelPmModal(): void {
    this.showPmModal.set(false);
  }

  // Inventário — collapsed by default, click toggles. No hover/active
  // styling on the section itself (unlike the PV/PM tiles) since it'd be
  // distracting for something the user's eye rests on/scrolls past often.
  protected readonly inventoryExpanded = signal(false);

  protected toggleInventory(): void {
    this.inventoryExpanded.set(!this.inventoryExpanded());
  }

  // Perícias — same collapsed-by-default/click-toggle pattern as Inventário.
  protected readonly skillsExpanded = signal(false);

  protected toggleSkills(): void {
    this.skillsExpanded.set(!this.skillsExpanded());
  }

  // Poderes — same collapsed-by-default/click-toggle pattern. One card,
  // one expand state, for all three sub-groups (Ativáveis/Condicionais/
  // the passive-etc. group) — see poderUsabilities/activeEffectRows above.
  protected readonly powersExpanded = signal(false);

  protected togglePowers(): void {
    this.powersExpanded.set(!this.powersExpanded());
  }

  // Ações — same collapsed-by-default/click-toggle pattern as every other
  // section.
  protected readonly actionsExpanded = signal(false);

  protected toggleActions(): void {
    this.actionsExpanded.set(!this.actionsExpanded());
  }

  // "Outros" — same collapsed-by-default/click-toggle pattern, for actions
  // that aren't a roll (Mudar Nível today).
  protected readonly otherExpanded = signal(false);

  protected toggleOther(): void {
    this.otherExpanded.set(!this.otherExpanded());
  }

  // Magias — same collapsed-by-default/click-toggle pattern as every other
  // section, but only shown at all for a character that actually has a
  // caster class: any character_levels row with a non-empty spell_ids
  // means the character has known spells (see character_levels' own
  // spell_ids column, populated at creation/level-up for whichever class
  // granted spell slots that level).
  protected readonly spellsExpanded = signal(false);

  protected toggleSpells(): void {
    this.spellsExpanded.set(!this.spellsExpanded());
  }

  protected hasSpells(character: Character): boolean {
    const knowsAny = (character.levels ?? []).some((level) => (level.spell_ids?.length ?? 0) > 0);
    // A non-caster can still receive a buff spell cast on them (e.g. a
    // Guerreiro targeted by Bênção) — the section stays visible so they can
    // see/remove it, even though character_levels has no spell_ids for them.
    const hasActiveBuff = (character.active_spell_effects ?? []).length > 0;
    return knowsAny || hasActiveBuff;
  }

  // One group per círculo that actually has a known spell (every spell_ids
  // entry across every character_levels row, joined against the spells
  // catalog) — a círculo with none is simply skipped, same "no empty label"
  // rule as every other items-row grouping on this page.
  protected spellGroups(character: Character): { circle: number; spells: Spell[] }[] {
    const knownIds = new Set<number>();
    (character.levels ?? []).forEach((level) => {
      (level.spell_ids ?? []).forEach((id) => knownIds.add(id));
    });
    const known = this.staticRegistry.spells.filter((spell) => knownIds.has(spell.id));
    const circles = [...new Set(known.map((spell) => spell.circle))].sort((a, b) => a - b);
    return circles.map((circle) => ({
      circle,
      spells: known.filter((spell) => spell.circle === circle).sort((a, b) => a.name.localeCompare(b.name, 'pt-BR')),
    }));
  }

  protected circleLabel(circle: number): string {
    return `${circle}º Círculo`;
  }

  // Currently active spell buffs (character_active_spell_effects) — a
  // row's mere existence means it's active (no is_active flag; Remover
  // deletes the row outright). Joined against the spells catalog just for
  // icon/name, same shape as activeEffectRows for powers.
  protected activeSpellEffectRows(character: Character): SelectedActiveSpellEffect[] {
    return (character.active_spell_effects ?? [])
      .map((effect) => {
        const spell = this.staticRegistry.spells.find((s) => s.id === effect.spell_id);
        return spell ? { effect, spell } : null;
      })
      .filter((row): row is SelectedActiveSpellEffect => row !== null);
  }

  protected readonly selectedActiveSpellEffect = signal<SelectedActiveSpellEffect | null>(null);

  protected openActiveSpellEffectModal(row: SelectedActiveSpellEffect): void {
    this.selectedActiveSpellEffect.set(row);
  }

  protected cancelActiveSpellEffectModal(): void {
    this.selectedActiveSpellEffect.set(null);
  }

  protected spellTypeLabel(type: string): string {
    const labels: Record<string, string> = {
      arcana: 'Arcana',
      divina: 'Divina',
      universal: 'Universal',
    };
    return labels[type] ?? type;
  }

  protected spellSchoolLabel(school: string): string {
    const labels: Record<string, string> = {
      abjuracao: 'Abjuração',
      adivinhacao: 'Adivinhação',
      convocacao: 'Convocação',
      encantamento: 'Encantamento',
      evocacao: 'Evocação',
      ilusao: 'Ilusão',
      necromancia: 'Necromancia',
      transmutacao: 'Transmutação',
    };
    return labels[school] ?? school;
  }

  // Spell-casting modal — opened from clicking a spell card, same
  // click-a-card-to-open-its-modal convention as every other item/power
  // card on this page (not a separate generic button). Holding the
  // selected Spell itself (not just a boolean) is what the modal's own
  // [spell] input needs.
  protected readonly selectedSpell = signal<Spell | null>(null);

  protected openCastSpellModal(spell: Spell): void {
    this.selectedSpell.set(spell);
  }

  protected cancelCastSpellModal(): void {
    this.selectedSpell.set(null);
  }

  // Attack roll modal — opened from the Agredir button. All of its own
  // state/logic (carousel, power checklist, roll) now lives in
  // shared/attack-modal since that modal is expected to keep growing.
  protected readonly showAttackModal = signal(false);

  protected openAttackModal(): void {
    this.showAttackModal.set(true);
  }

  protected cancelAttackModal(): void {
    this.showAttackModal.set(false);
  }

  // Skill roll modal — opened from a skill-row click in the Perícias
  // expandible. Same signal-pair shape as showAttackModal.
  protected readonly showSkillRollModal = signal(false);
  protected readonly selectedSkillRollId = signal<number | null>(null);

  protected openSkillRollModal(skillId: number): void {
    this.selectedSkillRollId.set(skillId);
    this.showSkillRollModal.set(true);
  }

  protected cancelSkillRollModal(): void {
    this.showSkillRollModal.set(false);
  }

  // Mudar Nível modal — own component (shared/level-change-modal), same
  // pattern as attack-modal/golpe-pessoal-modal.
  protected readonly showMudarNivelModal = signal(false);

  protected openMudarNivelModal(): void {
    this.showMudarNivelModal.set(true);
  }

  protected cancelMudarNivelModal(): void {
    this.showMudarNivelModal.set(false);
  }

  protected readonly declareDeadConfirming = signal(false);
  protected readonly declareDeadReady = signal(false);
  private declareDeadTimeoutId: ReturnType<typeof setTimeout> | null = null;

  protected onDeclareDeadClick(character: Character): void {
    if (!this.declareDeadConfirming()) {
      this.declareDeadConfirming.set(true);
      this.declareDeadTimeoutId = setTimeout(() => this.declareDeadReady.set(true), 1000);
      return;
    }
    if (!this.declareDeadReady()) {
      return;
    }
    this.apiService.updateCharacter(character.id, { is_dead: true }).subscribe(() => {
      this.useCharacter.patchCharacterCache(this.id(), { is_dead: true });
      this.router.navigate(['/player']);
    });
  }

  // Entrar em Campanha modal — own component (shared/join-campaign-modal),
  // same pattern as attack-modal/golpe-pessoal-modal/level-change-modal.
  // Only shown at all while campaign_id is null (see character-main.html).
  protected readonly showJoinCampaignModal = signal(false);

  protected openJoinCampaignModal(): void {
    this.showJoinCampaignModal.set(true);
  }

  protected cancelJoinCampaignModal(): void {
    this.showJoinCampaignModal.set(false);
  }

  // Melhorar Item modal — own component (shared/improve-item-modal), same
  // pattern as attack-modal/golpe-pessoal-modal/level-change-modal.
  protected readonly showImproveItemModal = signal(false);

  protected openImproveItemModal(): void {
    this.showImproveItemModal.set(true);
  }

  protected cancelImproveItemModal(): void {
    this.showImproveItemModal.set(false);
  }

  // Comprar Item modal — own component (shared/modals/buy-item-modal), same
  // pattern as improve-item-modal/item-details-modal.
  protected readonly showBuyItemModal = signal(false);

  protected openBuyItemModal(): void {
    this.showBuyItemModal.set(true);
  }

  protected cancelBuyItemModal(): void {
    this.showBuyItemModal.set(false);
  }

  // Power detail modal — click a card, see the power's full description,
  // Remover button. All of its own state/logic now lives in
  // shared/modals/power-details-modal, same reasoning as attack-modal.
  protected readonly selectedPower = signal<SelectedPower | null>(null);

  // Golpe Pessoal (power id 115) doesn't get the normal description modal
  // — it opens its own dedicated build/view modal instead. Hardcoded id,
  // same convention as attack-modal's ataqueEspecialPowerIds/
  // repeatablePowerIds in character-creation-step-9.ts.
  private readonly golpePessoalPowerId = 115;
  protected readonly showGolpePessoalModal = signal(false);

  protected openPowerModal(effect: CharacterActiveEffectRow, power: Power, iconFileName: string | undefined): void {
    if (power.id === this.golpePessoalPowerId) {
      this.showGolpePessoalModal.set(true);
      return;
    }
    this.selectedPower.set({ effect, power, iconFileName });
  }

  protected cancelGolpePessoalModal(): void {
    this.showGolpePessoalModal.set(false);
  }

  protected cancelPowerModal(): void {
    this.selectedPower.set(null);
  }

  // Adicionar Poder — every power in the catalog not already on the
  // character, no usability filtering — a player looking for e.g. Vontade
  // de Ferro shouldn't have to guess which button it's under. No
  // prerequisite/type checks like the wizard does either — this is a
  // free-form GM/dev tool for granting a power directly from the sheet,
  // not a guided pick — "in the real world, that's how it goes."
  protected readonly showAddPowerModal = signal(false);
  protected readonly draftAddPowerPowerId = signal<number | null>(null);

  protected availableAddPowerPowers(character: Character): Power[] {
    const alreadyHas = new Set((character.active_effects ?? []).map((ae) => ae.power_id));
    return this.staticRegistry.powers.filter((p) => !alreadyHas.has(p.id));
  }

  protected openAddPowerModal(): void {
    this.draftAddPowerPowerId.set(null);
    this.showAddPowerModal.set(true);
  }

  protected cancelAddPowerModal(): void {
    this.showAddPowerModal.set(false);
  }

  protected confirmAddPower(character: Character): void {
    const powerId = this.draftAddPowerPowerId();
    if (powerId === null) {
      return;
    }
    this.apiService.addCharacterActiveEffect(character.id, powerId).subscribe((active_effects) => {
      this.useCharacter.patchCharacterCache(this.id(), { active_effects });
    });
    this.showAddPowerModal.set(false);
  }

  // Tibares editing — Adicionar/Remover a delta, not setting an absolute
  // value (unlike PV/PM's own tentative-value modal). draftTibares starts
  // empty each open, since it's an amount being added/removed, not the
  // current total. tibaresMode defaults to 'add' on open and is flipped by
  // a toggle button inside the modal itself, not by which button opened it.
  protected readonly showTibaresModal = signal(false);
  protected readonly tibaresMode = signal<'add' | 'remove'>('add');
  protected readonly draftTibares = signal<number | null>(null);

  protected openTibaresModal(): void {
    this.tibaresMode.set('add');
    this.draftTibares.set(null);
    this.showTibaresModal.set(true);
  }

  protected toggleTibaresMode(): void {
    this.tibaresMode.set(this.tibaresMode() === 'add' ? 'remove' : 'add');
  }

  protected confirmTibares(character: Character): void {
    const delta = this.draftTibares();
    if (delta === null) {
      return;
    }
    const tibares = character.tibares + (this.tibaresMode() === 'add' ? delta : -delta);
    this.apiService.updateCharacter(character.id, { tibares }).subscribe(() => {
      this.useCharacter.patchCharacterCache(this.id(), { tibares });
    });
    this.showTibaresModal.set(false);
  }

  protected cancelTibaresModal(): void {
    this.showTibaresModal.set(false);
  }

  // XP editing — same Adicionar/Remover a delta treatment as Tibares.
  protected readonly showXpModal = signal(false);
  protected readonly xpMode = signal<'add' | 'remove'>('add');
  protected readonly draftXp = signal<number | null>(null);

  protected openXpModal(): void {
    this.xpMode.set('add');
    this.draftXp.set(null);
    this.showXpModal.set(true);
  }

  protected toggleXpMode(): void {
    this.xpMode.set(this.xpMode() === 'add' ? 'remove' : 'add');
  }

  protected confirmXp(character: Character): void {
    const delta = this.draftXp();
    if (delta === null) {
      return;
    }
    const xp = character.xp + (this.xpMode() === 'add' ? delta : -delta);
    this.apiService.updateCharacter(character.id, { xp }).subscribe(() => {
      this.useCharacter.patchCharacterCache(this.id(), { xp });
    });
    this.showXpModal.set(false);
  }

  protected cancelXpModal(): void {
    this.showXpModal.set(false);
  }

  // Item detail modal — own component (shared/item-details-modal), same
  // pattern as attack-modal/golpe-pessoal-modal/level-change-modal/
  // improve-item-modal. This just tracks WHICH item is selected; equip/
  // destroy behavior lives inside the modal itself.
  protected readonly selectedItem = signal<SelectedItem | null>(null);

  protected openWeaponModal(inventoryRow: CharacterInventoryRow, weapon: Weapon, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, name: inventoryRow.custom_name ?? weapon.name, description: weapon.description, iconFileName, kind: 'weapon', grip: weapon.grip });
  }

  protected openShieldModal(inventoryRow: CharacterInventoryRow, shield: Shield, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, name: inventoryRow.custom_name ?? shield.name, description: shield.description, iconFileName, kind: 'shield' });
  }

  protected openArmorModal(inventoryRow: CharacterInventoryRow, armor: Armor, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, name: inventoryRow.custom_name ?? armor.name, description: armor.description, iconFileName, kind: 'armor' });
  }

  protected openAccessoryModal(inventoryRow: CharacterInventoryRow, accessory: Accessory, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, name: inventoryRow.custom_name ?? accessory.name, description: accessory.description, iconFileName, kind: 'accessory' });
  }

  protected cancelItemModal(): void {
    this.selectedItem.set(null);
  }

  // Destruir Personagem — same deliberate-second-click cooldown as item
  // destroy above, but its own independent state (this button isn't
  // inside the item modal, so it can't share destroyConfirming/destroyReady).
  protected readonly characterDestroyConfirming = signal(false);
  protected readonly characterDestroyReady = signal(false);
  private characterDestroyTimeoutId: ReturnType<typeof setTimeout> | null = null;

  protected onCharacterDestroyClick(character: Character): void {
    if (!this.characterDestroyConfirming()) {
      this.characterDestroyConfirming.set(true);
      this.characterDestroyTimeoutId = setTimeout(() => this.characterDestroyReady.set(true), 1000);
      return;
    }
    if (!this.characterDestroyReady()) {
      return;
    }
    this.apiService.destroyCharacter(character.id).subscribe(() => {
      this.useCharacter.invalidate();
      this.router.navigate(['/player']);
    });
  }
}
