<?php

namespace App\Http\Controllers;

use App\Http\Traits\ManagesPowers;
use App\Models\Character;
use App\Models\CharacterLevel;
use App\Models\Power;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterLevelController extends Controller
{
    use ManagesPowers;

    public function store(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $classId = (int) $request->input('class_id');
        $powerId = $request->input('power_id');
        $spellIds = $request->input('spell_ids');

        DB::transaction(function () use ($character, $classId, $powerId, $spellIds) {
            $newLevel = (int) $character->levels()->max('level') + 1;
            $classLevel = $character->levels()->where('class_id', $classId)->count() + 1;
            $power = $powerId !== null ? Power::find($powerId) : null;
            $otherSourceSpellIds = $power?->grantedOtherSourceSpellIds() ?? [];
            $mergedSpellIds = array_unique([...($spellIds ?? []), ...($power?->grantedSpellIds() ?? [])]);

            CharacterLevel::create([
                'character_id' => $character->id,
                'level' => $newLevel,
                'class_id' => $classId,
                'class_level' => $classLevel,
                'power_id' => $powerId,
                'spell_ids' => empty($mergedSpellIds) ? null : array_values($mergedSpellIds),
                'other_source_spell_ids' => empty($otherSourceSpellIds) ? null : $otherSourceSpellIds,
            ]);

            if ($powerId !== null) {
                $this->grantPower($character, (int) $powerId);
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
                    $this->grantPower($character, $classGrantedPower->id);
                }
            }
        });

        return response()->json($character->fresh(['levels.characterClass', 'activeEffects', 'golpesPessoais']));
    }

    /** Adicionar Magia — manually learns a spell outside the normal level-up slot flow (e.g. Conhecimento Mágico), appended onto the chosen class's own highest character_levels row. */
    public function addSpell(Request $request, int $characterId): JsonResponse
    {
        $character = Character::where('id', $characterId)
            ->where('user_id', auth('api')->id())
            ->firstOrFail();

        $classId = (int) $request->input('class_id');
        $spellId = (int) $request->input('spell_id');

        $level = $character->levels()->where('class_id', $classId)->orderByDesc('level')->firstOrFail();
        $spellIds = $level->spell_ids ?? [];
        if (!in_array($spellId, $spellIds, true)) {
            $spellIds[] = $spellId;
        }
        $level->update(['spell_ids' => $spellIds]);

        return response()->json($character->levels()->with('characterClass')->get());
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
                $this->revokePower($character, $level->power_id);
            }

            $level->delete();
        });

        return response()->json($character->fresh(['levels.characterClass', 'activeEffects', 'golpesPessoais']));
    }
}
