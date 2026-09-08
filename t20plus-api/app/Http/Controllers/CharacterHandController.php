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

            if ($hand->name === 'hand_1' && $grip === 'two_hand') {

                $this->clearHand($character->hands->firstWhere('name', 'hand_2'));
            } elseif ($hand->name === 'hand_2') {

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
