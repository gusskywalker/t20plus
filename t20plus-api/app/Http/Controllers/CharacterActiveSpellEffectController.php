<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterActiveSpellEffect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterActiveSpellEffectController extends Controller
{

    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        // A row's mere existence means it's active — no is_active flag,
        // Remover deletes the row outright instead. Re-casting the same
        // spell overwrites its existing row (same character_id + spell_id)
        // instead of piling up duplicates — the old cast is meaningless
        // once a new one replaces it, same as if it had never been there.
        CharacterActiveSpellEffect::updateOrCreate(
            ['character_id' => $characterId, 'spell_id' => $request->input('spell_id')],
            [
                'effects' => $request->input('effects'),
                'chosen_enhancement_indices' => $request->input('chosen_enhancement_indices'),
            ],
        );

        return response()->json($character->activeSpellEffects()->get());
    }

    public function destroy(int $characterId, int $activeSpellEffectId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        CharacterActiveSpellEffect::where('id', $activeSpellEffectId)
            ->where('character_id', $characterId)
            ->firstOrFail()
            ->delete();

        return response()->json($character->activeSpellEffects()->get());
    }
}
