<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterActiveEffect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterActiveEffectController extends Controller
{
    /**
     * Grant a power's active effect directly from the character sheet —
     * the "Adicionar Efeito" button offers every power in the catalog with
     * no filtering (prerequisites, type, whether it makes narrative sense),
     * since this is a free-form GM/dev tool, not the character-creation
     * wizard's own guided flow. firstOrCreate so re-adding an already-
     * active power is a harmless no-op instead of a unique-constraint
     * error. Returns the character's full active_effects, same convention
     * as destroy() below.
     */
    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        CharacterActiveEffect::firstOrCreate([
            'character_id' => $characterId,
            'power_id' => $request->input('power_id'),
        ]);

        return response()->json($character->activeEffects()->get());
    }

    /**
     * Remove one active-effect row — ownership-scoped through the parent
     * character the same way every other character-child route is.
     * Returns the character's full remaining active_effects, matching the
     * inventory/hands/accessory destroy endpoints' own convention.
     */
    public function destroy(int $characterId, int $activeEffectId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        CharacterActiveEffect::where('id', $activeEffectId)
            ->where('character_id', $characterId)
            ->firstOrFail()
            ->delete();

        return response()->json($character->activeEffects()->get());
    }
}
