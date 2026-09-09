<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasUserContext;
use App\Models\Character;
use App\Models\CharacterAccessory;
use App\Models\CharacterActiveEffect;
use App\Models\CharacterGolpePessoal;
use App\Models\CharacterHand;
use App\Models\CharacterInventory;
use App\Models\CharacterLevel;
use App\Models\Power;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterController extends Controller
{
    use HasUserContext;

    public function index(): JsonResponse
    {
        $characters = Character::where('user_id', auth('api')->id())
            ->with(['campaign', 'race', 'portrait', 'god', 'levels'])
            ->get();

        return response()->json($characters);
    }

    public function show(int $id): JsonResponse
    {
        $character = Character::where('id', $id)
            ->where('user_id', auth('api')->id())
            ->with(['campaign', 'race', 'portrait', 'god', 'origin', 'levels.characterClass', 'inventory', 'hands', 'accessorySlots', 'activeEffects', 'golpesPessoais'])
            ->firstOrFail();

        return response()->json($character);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->addUserId($request->only([
            'name',
            'base_str',
            'base_dex',
            'base_con',
            'base_int',
            'base_knw',
            'base_car',
            'current_size',
            'race_id',
            'origin_id',
            'god_id',
            'portrait_id',
            'trained_skill_ids',
            'age',
            'age_bracket',
            'complication_ids',
            'tibares',
        ]));

        $character = DB::transaction(function () use ($request, $data) {
            $character = Character::create($data);

            foreach ($request->input('levels', []) as $level) {
                CharacterLevel::create([
                    'character_id' => $character->id,
                    'level' => $level['level'],
                    'class_id' => $level['class_id'],
                    'class_level' => $level['class_level'],
                    'power_id' => $level['power_id'] ?? null,
                ]);

                if (($level['power_id'] ?? null) === 115) {
                    CharacterGolpePessoal::create([
                        'character_id' => $character->id,
                    ]);
                }
            }

            foreach ($request->input('inventory', []) as $item) {
                CharacterInventory::create([
                    'character_id' => $character->id,
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'],
                    'worn' => $item['worn'] ?? false,
                    'quantity' => $item['quantity'] ?? 1,
                    'weapon_size' => $item['weapon_size'] ?? 0,
                ]);
            }

            foreach (['hand_1', 'hand_2', 'hand_3', 'hand_4'] as $handName) {
                CharacterHand::create([
                    'character_id' => $character->id,
                    'name' => $handName,
                    'enabled' => in_array($handName, ['hand_1', 'hand_2'], true),
                ]);
            }

            foreach (['accessory_1', 'accessory_2', 'accessory_3', 'accessory_4', 'accessory_5'] as $accessoryName) {
                CharacterAccessory::create([
                    'character_id' => $character->id,
                    'name' => $accessoryName,
                    'enabled' => $accessoryName !== 'accessory_5',
                ]);
            }

            foreach ($request->input('power_ids', []) as $powerId) {
                $power = Power::find($powerId);
                CharacterActiveEffect::create([
                    'character_id' => $character->id,
                    'power_id' => $powerId,
                    'is_active' => $power?->usability === 'passive',
                ]);
            }

            return $character;
        });

        return response()->json($character->load(['levels', 'inventory', 'hands', 'accessorySlots', 'activeEffects', 'golpesPessoais']), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $character = Character::where('id', $id)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $character->update($request->only(['current_pv', 'current_pm', 'tibares', 'xp', 'base_str', 'base_dex', 'base_con', 'base_int', 'base_knw', 'base_car', 'is_dead']));

        return response()->json($character);
    }

    public function destroy(int $id): JsonResponse
    {
        $character = Character::where('id', $id)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $character->delete();

        return response()->json(null, 204);
    }
}
