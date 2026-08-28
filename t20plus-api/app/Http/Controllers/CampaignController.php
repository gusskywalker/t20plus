<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasUserContext;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;

class CampaignController extends Controller
{
    use HasUserContext;

    /**
     * List the authenticated user's own campaigns.
     */
    public function index(): JsonResponse
    {
        $campaigns = Campaign::where('user_id', auth('api')->id())->get();

        return response()->json($campaigns);
    }
}
