<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasUserContext;
use App\Models\Character;
use App\Models\CharacterInventory;
use App\Models\CharacterLevel;
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
            ->with(['campaign', 'race', 'portrait', 'god', 'origin', 'levels.characterClass'])
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
            'power_ids',
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
                ]);
            }

            return $character;
        });

        return response()->json($character->load(['levels', 'inventory']), 201);
    }

    /**
     * Update a character's own live state — for now just current_pv/
     * current_pm (e.g. the character sheet filling them in from null the
     * first time it loads, or later actual damage/healing/PM spend).
     * Ownership-scoped the same way show() is.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $character = Character::where('id', $id)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $character->update($request->only(['current_pv', 'current_pm']));

        return response()->json($character);
    }
}
