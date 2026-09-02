import { ApiService, Character } from '../../../../api.service';
import { UseCharacter } from '../../../../shared/hooks/use-character';
import { calculateMaxPv } from '../../../../shared/helpers/max-pv/max-pv';
import { calculateMaxPm } from '../../../../shared/helpers/max-pm/max-pm';

/**
 * Runs once, the very first time a character's sheet loads with
 * current_pv/current_pm still null (see the characters migration comment
 * on why null, not 0) — computes and persists their starting PV/PM, then
 * auto-equips their starting gear: first weapon into hand_1, first armor
 * worn, first shield into hand_2, first N accessories into however many
 * accessory slots are enabled (normally 4). The caller (character-main.ts)
 * owns the "has this already run" guard — this just does the work once
 * called.
 */
export function initNewCharacter(character: Character, routeId: string, apiService: ApiService, useCharacter: UseCharacter): void {
  const current_pv = character.current_pv ?? calculateMaxPv(character);
  const current_pm = character.current_pm ?? calculateMaxPm(character);
  apiService.updateCharacter(character.id, { current_pv, current_pm }).subscribe(() => {
    useCharacter.patchCharacterCache(routeId, { current_pv, current_pm });
  });

  autoEquipStartingGear(character, routeId, apiService, useCharacter);
}

// Chained one step at a time rather than fired concurrently — every equip
// response is a full snapshot of inventory/hands/accessory_slots, so two
// in flight at once could have the second response's stale snapshot stomp
// on the first's cache patch.
function autoEquipStartingGear(character: Character, routeId: string, apiService: ApiService, useCharacter: UseCharacter): void {
  const inventory = character.inventory ?? [];
  const hand1 = character.hands?.find((hand) => hand.name === 'hand_1');
  const hand2 = character.hands?.find((hand) => hand.name === 'hand_2');
  const enabledAccessorySlots = (character.accessory_slots ?? []).filter((slot) => slot.enabled);

  const firstWeapon = inventory.find((item) => item.item_type === 'weapon');
  const firstArmor = inventory.find((item) => item.item_type === 'armor');
  const firstShield = inventory.find((item) => item.item_type === 'shield');
  const firstAccessories = inventory.filter((item) => item.item_type === 'accessory').slice(0, enabledAccessorySlots.length);

  const steps: (() => void)[] = [];

  if (firstWeapon && hand1) {
    steps.push(() =>
      apiService.equipCharacterHand(character.id, hand1.id, firstWeapon.id).subscribe(({ hands, inventory }) => {
        useCharacter.patchCharacterCache(routeId, { hands, inventory });
        runNextStep();
      }),
    );
  }
  if (firstArmor) {
    steps.push(() =>
      apiService.updateCharacterInventoryItem(character.id, firstArmor.id, { worn: true }).subscribe((inventory) => {
        useCharacter.patchCharacterCache(routeId, { inventory });
        runNextStep();
      }),
    );
  }
  if (firstShield && hand2) {
    steps.push(() =>
      apiService.equipCharacterHand(character.id, hand2.id, firstShield.id).subscribe(({ hands, inventory }) => {
        useCharacter.patchCharacterCache(routeId, { hands, inventory });
        runNextStep();
      }),
    );
  }
  firstAccessories.forEach((item, index) => {
    const slot = enabledAccessorySlots[index];
    steps.push(() =>
      apiService.equipCharacterAccessory(character.id, slot.id, item.id).subscribe(({ accessory_slots, inventory }) => {
        useCharacter.patchCharacterCache(routeId, { accessory_slots, inventory });
        runNextStep();
      }),
    );
  });

  let nextIndex = 0;
  const runNextStep = () => {
    if (nextIndex >= steps.length) {
      return;
    }
    steps[nextIndex++]();
  };
  runNextStep();
}
