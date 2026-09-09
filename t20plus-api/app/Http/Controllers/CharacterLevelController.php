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

            $classLevelCounts = [];
            foreach ($character->levels()->get() as $level) {
                $classLevelCounts[$level->class_id] = ($classLevelCounts[$level->class_id] ?? 0) + 1;
            }

            $alreadyGrantedPowerIds = $character->activeEffects()->pluck('power_id')->all();

            foreach (Power::where('source', 'class_granted')->get() as $classGrantedPower) {
                if (in_array($classGrantedPower->id, $alreadyGrantedPowerIds, true)) {
                    continue;
                }

                $qualifies = collect($classGrantedPower->prerequisites ?? [])->contains(function ($prerequisite) use ($classLevelCounts) {
                    if (($prerequisite['type'] ?? null) !== 'class') {
                        return false;
                    }
                    foreach ($prerequisite['class_ids'] ?? [] as $prerequisiteClassId) {
                        if (($classLevelCounts[$prerequisiteClassId] ?? 0) >= ($prerequisite['min_level'] ?? 0)) {
                            return true;
                        }
                    }
                    return false;
                });

                if ($qualifies) {
                    CharacterActiveEffect::create([
                        'character_id' => $character->id,
                        'power_id' => $classGrantedPower->id,
                        'is_active' => $classGrantedPower->usability === 'passive',
                    ]);
                }
            }
        });

        return response()->json($character->fresh(['levels.characterClass', 'activeEffects', 'golpesPessoais']));
    }

    public function destroy(int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        DB::transaction(function () use ($character) {
            if ($character->levels()->count() <= 1) {
                return;
            }

            $level = $character->levels()->orderByDesc('level')->first();

            if ($level->power_id !== null) {
                $power = Power::find($level->power_id);

                $attributeFields = ['base_str', 'base_dex', 'base_con', 'base_int', 'base_knw', 'base_car'];
                foreach ($power?->effects ?? [] as $effect) {
                    if (($effect['op'] ?? null) !== 'add' || !str_starts_with($effect['tag'] ?? '', 'mod_base_')) {
                        continue;
                    }
                    $field = 'base_' . substr($effect['tag'], strlen('mod_base_'));
                    if (in_array($field, $attributeFields, true)) {
                        $character->decrement($field, (int) ($effect['value'] ?? 0));
                    }
                }

                CharacterActiveEffect::where('character_id', $character->id)
                    ->where('power_id', $level->power_id)
                    ->delete();
            }

            $level->delete();
        });

        return response()->json($character->fresh(['levels.characterClass', 'activeEffects']));
    }
}
