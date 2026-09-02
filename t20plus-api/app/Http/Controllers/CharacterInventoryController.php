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
     */
    public function update(Request $request, int $characterId, int $inventoryId): JsonResponse
    {
        $item = CharacterInventory::where('id', $inventoryId)
            ->where('character_id', $characterId)
            ->whereHas('character', fn ($query) => $query->where('user_id', auth('api')->id()))
            ->firstOrFail();

        $item->update($request->only(['worn']));

        return response()->json($item);
    }

    /**
     * Destroy an item — deletes the row and strips its id out of whichever
     * hand (if any) was holding it, same as unequip's own cleanup, so no
     * hand is left pointing at a deleted inventory row.
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
            'inventory' => $character->inventory()->get(),
        ]);
    }
}
