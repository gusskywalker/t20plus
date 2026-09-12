<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Character;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterCampaignController extends Controller
{
    // Entrar em Campanha — looks up a campaign by secret_code + password
    // (both must match, same "don't say which one was wrong" reasoning as
    // any login form) and, if found, moves this character into it.
    public function store(Request $request, Character $character): JsonResponse
    {
        if ($character->user_id !== auth('api')->id()) {
            abort(404);
        }

        $campaign = Campaign::where('secret_code', $request->input('secret_code'))
            ->where('password', $request->input('password'))
            ->first();

        if (!$campaign) {
            return response()->json(['message' => 'Dados incorretos.'], 422);
        }

        $character->update(['campaign_id' => $campaign->id]);

        return response()->json([
            'campaign' => $campaign,
            'master_name' => $campaign->user->name,
        ]);
    }
}
