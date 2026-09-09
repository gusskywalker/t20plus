<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterActiveEffect;
use App\Models\Power;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterActiveEffectController extends Controller
{

    private const MARCA_DA_PRESA_POWER_IDS = [196, 197, 198, 199, 200];

    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $powerId = $request->input('power_id');
        $power = Power::find($powerId);

        CharacterActiveEffect::firstOrCreate(
            ['character_id' => $characterId, 'power_id' => $powerId],
            ['is_active' => $power?->usability === 'passive'],
        );

        return response()->json($character->activeEffects()->get());
    }

    public function update(Request $request, int $characterId, int $activeEffectId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $effect = CharacterActiveEffect::where('id', $activeEffectId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        DB::transaction(function () use ($request, $effect) {
            $effect->update($request->only(['is_active', 'is_favorite']));

            if ($effect->is_active && in_array($effect->power_id, self::MARCA_DA_PRESA_POWER_IDS, true)) {
                CharacterActiveEffect::where('character_id', $effect->character_id)
                    ->whereIn('power_id', self::MARCA_DA_PRESA_POWER_IDS)
                    ->where('id', '!=', $effect->id)
                    ->update(['is_active' => false]);
            }
        });

        return response()->json($character->activeEffects()->get());
    }

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
