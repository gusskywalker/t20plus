<?php

namespace App\Http\Controllers;

use App\Http\Traits\ManagesPowers;
use App\Models\Character;
use App\Models\Companion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterCompanionController extends Controller
{
    use ManagesPowers;

    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $companion = Companion::findOrFail($request->input('companion_id'));

        $count = max(1, min(20, (int) $request->input('count', 1)));
        for ($i = 0; $i < $count; $i++) {
            $this->grantCompanion($character, $companion->id, $request->input('companion_related_effects', []));
        }

        return $this->companionsResponse($character);
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

        $row = $character->characterCompanions()->findOrFail($characterCompanionId);

        if ($row->source_power_id !== null) {
            return response()->json(['message' => 'A companion granted by a power is removed with the power.'], 422);
        }

        $this->revokeCompanion($character, $row);

        return $this->companionsResponse($character);
    }

    private function companionsResponse(Character $character): JsonResponse
    {
        return response()->json([
            'character_companions' => $character->characterCompanions()->get(),
            'active_effects' => $character->activeEffects()->get(),
        ]);
    }
}
