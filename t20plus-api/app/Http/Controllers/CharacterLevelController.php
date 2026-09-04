<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\JsonResponse;

class CharacterLevelController extends Controller
{
    /**
     * Reduzir Nível — deletes the character's highest-numbered
     * character_levels row. character.level (Character::level()) recomputes
     * on its own from what's left. No-ops at level 1 — that row is the
     * character's only class/level, deleting it would leave a classless
     * character. Ownership-scoped like every other character-child route.
     */
    public function destroy(int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        if ($character->levels()->count() > 1) {
            $character->levels()->orderByDesc('level')->first()->delete();
        }

        return response()->json($character->fresh('levels.characterClass'));
    }
}
