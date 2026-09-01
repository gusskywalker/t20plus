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
            ->with(['campaign', 'race', 'portrait'])
            ->get();

        return response()->json($characters);
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
}
