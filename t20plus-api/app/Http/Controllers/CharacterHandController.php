<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterHand;
use App\Models\CharacterInventory;
use App\Models\Weapon;
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

        $inventoryItem = CharacterInventory::where('id', $inventoryId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        $grip = $inventoryItem->item_type === 'weapon'
            ? Weapon::find($inventoryItem->item_id)?->grip
            : null;

        DB::transaction(function () use ($character, $hand, $inventoryId, $grip) {
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

            // Two-handed weapons occupy hand_1+hand_2 together — hand_3/4
            // have no such pairing so this only applies to hand_1/hand_2.
            if ($hand->name === 'hand_1' && $grip === 'two_hand') {
                // Equipping a two-hander into hand_1 frees whatever hand_2
                // was holding — it's occupied by this same weapon now.
                $this->clearHand($character->hands->firstWhere('name', 'hand_2'));
            } elseif ($hand->name === 'hand_2') {
                // Equipping anything into hand_2 while hand_1 holds a
                // two-hander frees hand_1 — it can no longer be gripped
                // with both hands.
                $hand1 = $character->hands->firstWhere('name', 'hand_1');
                if ($this->resolveHandWeapon($hand1)?->grip === 'two_hand') {
                    $this->clearHand($hand1);
                }
            }
        });

        return response()->json([
            'hands' => $character->hands()->get(),
            'inventory' => $character->inventory()->get(),
        ]);
    }

    private function clearHand(?CharacterHand $hand): void
    {
        if (!$hand || !($hand->inventory_ids ?? [])) {
            return;
        }
        CharacterInventory::whereIn('id', $hand->inventory_ids)->update(['worn' => false]);
        $hand->update(['inventory_ids' => []]);
    }

    private function resolveHandWeapon(?CharacterHand $hand): ?Weapon
    {
        $inventoryId = ($hand->inventory_ids ?? [])[0] ?? null;
        if (!$inventoryId) {
            return null;
        }
        $item = CharacterInventory::find($inventoryId);
        if (!$item || $item->item_type !== 'weapon') {
            return null;
        }
        return Weapon::find($item->item_id);
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
