<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterGolpePessoal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterGolpePessoalController extends Controller
{

    public function update(Request $request, int $characterId, int $golpePessoalId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $guerreiroLevel = $character->levels()->where('class_id', 1)->count();

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
