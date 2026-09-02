import { Component, effect, inject, input, signal } from '@angular/core';
import { CardHeader } from '../../../shared/card-header/card-header';
import { Modal } from '../../../shared/modal/modal';
import { NumberInput } from '../../../shared/inputs/number-input/number-input';
import { UseCharacter } from '../../../shared/hooks/use-character';
import { StaticRegistry } from '../../../shared/hooks/static-registry';
import { ApiService, Character, CharacterHandRow, CharacterInventoryRow, Weapon } from '../../../api.service';
import { calculateMaxPv } from '../../../shared/helpers/max-pv/max-pv';
import { calculateMaxPm } from '../../../shared/helpers/max-pm/max-pm';
import { calculateMaxSlots } from '../../../shared/helpers/max-slots/max-slots';
import { replaceTormenta0ToO } from '../../../shared/helpers/replace-tormenta-0-to-o/replace-tormenta-0-to-o';
import { environment } from '../../../../environments/environment';

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

  // Bound straight from the :id route segment — see withComponentInputBinding() in app.config.ts.
  id = input.required<string>();

  protected readonly characterQuery = this.useCharacter.characterQuery(this.id);

  constructor() {
    // First time the sheet loads a character whose current_pv/current_pm
    // were never initialized (null, not 0 — see the migration comment),
    // compute max and persist it as the starting current value. Only
    // fires once per character: after the PATCH + invalidate below, both
    // fields are no longer null, so this effect's own guard stops it from
    // firing again.
    effect(() => {
      const character = this.characterQuery.data();
      if (!character || (character.current_pv !== null && character.current_pm !== null)) {
        return;
      }
      const current_pv = character.current_pv ?? calculateMaxPv(character);
      const current_pm = character.current_pm ?? calculateMaxPm(character);
      this.apiService.updateCharacter(character.id, { current_pv, current_pm }).subscribe(() => {
        this.useCharacter.patchCharacterCache(this.id(), { current_pv, current_pm });
      });
    });
  }

  protected portraitUrl(fileName: string): string {
    return `${environment.portraitsBaseUrl}/${fileName}`;
  }

  protected slotsLabel(slots: number): string {
    return slots === 1 ? 'Espaço' : 'Espaços';
  }

  // icons.file_name already includes its subdir (e.g. "weapons/weapons_01.webp")
  // — see IconSeeder — so this is just a straight base-url join, same as portraitUrl.
  protected iconUrl(fileName: string): string {
    return `${environment.iconsBaseUrl}/${fileName}`;
  }

  // One row per weapon in the character's inventory, joined against the
  // weapons catalog (for name/slots/price) and icons catalog (for the
  // card's icon) — armors/shields/accessories will get their own sections
  // the same way once we get to them.
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

  protected readonly maxPv = calculateMaxPv;
  protected readonly maxPm = calculateMaxPm;
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
              : this.staticRegistry.accessories;
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

  // Item detail modal — equip/unequip buttons act immediately (see
  // toggleHand below), no separate confirm step for those.
  protected readonly selectedItem = signal<{ inventoryRow: CharacterInventoryRow; weapon: Weapon; iconFileName: string | undefined } | null>(null);

  protected openWeaponModal(inventoryRow: CharacterInventoryRow, weapon: Weapon, iconFileName: string | undefined): void {
    this.selectedItem.set({ inventoryRow, weapon, iconFileName });
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
    this.apiService.destroyCharacterInventoryItem(character.id, inventoryId).subscribe(({ hands, inventory }) => {
      this.useCharacter.patchCharacterCache(this.id(), { hands, inventory });
    });
    this.selectedItem.set(null);
    this.resetDestroyState();
  }
}
