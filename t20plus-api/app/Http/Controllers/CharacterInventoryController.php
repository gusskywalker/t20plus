<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterInventory;
use App\Models\GeneralItem;
use App\Models\Weapon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterInventoryController extends Controller
{

    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $itemType = $request->input('item_type');
        $itemId = (int) $request->input('item_id');
        $quantity = (int) ($request->input('quantity') ?? 1);
        $weaponSize = (int) ($request->input('weapon_size') ?? 0);

        DB::transaction(function () use ($character, $itemType, $itemId, $quantity, $weaponSize) {
            $isPotion = $itemType === 'general_item' && GeneralItem::find($itemId)?->type === 'potion';

            if ($isPotion) {
                $existing = CharacterInventory::where('character_id', $character->id)
                    ->where('item_type', 'general_item')
                    ->where('item_id', $itemId)
                    ->first();

                if ($existing) {
                    $existing->increment('quantity', $quantity);

                    return;
                }
            }

            $preAppliedUpgradeIds = $itemType === 'weapon' ? Weapon::find($itemId)?->pre_applied_upgrade_ids : null;

            CharacterInventory::create([
                'character_id' => $character->id,
                'item_type' => $itemType,
                'item_id' => $itemId,
                'worn' => false,
                'quantity' => $quantity,
                'weapon_size' => $weaponSize,
                'improvement_ids' => $preAppliedUpgradeIds['improvement_ids'] ?? [],
                'enchantment_ids' => $preAppliedUpgradeIds['enchantment_ids'] ?? [],
            ]);
        });

        return response()->json(CharacterInventory::where('character_id', $character->id)->get());
    }

    public function update(Request $request, int $characterId, int $inventoryId): JsonResponse
    {
        $item = CharacterInventory::where('id', $inventoryId)
            ->where('character_id', $characterId)
            ->whereHas('character', fn ($query) => $query->where('user_id', auth('api')->id()))
            ->firstOrFail();

        DB::transaction(function () use ($request, $item) {
            $item->update($request->only(['worn', 'improvement_ids', 'enchantment_ids', 'custom_name', 'quantity']));

            if ($item->item_type === 'armor' && $item->worn) {
                CharacterInventory::where('character_id', $item->character_id)
                    ->where('item_type', 'armor')
                    ->where('id', '!=', $item->id)
                    ->update(['worn' => false]);
            }
        });

        return response()->json(CharacterInventory::where('character_id', $item->character_id)->get());
    }

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
