import { Component, effect, inject, input, signal } from '@angular/core';
import { Router } from '@angular/router';
import { CardHeader } from '../../../shared/card-header/card-header';
import { Modal } from '../../../shared/modal/modal';
import { NumberInput } from '../../../shared/inputs/number-input/number-input';
import { UseCharacter } from '../../../shared/hooks/use-character';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import {
  Accessory,
  ApiService,
  Armor,
  Character,
  CharacterAccessoryRow,
  CharacterActiveEffectRow,
  CharacterHandRow,
  CharacterInventoryRow,
  Power,
  Shield,
  Skill,
  Weapon,
} from '../../../api.service';
import { calculateMaxPv } from '../../../shared/helpers/calculate-max-pv/calculate-max-pv';
import { calculateMaxPm } from '../../../shared/helpers/calculate-max-pm/calculate-max-pm';
import { calculateMaxSlots } from '../../../shared/helpers/max-slots/max-slots';
import { calculateDefense } from '../../../shared/helpers/calculate-defense/calculate-defense';
import { calculateSkillBonus } from '../../../shared/helpers/calculate-skill-bonus/calculate-skill-bonus';
import { replaceTormenta0ToO } from '../../../shared/helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
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
  imports: [CardHeader, Modal, NumberInput],
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
      const iconFileName = this.staticRegistry.icons.find((icon) => icon.id === weapon.icon_id)?.file_name;
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
      const iconFileName = this.staticRegistry.icons.find((icon) => icon.id === shield.icon_id)?.file_name;
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
      const iconFileName = this.staticRegistry.icons.find((icon) => icon.id === armor.icon_id)?.file_name;
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
      const iconFileName = this.staticRegistry.icons.find((icon) => icon.id === accessory.icon_id)?.file_name;
      rows.push({ inventoryRow: item, accessory, iconFileName });
    }
    return rows;
  }

  // One row per character_active_effects row, joined against the powers
  // catalog (for name/description) and icons catalog — same shape as
  // weaponRows/etc. above, but keyed off power_id instead of item_id since
  // active effects aren't inventory items.
  protected effectRows(character: Character): { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] {
    const rows: { effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined }[] = [];
    for (const effect of character.active_effects ?? []) {
      const power = this.staticRegistry.powers.find((p) => p.id === effect.power_id);
      if (!power) {
        continue;
      }
      const iconFileName = this.staticRegistry.icons.find((icon) => icon.id === power.icon_id)?.file_name;
      rows.push({ effect, power, iconFileName });
    }
    return rows;
  }

  // XP needed to reach the NEXT level — the "z" in "XP y/z". Level 20 is
  // the cap (no level 21 row), so it just holds at its own threshold.
  protected xpForNextLevel(level: number): number {
    return XP_BY_LEVEL[level + 1] ?? XP_BY_LEVEL[20];
  }

  // "Gue 2/Bár 3/Caç 6" — first 3 letters of each class name + how many
  // character_levels rows belong to it, in the order each class first
  // appears (level order), not alphabetical, so it reads the way the
  // character was actually built. Abbreviated so a multiclass character
  // still fits on one row next to the "Classes" label.
  protected classSummary(character: Character): string {
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

  // base_* already IS the effective value — the race's fixed mod_* and the
  // "other" bonus point are both baked in at creation time (see
  // character-payload.ts), so nothing gets added here.
  protected readonly attributeRows = (character: Character) => [
    { label: 'Força', value: character.base_str },
    { label: 'Destreza', value: character.base_dex },
    { label: 'Constituição', value: character.base_con },
    { label: 'Inteligência', value: character.base_int },
    { label: 'Sabedoria', value: character.base_knw },
    { label: 'Carisma', value: character.base_car },
  ];

  protected characterDefense(character: Character): number {
    return calculateDefense(character, this.staticRegistry.armors, this.staticRegistry.shields, this.staticRegistry.powers);
  }

  // Every skill in the catalog, in seed order (canon order, not sorted).
  // The two icons are purely informative about the SKILL itself (can it
  // only be used if trained, does armor penalty apply to it at all) — not
  // about whether this particular character happens to be trained or is
  // currently wearing penalized gear. They always show for a matching
  // skill, e.g. Ladinagem always gets both regardless of who's viewing it.
  protected skillRows(character: Character): { skill: Skill; bonus: number; characterIsTrained: boolean }[] {
    return this.staticRegistry.skills.map((skill) => ({
      skill,
      bonus: calculateSkillBonus(character, skill, this.staticRegistry.armors, this.staticRegistry.shields),
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

  // Sum of each inventory item's own slots field, looked up in its
  // catalog by item_type/item_id. Tibares contribute 0 for now — TODO
  // implement different tibar coins and other universalized weights;
  // only slots declared on actual equipment count today.
  protected currentSlots(inventory: CharacterInventoryRow[] | undefined): number {
    if (!inventory) {
      return 0;
    }
    return inventory.reduce((total, item) => {
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
      return total + (entry?.slots ?? 0);
    }, 0);
  }

  // Vida/Mana Atual editing — tentative value only committed on
  // "Selecionar", same pattern as step 1's portrait modal.
  protected readonly showPvModal = signal(false);
  protected readonly showPmModal = signal(false);
  protected readonly draftPv = signal<number | null>(null);
  protected readonly draftPm = signal<number | null>(null);

  protected openPvModal(character: Character): void {
    this.draftPv.set(character.current_pv);
    this.showPvModal.set(true);
  }

  protected confirmPv(character: Character): void {
    const current_pv = this.draftPv();
    if (current_pv === null) {
      return;
    }
    this.apiService.updateCharacter(character.id, { current_pv }).subscribe(() => {
      this.useCharacter.patchCharacterCache(this.id(), { current_pv });
    });
    this.showPvModal.set(false);
  }

  protected cancelPvModal(): void {
    this.showPvModal.set(false);
  }

  protected openPmModal(character: Character): void {
    this.draftPm.set(character.current_pm);
    this.showPmModal.set(true);
  }

  protected confirmPm(character: Character): void {
    const current_pm = this.draftPm();
    if (current_pm === null) {
      return;
    }
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
  protected readonly periciasExpanded = signal(false);

  protected togglePericias(): void {
    this.periciasExpanded.set(!this.periciasExpanded());
  }

  // Efeitos Ativos — same collapsed-by-default/click-toggle pattern.
  protected readonly efeitosAtivosExpanded = signal(false);

  protected toggleEfeitosAtivos(): void {
    this.efeitosAtivosExpanded.set(!this.efeitosAtivosExpanded());
  }

  // Effect detail modal — click a card, see the power's full description,
  // Remover button. No backend DELETE endpoint yet, so removeEffect is a
  // stub for now (button's wired, just not hooked up).
  protected readonly selectedEffect = signal<{ effect: CharacterActiveEffectRow; power: Power; iconFileName: string | undefined } | null>(null);

  protected openEffectModal(effect: CharacterActiveEffectRow, power: Power, iconFileName: string | undefined): void {
    this.selectedEffect.set({ effect, power, iconFileName });
  }

  protected cancelEffectModal(): void {
    this.selectedEffect.set(null);
  }

  // TODO: no character_active_effects DELETE endpoint yet — wire this up
  // once it exists.
  protected removeEffect(character: Character, effect: CharacterActiveEffectRow): void {}

  // Tibares editing — same tentative-value-on-modal pattern as PV/PM.
  protected readonly showTibaresModal = signal(false);
  protected readonly draftTibares = signal<number | null>(null);

  protected openTibaresModal(character: Character): void {
    this.draftTibares.set(character.tibares);
    this.showTibaresModal.set(true);
  }

  protected confirmTibares(character: Character): void {
    const tibares = this.draftTibares();
    if (tibares === null) {
      return;
    }
    this.apiService.updateCharacter(character.id, { tibares }).subscribe(() => {
      this.useCharacter.patchCharacterCache(this.id(), { tibares });
    });
    this.showTibaresModal.set(false);
  }

  protected cancelTibaresModal(): void {
    this.showTibaresModal.set(false);
  }

  // Item detail modal — shared by every item type. name/description are
  // flattened onto the common shape since every catalog carries them;
  // `kind` only exists to pick which action UI shows (weapons/shields: one
  // button per hand — shields share weapons' whole hand story; armor: a
  // single Equipar/Desequipar; accessories: one button per slot, same idea
  // as hands) — destroy/cancel don't care which kind it is. Equip/unequip
  // buttons act immediately (see
  // toggleHand/toggleWorn/toggleAccessorySlot below), no separate confirm
  // step for those.
  protected readonly selectedItem = signal<{
    inventoryRow: CharacterInventoryRow;
    name: string;
    description: string;
    iconFileName: string | undefined;
    kind: 'weapon' | 'shield' | 'armor' | 'accessory';
  } | null>(null);

  protected openWeaponModal(inventoryRow: CharacterInventoryRow, weapon: Weapon, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, name: weapon.name, description: weapon.description, iconFileName, kind: 'weapon' });
    this.resetDestroyState();
  }

  protected openShieldModal(inventoryRow: CharacterInventoryRow, shield: Shield, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, name: shield.name, description: shield.description, iconFileName, kind: 'shield' });
    this.resetDestroyState();
  }

  protected openArmorModal(inventoryRow: CharacterInventoryRow, armor: Armor, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, name: armor.name, description: armor.description, iconFileName, kind: 'armor' });
    this.resetDestroyState();
  }

  protected openAccessoryModal(inventoryRow: CharacterInventoryRow, accessory: Accessory, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, name: accessory.name, description: accessory.description, iconFileName, kind: 'accessory' });
    this.resetDestroyState();
  }

  // hand_1/hand_2 read as right/left since that's the natural pair for a
  // standard 2-armed character — a 3rd/4th hand has no such side to name,
  // so those just stay numbered. Purely a display concern, the DB name
  // itself stays positional (hand_1..hand_4).
  protected handLabel(name: CharacterHandRow['name']): string {
    const labels: Record<CharacterHandRow['name'], string> = {
      hand_1: 'Mão Direita',
      hand_2: 'Mão Esquerda',
      hand_3: 'Mão 3',
      hand_4: 'Mão 4',
    };
    return labels[name];
  }

  protected handActionLabel(hand: CharacterHandRow, inventoryRowId: number): string {
    const equipped = hand.inventory_ids?.includes(inventoryRowId) ?? false;
    return `${equipped ? 'Desequipar' : 'Equipar'} ${this.handLabel(hand.name)}`;
  }

  protected toggleHand(character: Character, hand: CharacterHandRow, inventoryRowId: number): void {
    const equipped = hand.inventory_ids?.includes(inventoryRowId) ?? false;
    const request$ = equipped
      ? this.apiService.unequipCharacterHand(character.id, hand.id, inventoryRowId)
      : this.apiService.equipCharacterHand(character.id, hand.id, inventoryRowId);
    request$.subscribe(({ hands, inventory }) => {
      this.useCharacter.patchCharacterCache(this.id(), { hands, inventory });
    });
    this.selectedItem.set(null);
    this.resetDestroyState();
  }

  // Armor's whole equip story is just worn:true/false — no hands involved
  // — so this PATCHes the inventory row directly instead of going through
  // CharacterHandController.
  protected toggleWorn(character: Character, inventoryRow: CharacterInventoryRow): void {
    const worn = !inventoryRow.worn;
    this.apiService.updateCharacterInventoryItem(character.id, inventoryRow.id, { worn }).subscribe((inventory) => {
      this.useCharacter.patchCharacterCache(this.id(), { inventory });
    });
    this.selectedItem.set(null);
    this.resetDestroyState();
  }

  // accessory_1..5 have no natural side like hands do, so they just stay
  // numbered — "Acessório 1", etc.
  protected accessorySlotLabel(name: CharacterAccessoryRow['name']): string {
    return `Acessório ${name.split('_')[1]}`;
  }

  protected accessorySlotActionLabel(slot: CharacterAccessoryRow, inventoryRowId: number): string {
    const equipped = slot.inventory_id === inventoryRowId;
    return `${equipped ? 'Desequipar' : 'Equipar'} ${this.accessorySlotLabel(slot.name)}`;
  }

  // Same shape as toggleHand, against a single inventory_id instead of an
  // array — CharacterAccessoryController keeps worn in sync the same way
  // CharacterHandController does.
  protected toggleAccessorySlot(character: Character, slot: CharacterAccessoryRow, inventoryRowId: number): void {
    const equipped = slot.inventory_id === inventoryRowId;
    const request$ = equipped
      ? this.apiService.unequipCharacterAccessory(character.id, slot.id, inventoryRowId)
      : this.apiService.equipCharacterAccessory(character.id, slot.id, inventoryRowId);
    request$.subscribe(({ accessory_slots, inventory }) => {
      this.useCharacter.patchCharacterCache(this.id(), { accessory_slots, inventory });
    });
    this.selectedItem.set(null);
    this.resetDestroyState();
  }

  protected cancelItemModal(): void {
    this.selectedItem.set(null);
    this.resetDestroyState();
  }

  // "Destruir" needs a deliberate second click before it actually does
  // anything — first click starts a 3s cooldown (button disabled, label
  // switches to "Confirmar"); only a click after that cooldown is the
  // real confirm.
  protected readonly destroyConfirming = signal(false);
  protected readonly destroyReady = signal(false);
  private destroyTimeoutId: ReturnType<typeof setTimeout> | null = null;

  private resetDestroyState(): void {
    if (this.destroyTimeoutId !== null) {
      clearTimeout(this.destroyTimeoutId);
      this.destroyTimeoutId = null;
    }
    this.destroyConfirming.set(false);
    this.destroyReady.set(false);
  }

  protected onDestroyClick(character: Character, inventoryId: number): void {
    if (!this.destroyConfirming()) {
      this.destroyConfirming.set(true);
      this.destroyTimeoutId = setTimeout(() => this.destroyReady.set(true), 3000);
      return;
    }
    if (!this.destroyReady()) {
      return;
    }
    this.apiService.destroyCharacterInventoryItem(character.id, inventoryId).subscribe(({ hands, accessory_slots, inventory }) => {
      this.useCharacter.patchCharacterCache(this.id(), { hands, accessory_slots, inventory });
    });
    this.selectedItem.set(null);
    this.resetDestroyState();
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
      this.characterDestroyTimeoutId = setTimeout(() => this.characterDestroyReady.set(true), 3000);
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
