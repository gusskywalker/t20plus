<?php

namespace App\Http\Controllers;

use App\Models\CharacterInventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
