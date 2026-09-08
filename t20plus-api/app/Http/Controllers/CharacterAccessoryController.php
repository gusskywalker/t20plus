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
