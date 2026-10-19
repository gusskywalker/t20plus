<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasUserContext;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    use HasUserContext;

    public function index(): JsonResponse
    {
        $campaigns = Campaign::where('user_id', auth('api')->id())->get();

        return response()->json($campaigns);
    }

    public function store(Request $request): JsonResponse
    {
        $campaign = Campaign::create($this->addUserId($request->only([
            'name',
            'password',
            'icon_file_name',
        ])));

        return response()->json($campaign);
    }

    // Every character in the campaign, regardless of which user owns them
    // — used by the spell-casting modal's ally-buff picker, which needs
    // the whole party, not just the current user's own characters (unlike
    // CharacterController::index, which is scoped to the logged-in user).
    public function characters(Campaign $campaign): JsonResponse
    {
        $characters = $campaign->characters()->with(['campaign', 'race', 'portrait', 'god', 'levels.characterClass'])->get();

        return response()->json($characters);
    }

    public function fullCharacters(Request $request, Campaign $campaign): JsonResponse
    {
        $ids = array_filter(array_map('intval', explode(',', (string) $request->query('ids', ''))));

        $characters = $campaign->characters()
            ->whereIn('id', $ids)
            ->with(['campaign', 'race', 'portrait', 'god', 'origin', 'levels.characterClass', 'inventory', 'hands', 'accessorySlots', 'activeEffects', 'activeSpellEffects', 'golpesPessoais', 'characterCompanions'])
            ->get();

        return response()->json($characters);
    }

    public function heal(Request $request, Campaign $campaign): JsonResponse
    {
        $userId = auth('api')->id();
        if ($campaign->user_id !== $userId && !$campaign->characters()->where('user_id', $userId)->exists()) {
            abort(403);
        }

        $entries = $request->validate([
            'heals' => ['required', 'array', 'max:20'],
            'heals.*.character_id' => ['required', 'integer'],
            'heals.*.amount' => ['required', 'integer', 'min:1'],
            'heals.*.max_pv' => ['required', 'integer', 'min:1'],
        ])['heals'];

        $result = DB::transaction(function () use ($campaign, $entries) {
            $result = [];
            foreach ($entries as $entry) {
                $character = $campaign->characters()->where('id', $entry['character_id'])->lockForUpdate()->firstOrFail();
                if ($character->is_dead) {
                    continue;
                }
                $current = (int) ($character->current_pv ?? 0);
                $character->current_pv = max($current, min($current + $entry['amount'], $entry['max_pv']));
                $character->save();
                $result[$character->id] = $character->current_pv;
            }

            return $result;
        });

        return response()->json($result);
    }
}
