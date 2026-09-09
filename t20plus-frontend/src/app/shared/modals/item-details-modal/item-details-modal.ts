import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterAccessoryRow, CharacterHandRow, CharacterInventoryRow, Power, Weapon } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { calculateMargin } from '../../helpers/calculators/calculate-margin/calculate-margin';
import { calculateMultiplier } from '../../helpers/calculators/calculate-multiplier/calculate-multiplier';
import { calculateWeaponDice } from '../../helpers/calculators/calculate-weapon-dice/calculate-weapon-dice';
import { getActiveEffects } from '../../helpers/get-active-effects/get-active-effects';
import { getItemGrantedEffects, getItemGrantedPowers } from '../../helpers/get-item-granted-effects/get-item-granted-effects';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { weaponSizeLabel } from '../../helpers/weapon-size-label/weapon-size-label';
import { weaponSizeStatus } from '../../helpers/weapon-size-penalty-solver/weapon-size-penalty-solver';
import { resolveProficiencyPenaltyEffects } from '../../helpers/proficiency-penalty-solver/proficiency-penalty-solver';
import { UseCharacter } from '../../hooks/use-character';
import { StaticRegistry } from '../../hooks/static-registry';

// Shared by every item type opened from the inventory. name/description are
// flattened onto this common shape since every catalog carries them; `kind`
// only exists to pick which action UI shows (weapons/shields: one button per
// hand — shields share weapons' whole hand story; armor: a single Equipar/
// Desequipar; accessories: one button per slot, same idea as hands).
export interface SelectedItem {
  inventoryRow: CharacterInventoryRow;
  name: string;
  description: string;
  iconFileName: string | undefined;
  kind: 'weapon' | 'shield' | 'armor' | 'accessory';
  // Only set for kind: 'weapon' — drives the two_hand single-button case
  // below (a two-hander always equips into hand_1, see
  // CharacterHandController::equip).
  grip?: string;
}

@Component({
  selector: 'app-item-details-modal',
  imports: [],
  templateUrl: './item-details-modal.html',
  styleUrl: './item-details-modal.scss',
})
export class ItemDetailsModal {
  private readonly apiService = inject(ApiService);
  private readonly useCharacter = inject(UseCharacter);
  private readonly staticRegistry = inject(StaticRegistry);

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  item = input.required<SelectedItem>();
  cancel = output<void>();

  protected readonly replaceTormenta0ToO = replaceTormenta0ToO;

  // Page 1 — the item itself. Page 2 — read one granted power's own
  // description, reached by clicking its card. Same shape as
  // golpe-pessoal-modal's page 3/4 split.
  protected readonly currentPage = signal(1);
  protected readonly selectedGrantedPower = signal<Power | null>(null);

  protected viewGrantedPower(power: Power): void {
    this.selectedGrantedPower.set(power);
    this.currentPage.set(2);
  }

  protected closeGrantedPowerView(): void {
    this.selectedGrantedPower.set(null);
    this.currentPage.set(1);
  }

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  // Only meaningful for kind: 'weapon' — looked up here instead of carried
  // on SelectedItem since the stats-section needs the full catalog row
  // (proficiency_id, purpose), not just name/description/icon.
  protected currentWeapon(): Weapon | undefined {
    return this.staticRegistry.weapons.find((w) => w.id === this.item().inventoryRow.item_id);
  }

  // This item's OWN granted effects only (its improvement_ids/
  // enchantment_ids) — deliberately not merged with character.active_effects,
  // this screen only ever shows what the physical item itself contributes.
  // Weapons never branch on when_type (only armor/general_item do), so type
  // is always null here.
  protected weaponGrantedEffects() {
    return getItemGrantedEffects(this.item().inventoryRow, this.staticRegistry.itemImprovements, this.staticRegistry.itemEnchantments, this.staticRegistry.powers, null);
  }

  // Every power this item's own improvement_ids/enchantment_ids grant —
  // one card per power, icon + name only for now. type is always null here
  // (only armor/general_item branch on when_type, not modeled in this
  // section yet).
  protected grantedPowers() {
    return getItemGrantedPowers(this.item().inventoryRow, this.staticRegistry.itemImprovements, this.staticRegistry.itemEnchantments, this.staticRegistry.powers, null);
  }

  protected weaponDamageLabel(weapon: Weapon): string {
    return calculateWeaponDice(weapon, this.weaponGrantedEffects(), this.item().inventoryRow.weapon_size);
  }

  protected weaponMarginLabel(weapon: Weapon): number {
    return calculateMargin(weapon, this.weaponGrantedEffects());
  }

  protected weaponMultiplierLabel(weapon: Weapon): number {
    return calculateMultiplier(weapon, this.weaponGrantedEffects());
  }

  protected weaponProficiencyLabel(weapon: Weapon): string {
    if (weapon.proficiency_id === null) {
      return 'Sem Proficiência';
    }
    return this.staticRegistry.powers.find((p) => p.id === weapon.proficiency_id)?.name ?? 'Sem Proficiência';
  }

  // Same missing-proficiency check the attack modal's -5 penalty is built
  // on (resolveProficiencyPenaltyEffects) — non-empty means the character
  // doesn't own the required power.
  protected weaponProficiencyColor(weapon: Weapon): string | null {
    return resolveProficiencyPenaltyEffects(weapon, this.character()).length > 0 ? 'var(--color-tormenta-red)' : null;
  }

  protected weaponPurposeLabel(purpose: string): string {
    const labels: Record<string, string> = {
      melee: 'Corpo a Corpo',
      thrown: 'Arremesso',
      fired: 'Disparo',
    };
    return labels[purpose] ?? purpose;
  }

  // weapon_size lives on the inventory row (character_inventory), not the
  // weapons catalog row — the same catalog weapon can be forged in
  // different sizes across different owned instances.
  protected readonly weaponSizeLabel = weaponSizeLabel;

  // A weapon is exotic when it requires a proficiency other than the two
  // generic ones (Armas Marciais/Armas de Fogo) or none at all — its own
  // standalone proficiency power (e.g. Pistola-Tambor) makes it exotic.
  protected isExoticWeapon(weapon: Weapon): boolean {
    return weapon.proficiency_id !== null && weapon.proficiency_id !== 40 && weapon.proficiency_id !== 41;
  }

  protected weaponGripLabel(grip: string): string {
    const labels: Record<string, string> = {
      light: 'Leve - Uma Mão',
      one_hand: 'Uma Mão',
      two_hand: 'Duas Mãos',
    };
    return labels[grip] ?? grip;
  }

  protected weaponDamageTypeLabel(damageType: string): string {
    const labels: Record<string, string> = {
      slashing: 'Corte',
      bludgeoning: 'Impacto',
      piercing: 'Perfuração',
    };
    return labels[damageType] ?? damageType;
  }

  // base_reach comes back as a decimal-column string (e.g. "0.0", "4.5") —
  // Number() drops the pointless trailing .0 for whole values while keeping
  // real decimals like 4.5, then the m unit is appended here so every call
  // site gets it for free.
  protected weaponReachLabel(weapon: Weapon): string {
    return `${this.replaceTormenta0ToO(Number(weapon.base_reach))}m`;
  }

  protected weaponAbilityNames(weapon: Weapon): string[] {
    return (weapon.ability_ids ?? [])
      .map((id) => this.staticRegistry.weaponAbilities.find((a) => a.id === id)?.name)
      .filter((name): name is string => name !== undefined);
  }

  // hand_1/hand_2 read as right/left since that's the natural pair for a
  // standard 2-armed character — a 3rd/4th hand has no such side to name, so
  // those just stay numbered.
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

  // Two-handed weapons always equip into hand_1 (CharacterHandController
  // clears hand_2 as a side effect) — a single Equipar/Desequipar button
  // instead of one per hand, since there's only ever one hand to pick.
  protected twoHandActionLabel(character: Character, inventoryRowId: number): string {
    const hand1 = (character.hands ?? []).find((hand) => hand.name === 'hand_1');
    const equipped = hand1?.inventory_ids?.includes(inventoryRowId) ?? false;
    return equipped ? 'Desequipar' : 'Equipar';
  }

  protected toggleTwoHandWeapon(character: Character, inventoryRowId: number): void {
    const hand1 = (character.hands ?? []).find((hand) => hand.name === 'hand_1');
    if (!hand1) {
      return;
    }
    this.toggleHand(character, hand1, inventoryRowId);
  }

  // Arma Secundária Grande grants allow_dual_wield_full (see
  // combat-interactions.md's Dual Wielding section: naturally you can only
  // pair a one_hand weapon with a 'leve' one in the other hand, this power
  // lifts that to two full one_hand weapons). Checked via the shared
  // getActiveEffects tag pool, same as any other boolean-grant capability
  // (allow_improve_ammo), not a hardcoded power_id. Deliberately scoped to
  // hand_1/hand_2 only in otherHandGrip() below — hand_3/hand_4 are already
  // 'leve'-only by the power that grants them (a separate, not-yet-built
  // rule), so this check is naturally a no-op there.
  private hasAllowDualWieldFull(character: Character): boolean {
    return getActiveEffects(character, this.staticRegistry.powers).some((e) => e.tag === 'allow_dual_wield_full');
  }

  private otherHandGrip(character: Character, hand: CharacterHandRow): string | null {
    const otherName = hand.name === 'hand_1' ? 'hand_2' : hand.name === 'hand_2' ? 'hand_1' : null;
    if (!otherName) {
      return null;
    }
    const otherHand = (character.hands ?? []).find((h) => h.name === otherName);
    const otherInventoryId = otherHand?.inventory_ids?.[0];
    const otherRow = (character.inventory ?? []).find((row) => row.id === otherInventoryId);
    if (!otherRow || otherRow.item_type !== 'weapon') {
      return null;
    }
    return this.staticRegistry.weapons.find((w) => w.id === otherRow.item_id)?.grip ?? null;
  }

  protected toggleHand(character: Character, hand: CharacterHandRow, inventoryRowId: number): void {
    const equipped = hand.inventory_ids?.includes(inventoryRowId) ?? false;

    // Blocked instead of equipping — page 3/4 show the explanation inline in
    // this same modal (own-chrome, same as page 2's granted-power view)
    // rather than stacking a second modal on top.
    if (!equipped && this.item().kind === 'weapon' && this.item().grip === 'one_hand') {
      if (this.otherHandGrip(character, hand) === 'one_hand' && !this.hasAllowDualWieldFull(character)) {
        this.currentPage.set(3);
        return;
      }
    }

    if (!equipped && this.item().kind === 'weapon') {
      if (weaponSizeStatus(character.current_size, this.item().inventoryRow.weapon_size ?? 0) === 'blocked') {
        this.currentPage.set(4);
        return;
      }
    }

    const request$ = equipped
      ? this.apiService.unequipCharacterHand(character.id, hand.id, inventoryRowId)
      : this.apiService.equipCharacterHand(character.id, hand.id, inventoryRowId);
    request$.subscribe(({ hands, inventory }) => {
      this.useCharacter.patchCharacterCache(this.id(), { hands, inventory });
    });
    this.cancel.emit();
  }

  // Armor's whole equip story is just worn:true/false — no hands involved —
  // so this PATCHes the inventory row directly instead of going through
  // CharacterHandController.
  protected toggleWorn(character: Character, inventoryRow: CharacterInventoryRow): void {
    const worn = !inventoryRow.worn;
    this.apiService.updateCharacterInventoryItem(character.id, inventoryRow.id, { worn }).subscribe((inventory) => {
      this.useCharacter.patchCharacterCache(this.id(), { inventory });
    });
    this.cancel.emit();
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
    this.cancel.emit();
  }

  // "Destruir" needs a deliberate second click before it actually does
  // anything — first click starts a 3s cooldown (button disabled, label
  // switches to "Confirmar"); only a click after that cooldown is the real
  // confirm.
  protected readonly destroyConfirming = signal(false);
  protected readonly destroyReady = signal(false);
  private destroyTimeoutId: ReturnType<typeof setTimeout> | null = null;

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
    this.cancel.emit();
  }
}
