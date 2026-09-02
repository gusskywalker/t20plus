<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterInventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterInventoryController extends Controller
{
    /**
     * Update one inventory row's own live state — just "worn" for now
     * (equip/unequip from the character sheet). Ownership-scoped through
     * the parent character the same way CharacterController's own routes
     * are, so typing another id in the URL 404s instead of touching
     * someone else's item.
     *
     * Only one armor can be worn at a time — equipping one unequips every
     * other armor row this character owns. Weapons/shields don't need this
     * here since hand assignment (CharacterHandController) already
     * enforces their own exclusivity; accessories have no such limit.
     * Returns the character's full inventory, not just this row, since an
     * armor equip can change other rows too.
     */
    public function update(Request $request, int $characterId, int $inventoryId): JsonResponse
    {
        $item = CharacterInventory::where('id', $inventoryId)
            ->where('character_id', $characterId)
            ->whereHas('character', fn ($query) => $query->where('user_id', auth('api')->id()))
            ->firstOrFail();

        DB::transaction(function () use ($request, $item) {
            $item->update($request->only(['worn']));

            if ($item->item_type === 'armor' && $item->worn) {
                CharacterInventory::where('character_id', $item->character_id)
                    ->where('item_type', 'armor')
                    ->where('id', '!=', $item->id)
                    ->update(['worn' => false]);
            }
        });

        return response()->json(CharacterInventory::where('character_id', $item->character_id)->get());
    }

    /**
     * Destroy an item — deletes the row and strips its id out of whichever
     * hand (if any) was holding it, same as unequip's own cleanup, so no
     * hand is left pointing at a deleted inventory row. Accessory slots
     * don't need the same manual cleanup — inventory_id there is a real FK
     * with nullOnDelete, so the DB clears it for free — but the response
     * still returns the fresh accessory_slots so the frontend cache sees it.
     */
    public function destroy(int $characterId, int $inventoryId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $item = CharacterInventory::where('id', $inventoryId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        DB::transaction(function () use ($character, $item) {
            foreach ($character->hands as $hand) {
                $ids = $hand->inventory_ids ?? [];
                if (in_array($item->id, $ids, true)) {
                    $hand->update(['inventory_ids' => array_values(array_diff($ids, [$item->id]))]);
                }
            }

            $item->delete();
        });

        return response()->json([
            'hands' => $character->hands()->get(),
            'accessory_slots' => $character->accessorySlots()->get(),
            'inventory' => $character->inventory()->get(),
        ]);
    }
}
