<?php

namespace App\Http\Controllers;

use App\Http\Traits\ManagesPowers;
use App\Models\Character;
use App\Models\CharacterActiveEffect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterActiveEffectController extends Controller
{
    use ManagesPowers;

    private const MARCA_DA_PRESA_POWER_IDS = [196, 197, 198, 199, 200];

    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $powerId = (int) $request->input('power_id');

        // grantPower owns the "already granted?" check itself now, so a
        // repeat grant from a different source can flip other_sources_state
        // instead of being silently skipped before ever reaching it.
        $this->grantPower($character, $powerId);

        // Full character, not just the active_effects list — grantPower can
        // also touch the character's own base_* columns (Aumentar Atributo)
        // or create a golpes_pessoais row (power 115), which the caller
        // needs to cache too.
        return response()->json($character->fresh(['activeEffects', 'golpesPessoais', 'hands']));
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
            $effect->update($request->only(['is_active', 'is_favorite', 'custom_effect']));

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

        $effect = CharacterActiveEffect::where('id', $activeEffectId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        $this->revokePower($character, $effect->power_id);

        // Full character, not just the active_effects list — revokePower
        // can also touch base_* columns or delete a golpes_pessoais row,
        // and can take child powers with it — the caller needs all of that
        // cached too.
        return response()->json($character->fresh(['activeEffects', 'golpesPessoais', 'hands']));
    }
}
