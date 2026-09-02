<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasUserContext;
use App\Models\Character;
use App\Models\CharacterAccessory;
use App\Models\CharacterActiveEffect;
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

    /**
     * List the authenticated user's own characters.
     */
    public function index(): JsonResponse
    {
        $characters = Character::where('user_id', auth('api')->id())
            ->with(['campaign', 'race', 'portrait', 'god'])
            ->get();

        return response()->json($characters);
    }

    /**
     * Show a single character — scoped to the authenticated user the same
     * way index() is, so typing another id in the URL 404s instead of
     * leaking someone else's character.
     */
    public function show(int $id): JsonResponse
    {
        $character = Character::where('id', $id)
            ->where('user_id', auth('api')->id())
            ->with(['campaign', 'race', 'portrait', 'god', 'origin', 'levels.characterClass', 'inventory', 'hands', 'accessorySlots', 'activeEffects'])
            ->firstOrFail();

        return response()->json($character);
    }

    /**
     * Create a character from the finished creation wizard's draft — the
     * character's own facts plus its per-level class/power rows and
     * starting inventory, all in one request/transaction since they're
     * meaningless without each other.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $this->addUserId($request->only([
            'name',
            'level',
            'base_str',
            'base_dex',
            'base_con',
            'base_int',
            'base_knw',
            'base_car',
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
            }

            foreach ($request->input('inventory', []) as $item) {
                CharacterInventory::create([
                    'character_id' => $character->id,
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'],
                    'worn' => $item['worn'] ?? false,
                    'quantity' => $item['quantity'] ?? 1,
                ]);
            }

            // Every character gets all 4 hand rows up front — only
            // hand_1/hand_2 start enabled (the standard 2-armed default),
            // hand_3/hand_4 sit disabled until a future add_arm power
            // effect flips them on.
            foreach (['hand_1', 'hand_2', 'hand_3', 'hand_4'] as $handName) {
                CharacterHand::create([
                    'character_id' => $character->id,
                    'name' => $handName,
                    'enabled' => in_array($handName, ['hand_1', 'hand_2'], true),
                ]);
            }

            // Same convention — all 5 rows up front, only accessory_1..4
            // start enabled (the default T20 accessory limit), accessory_5
            // sits disabled until a future power effect unlocks it.
            foreach (['accessory_1', 'accessory_2', 'accessory_3', 'accessory_4', 'accessory_5'] as $accessoryName) {
                CharacterAccessory::create([
                    'character_id' => $character->id,
                    'name' => $accessoryName,
                    'enabled' => $accessoryName !== 'accessory_5',
                ]);
            }

            // Starting powers (whatever origin/god/race/complication/etc.
            // granted at creation) land straight in character_active_effects
            // — no power_ids snapshot on the character row itself, see
            // create_characters_table.php. is_active starts true only for
            // passive powers (always on, nothing to toggle) — see
            // create_character_active_effects_table.php.
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

        return response()->json($character->load(['levels', 'inventory', 'hands', 'accessorySlots', 'activeEffects']), 201);
    }

    /**
     * Update a character's own live state — current_pv/current_pm (the
     * character sheet filling them in from null the first time it loads,
     * or later actual damage/healing/PM spend) and tibares (spending/
     * earning gold post-creation). Ownership-scoped the same way show()
     * is.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $character = Character::where('id', $id)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $character->update($request->only(['current_pv', 'current_pm', 'tibares']));

        return response()->json($character);
    }

    /**
     * Delete a character outright. Ownership-scoped the same way show()/
     * update() are. levels/inventory/hands/accessorySlots all
     * cascadeOnDelete at the DB level, so this is just the one row.
     */
    public function destroy(int $id): JsonResponse
    {
        $character = Character::where('id', $id)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $character->delete();

        return response()->json(null, 204);
    }
}
