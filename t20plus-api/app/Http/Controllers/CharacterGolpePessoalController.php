<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterGolpePessoal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterGolpePessoalController extends Controller
{
    /**
     * Build or rebuild one golpe slot — the character-sheet modal's Salvar
     * button. Stamps guerreiro_level_picked to the character's CURRENT
     * Guerreiro level (count of character_levels rows for that class, same
     * as the frontend's own maxPm()) — this is what gates rebuilding: the
     * modal only allows editing again once that number has gone up.
     * Ownership-scoped through the parent character the same way every
     * other character-child route is. Returns the character's full
     * golpes_pessoais, same convention as CharacterActiveEffectController.
     */
    public function update(Request $request, int $characterId, int $golpePessoalId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $guerreiroLevel = $character->levels()->where('class_id', 1)->count(); // Guerreiro

        CharacterGolpePessoal::where('id', $golpePessoalId)
            ->where('character_id', $characterId)
            ->firstOrFail()
            ->update([
                'name' => $request->input('name'),
                'power_ids' => $request->input('power_ids'),
                'guerreiro_level_picked' => $guerreiroLevel,
            ]);

        return response()->json($character->golpesPessoais()->get());
    }
}
