import { Component, inject, input, output, signal } from '@angular/core';
import { ApiService, Character, CharacterAccessoryRow, CharacterHandRow, CharacterInventoryRow } from '../../../api.service';
import { environment } from '../../../../environments/environment';
import { UseCharacter } from '../../hooks/use-character';

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

  character = input.required<Character>();
  // Route-param string id — same reason every other character-child modal
  // needs its own: patchCharacterCache's key must match whatever
  // characterQuery() was built with, not the numeric Character.id.
  id = input.required<string>();
  item = input.required<SelectedItem>();
  cancel = output<void>();

  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
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

  protected toggleHand(character: Character, hand: CharacterHandRow, inventoryRowId: number): void {
    const equipped = hand.inventory_ids?.includes(inventoryRowId) ?? false;
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
