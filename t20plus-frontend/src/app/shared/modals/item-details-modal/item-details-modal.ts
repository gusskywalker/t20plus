import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterAccessoryRow, CharacterHandRow, CharacterInventoryRow, OtherEffectPower, Power, Weapon } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { DAMAGE_TYPE_LABELS, WEAPON_PURPOSE_LABELS, WEAPON_GRIP_LABELS } from '../../constants/translation-constants';
import { calculateMargin } from '../../helpers/calculators/calculate-margin/calculate-margin';
import { calculateMultiplier } from '../../helpers/calculators/calculate-multiplier/calculate-multiplier';
import { calculateWeaponDice } from '../../helpers/calculators/calculate-weapon-dice/calculate-weapon-dice';
import { getActiveEffects } from '../../helpers/get-active-effects/get-active-effects';
import { getItemGrantedEffects, getItemGrantedPowers } from '../../helpers/get-item-granted-effects/get-item-granted-effects';
import { replaceTormenta0ToO } from '../../helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { weaponSizeLabel } from '../../helpers/weapon-size-label/weapon-size-label';
import { weaponSizeStatus } from '../../helpers/weapon-size-penalty-solver/weapon-size-penalty-solver';
import { resolveCurrentSize } from '../../helpers/resolve-current-size/resolve-current-size';
import { effectiveWeaponSize } from '../../helpers/effective-weapon-size/effective-weapon-size';
import { resolveProficiencyPenaltyEffects } from '../../helpers/proficiency-penalty-solver/proficiency-penalty-solver';
import { resolveReplacedPowerIds } from '../../helpers/resolve-replaced-power-ids/resolve-replaced-power-ids';
import { resolveTag } from '../../helpers/tag-solver/tag-solver';
import { resolveEffectiveWeaponGrip } from '../../helpers/resolve-effective-weapon-grip/resolve-effective-weapon-grip';
import { spendPm } from '../../helpers/spend-pm/spend-pm';
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
  // one card per power, icon + name only. type is always null here (only
  // armor/general_item branch on when_type).
  protected grantedPowers() {
    return getItemGrantedPowers(this.item().inventoryRow, this.staticRegistry.itemImprovements, this.staticRegistry.itemEnchantments, this.staticRegistry.powers, null);
  }

  // usability: 'item_enhancer' powers the character currently has (granted
  // directly, e.g. Natureza Venenosa) whose applies_when.categories matches
  // this item's own kind. Doesn't yet cover an owned consumable item
  // (Veneno bottles) granting one via quantity — that source has no seeded
  // data yet, added once Venenos exist.
  protected itemEnhancerPowers(): Power[] {
    const grantedIds = new Set((this.character().active_effects ?? []).map((e) => e.power_id));
    const replacedIds = resolveReplacedPowerIds(grantedIds, this.staticRegistry.powers);
    return this.staticRegistry.powers.filter(
      (power) => power.usability === 'item_enhancer' && grantedIds.has(power.id) && !replacedIds.has(power.id) && (power.applies_when?.categories ?? []).includes(this.item().kind),
    );
  }

  protected isItemEnhancerApplied(power: Power): boolean {
    return (this.item().inventoryRow.other_effects_power_ids ?? []).some((entry) => entry.power_id === power.id);
  }

  protected itemEnhancerButtonLabel(power: Power): string {
    return this.isItemEnhancerApplied(power) ? `Remover ${power.name}` : power.name;
  }

  // Same button toggles apply/remove — applying spends the power's own
  // pm_cost and writes {power_id, remaining_uses?} (remaining_uses copied
  // from the power's own base value, see tag-system.md); removing is free
  // (no PM refund — the cost was for casting the effect, not for keeping
  // it active) and just drops the entry. remaining_uses' own countdown on
  // a landed hit is attack-modal's job (markPassed), not this button's.
  protected toggleItemEnhancer(character: Character, power: Power): void {
    const current = this.item().inventoryRow.other_effects_power_ids ?? [];
    let otherEffectsPowerIds: OtherEffectPower[];

    if (this.isItemEnhancerApplied(power)) {
      otherEffectsPowerIds = current.filter((entry) => entry.power_id !== power.id);
    } else {
      spendPm(this.apiService, this.useCharacter, this.id(), character, power.pm_cost);
      const baseUses = resolveTag(power.effects ?? [], 'remaining_uses');
      const entry: OtherEffectPower = baseUses > 0 ? { power_id: power.id, remaining_uses: baseUses } : { power_id: power.id };
      otherEffectsPowerIds = [...current, entry];
    }

    this.apiService.updateCharacterInventoryItem(character.id, this.item().inventoryRow.id, { other_effects_power_ids: otherEffectsPowerIds }).subscribe((inventory) => {
      this.useCharacter.patchCharacterCache(this.id(), { inventory });
    });
    this.cancel.emit();
  }

  protected weaponDamageLabel(weapon: Weapon): string {
    return calculateWeaponDice(weapon, this.weaponGrantedEffects(), this.effectiveItemWeaponSize(this.character()));
  }

  // The item's stored weapon_size as it currently counts, after any live
  // change to the character's size (see effectiveWeaponSize).
  private effectiveItemWeaponSize(character: Character): number {
    return effectiveWeaponSize(
      this.item().inventoryRow.weapon_size ?? 0,
      character.current_size,
      resolveCurrentSize(character, this.staticRegistry.powers),
    );
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
    return resolveProficiencyPenaltyEffects(weapon, this.character(), this.staticRegistry.powers).length > 0 ? 'var(--color-tormenta-red)' : null;
  }

  protected weaponPurposeLabel(purpose: string): string {
    return WEAPON_PURPOSE_LABELS[purpose] ?? purpose;
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
    return WEAPON_GRIP_LABELS[grip] ?? grip;
  }

  protected weaponDamageTypeLabel(damageType: string): string {
    return DAMAGE_TYPE_LABELS[damageType] ?? damageType;
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
  // pair a one_hand weapon with 'leve' ones in the other hands, this power
  // lifts that to one_hand weapons in every hand). Checked via the shared
  // getActiveEffects tag pool, same as any other boolean-grant capability
  // (allow_improve_ammo), not a hardcoded power_id.
  private hasAllowDualWieldFull(character: Character): boolean {
    return getActiveEffects(character, this.staticRegistry.powers).some((e) => e.tag === 'allow_dual_wield_full');
  }

  private otherHandsHoldOneHand(character: Character, hand: CharacterHandRow): boolean {
    return (character.hands ?? [])
      .filter((otherHand) => otherHand.enabled && otherHand.name !== hand.name)
      .some((otherHand) => {
        const otherInventoryId = otherHand.inventory_ids?.[0];
        const otherRow = (character.inventory ?? []).find((row) => row.id === otherInventoryId);
        if (!otherRow || otherRow.item_type !== 'weapon') {
          return false;
        }
        const otherWeapon = this.staticRegistry.weapons.find((w) => w.id === otherRow.item_id);
        return !!otherWeapon && resolveEffectiveWeaponGrip(character, otherWeapon, this.staticRegistry.powers) === 'one_hand';
      });
  }

  protected toggleHand(character: Character, hand: CharacterHandRow, inventoryRowId: number): void {
    const equipped = hand.inventory_ids?.includes(inventoryRowId) ?? false;

    // Blocked instead of equipping — page 3/4 show the explanation inline in
    // this same modal (own-chrome, same as page 2's granted-power view)
    // rather than stacking a second modal on top.
    if (!equipped && this.item().kind === 'weapon' && this.item().grip === 'one_hand') {
      if (this.otherHandsHoldOneHand(character, hand) && !this.hasAllowDualWieldFull(character)) {
        this.currentPage.set(3);
        return;
      }
    }

    if (!equipped && this.item().kind === 'weapon') {
      if (weaponSizeStatus(resolveCurrentSize(character, this.staticRegistry.powers), this.effectiveItemWeaponSize(character)) === 'blocked') {
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
      this.destroyTimeoutId = setTimeout(() => this.destroyReady.set(true), 1000);
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
