<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasUserContext;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
