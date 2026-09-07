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
    /**
     * Marca da Presa's 5 tiers (ClassCacadorPowerSeeder.php) — a leveled-up
     * Caçador holds every tier they've ever unlocked as a separate granted
     * power simultaneously (same shape as Ataque Especial), but only one
     * mark can ever be active at once. See update()'s own exclusivity
     * check below.
     */
    private const MARCA_DA_PRESA_POWER_IDS = [196, 197, 198, 199, 200];

    /**
     * Grant a power's active effect directly from the character sheet —
     * the "Adicionar Efeito" button offers every power in the catalog with
     * no filtering (prerequisites, type, whether it makes narrative sense),
     * since this is a free-form GM/dev tool, not the character-creation
     * wizard's own guided flow. firstOrCreate so re-adding an already-
     * active power is a harmless no-op instead of a unique-constraint
     * error. is_active starts true only for passive powers, same rule as
     * character creation — see create_character_active_effects_table.php.
     * Returns the character's full active_effects, same convention as
     * destroy() below.
     */
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

    /**
     * Toggle one active-effect row's is_active — the sheet's Ativar/
     * Desativar button on an 'active'-usability power. Ownership-scoped
     * through the parent character the same way every other route here is.
     * Returns the character's full active_effects, same convention as
     * store()/destroy().
     *
     * Only one Marca da Presa tier can be active at a time — activating
     * one deactivates every other tier this character has, same
     * exclusivity shape as CharacterInventoryController's "only one armor
     * worn at once."
     */
    public function update(Request $request, int $characterId, int $activeEffectId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $effect = CharacterActiveEffect::where('id', $activeEffectId)
            ->where('character_id', $characterId)
            ->firstOrFail();

        DB::transaction(function () use ($request, $effect) {
            $effect->update($request->only(['is_active']));

            if ($effect->is_active && in_array($effect->power_id, self::MARCA_DA_PRESA_POWER_IDS, true)) {
                CharacterActiveEffect::where('character_id', $effect->character_id)
                    ->whereIn('power_id', self::MARCA_DA_PRESA_POWER_IDS)
                    ->where('id', '!=', $effect->id)
                    ->update(['is_active' => false]);
            }
        });

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
