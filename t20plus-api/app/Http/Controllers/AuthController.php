<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function googleLogin(Request $request): JsonResponse
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        $accessToken = $request->input('access_token');

        $tokenInfo = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'access_token' => $accessToken,
        ]);

        if (! $tokenInfo->successful() || $tokenInfo->json('aud') !== config('services.google.client_id')) {
            return response()->json(['message' => 'Invalid Google token'], 401);
        }

        $profile = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (! $profile->successful()) {
            return response()->json(['message' => 'Invalid Google token'], 401);
        }

        $googleUser = $profile->json();

        $user = User::where('google_id', $googleUser['sub'])->first();

        if (! $user) {
            $user = User::where('email', $googleUser['email'])->first();
        }

        if ($user) {
            if (! $user->google_id) {
                $user->update(['google_id' => $googleUser['sub']]);
            }
        } else {
            $user = User::create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['sub'],
                'password' => null,
            ]);
        }

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
