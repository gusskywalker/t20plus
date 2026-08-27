<?php

namespace App\Http\Traits;

use Tymon\JWTAuth\Facades\JWTAuth;

trait HasUserContext
{
    /**
     * Stamp the authenticated user's id onto the given data, overwriting
     * anything a client might have sent — the JWT is the only source of
     * truth for user_id on writes.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function addUserId(array $data): array
    {
        $data['user_id'] = JWTAuth::getPayload()->get('user_id');

        return $data;
    }
}
