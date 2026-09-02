<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterAccessory;
use App\Models\CharacterInventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterAccessoryController extends Controller
{
    /**
     * Equip an inventory item into an accessory slot — same shape as
     * CharacterHandController::equip(), just against a single inventory_id
     * column instead of a JSON array (an accessory slot only ever holds
     * one item, no ambiguity to model there). Displaces whatever was
     * previously in this slot (worn:false) and strips the item out of any
     * other slot it was sitting in, then marks it worn:true — accessories
     * are worn the same way weapons/armor are, effects gated on that flag.
     */
    public function equip(Request $request, int $characterId, int $slotId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $slot = CharacterAccessory::where('id', $slotId)
            ->where('character_id', $characterId)
            ->where('enabled', true)
            ->firstOrFail();

        $inventoryId = (int) $request->input('inventory_id');

        CharacterInventory::where('id', $inventoryId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        DB::transaction(function () use ($character, $slot, $inventoryId) {
            if ($slot->inventory_id !== null && $slot->inventory_id !== $inventoryId) {
                CharacterInventory::where('id', $slot->inventory_id)->update(['worn' => false]);
            }

            foreach ($character->accessorySlots as $otherSlot) {
                if ($otherSlot->id === $slot->id) {
                    continue;
                }
                if ($otherSlot->inventory_id === $inventoryId) {
                    $otherSlot->update(['inventory_id' => null]);
                }
            }

            $slot->update(['inventory_id' => $inventoryId]);

            CharacterInventory::where('id', $inventoryId)->update(['worn' => true]);
        });

        return response()->json([
            'accessory_slots' => $character->accessorySlots()->get(),
            'inventory' => $character->inventory()->get(),
        ]);
    }

    /**
     * Unequip — clears the slot's inventory_id and marks the item
     * worn:false. Same response shape as equip() above.
     */
    public function unequip(Request $request, int $characterId, int $slotId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $slot = CharacterAccessory::where('id', $slotId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        $inventoryId = (int) $request->input('inventory_id');

        DB::transaction(function () use ($slot, $inventoryId) {
            if ($slot->inventory_id === $inventoryId) {
                $slot->update(['inventory_id' => null]);
            }
            CharacterInventory::where('id', $inventoryId)->update(['worn' => false]);
        });

        return response()->json([
            'accessory_slots' => $character->accessorySlots()->get(),
            'inventory' => $character->inventory()->get(),
        ]);
    }
}
