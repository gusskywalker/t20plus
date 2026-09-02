<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterHand;
use App\Models\CharacterInventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterHandController extends Controller
{
    /**
     * Equip an inventory item into a hand. For now every item is one-handed
     * (no "shares a hand with other items" case yet — that'll skip this
     * override step and append to inventory_ids instead once it exists),
     * so this always OVERRIDES the target hand's inventory_ids rather than
     * appending, and strips the item out of any other hand it was
     * currently sitting in — a one-handed item can only be in one hand at
     * a time. Ownership-scoped the same way CharacterController's own
     * routes are.
     *
     * worn tracks whether an item's effects are active and is otherwise
     * independent of hand assignment (see CharacterInventoryController),
     * but equipping into a hand is itself one way an item becomes worn —
     * and overriding a hand's previous occupant bumps that displaced item
     * back to worn:false, since it's no longer held anywhere.
     */
    public function equip(Request $request, int $characterId, int $handId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $hand = CharacterHand::where('id', $handId)
            ->where('character_id', $characterId)
            ->where('enabled', true)
            ->firstOrFail();

        $inventoryId = (int) $request->input('inventory_id');

        CharacterInventory::where('id', $inventoryId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        DB::transaction(function () use ($character, $hand, $inventoryId) {
            $displacedIds = array_diff($hand->inventory_ids ?? [], [$inventoryId]);
            if ($displacedIds) {
                CharacterInventory::whereIn('id', $displacedIds)->update(['worn' => false]);
            }

            foreach ($character->hands as $otherHand) {
                if ($otherHand->id === $hand->id) {
                    continue;
                }
                $ids = $otherHand->inventory_ids ?? [];
                if (in_array($inventoryId, $ids, true)) {
                    $otherHand->update(['inventory_ids' => array_values(array_diff($ids, [$inventoryId]))]);
                }
            }

            $hand->update(['inventory_ids' => [$inventoryId]]);

            CharacterInventory::where('id', $inventoryId)->update(['worn' => true]);
        });

        return response()->json([
            'hands' => $character->hands()->get(),
            'inventory' => $character->inventory()->get(),
        ]);
    }

    /**
     * Unequip — removes the id from this hand's own inventory_ids and
     * bumps the item back to worn:false, no cross-hand side effects
     * (that's only an equip() concern). Same ownership-scoping and
     * response shape as equip() above.
     */
    public function unequip(Request $request, int $characterId, int $handId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $hand = CharacterHand::where('id', $handId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        $inventoryId = (int) $request->input('inventory_id');

        DB::transaction(function () use ($hand, $inventoryId) {
            $hand->update(['inventory_ids' => array_values(array_diff($hand->inventory_ids ?? [], [$inventoryId]))]);
            CharacterInventory::where('id', $inventoryId)->update(['worn' => false]);
        });

        return response()->json([
            'hands' => $character->hands()->get(),
            'inventory' => $character->inventory()->get(),
        ]);
    }
}
