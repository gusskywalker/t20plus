<?php

namespace App\Http\Controllers;

use App\Http\Traits\HasUserContext;
use App\Models\Character;
use Illuminate\Http\JsonResponse;

class CharacterController extends Controller
{
    use HasUserContext;

    /**
     * List the authenticated user's own characters.
     */
    public function index(): JsonResponse
    {
        $characters = Character::where('user_id', auth('api')->id())->with('campaign')->get();

        return response()->json($characters);
    }
}
