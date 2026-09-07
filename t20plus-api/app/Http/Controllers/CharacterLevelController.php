<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterActiveEffect;
use App\Models\CharacterGolpePessoal;
use App\Models\CharacterLevel;
use App\Models\Power;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterLevelController extends Controller
{
    /**
     * Subir Nível — appends one new character_levels row (level = current
     * max + 1, class_level = that class's own running count + 1, same
     * counting rule character-creation-step-9 uses) and, if the new level
     * offers one, grants power_id straight into character_active_effects —
     * same is_active-by-usability rule and Golpe Pessoal (power 115) extra
     * row as CharacterController::store()'s own per-level loop. Ownership-
     * scoped like every other character-child route.
     */
    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $classId = (int) $request->input('class_id');
        $powerId = $request->input('power_id');

        DB::transaction(function () use ($character, $classId, $powerId) {
            $newLevel = (int) $character->levels()->max('level') + 1;
            $classLevel = $character->levels()->where('class_id', $classId)->count() + 1;

            CharacterLevel::create([
                'character_id' => $character->id,
                'level' => $newLevel,
                'class_id' => $classId,
                'class_level' => $classLevel,
                'power_id' => $powerId,
            ]);

            if ($powerId !== null) {
                $power = Power::find($powerId);
                CharacterActiveEffect::create([
                    'character_id' => $character->id,
                    'power_id' => $powerId,
                    'is_active' => $power?->usability === 'passive',
                ]);

                if ((int) $powerId === 115) {
                    CharacterGolpePessoal::create([
                        'character_id' => $character->id,
                    ]);
                }
            }
        });

        return response()->json($character->fresh(['levels.characterClass', 'activeEffects', 'golpesPessoais']));
    }

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
