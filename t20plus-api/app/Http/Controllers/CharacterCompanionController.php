<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Companion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterCompanionController extends Controller
{

    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $companion = Companion::findOrFail($request->input('companion_id'));

        $character->characterCompanions()->create(['companion_id' => $companion->id]);

        return response()->json($character->characterCompanions()->get());
    }

    public function update(Request $request, int $characterId, int $characterCompanionId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $character->characterCompanions()
            ->findOrFail($characterCompanionId)
            ->update($request->only(['current_pv', 'name']));

        return response()->json($character->characterCompanions()->get());
    }

    public function destroy(int $characterId, int $characterCompanionId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $character->characterCompanions()->findOrFail($characterCompanionId)->delete();

        return response()->json($character->characterCompanions()->get());
    }
}
