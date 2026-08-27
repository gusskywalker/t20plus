<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Dev-only login: skips real auth entirely and issues a JWT for the
     * seeded user. Swap this for real Google login later — everything
     * downstream (auth:api middleware, UserScope, HasUserContext) stays
     * the same since it only cares about the JWT, not how it was issued.
     */
    public function devLogin(): JsonResponse
    {
        $user = User::findOrFail(1);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }
}
