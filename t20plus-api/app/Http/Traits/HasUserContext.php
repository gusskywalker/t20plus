<?php

namespace App\Http\Traits;

trait HasUserContext
{
    /**
     * Stamp the authenticated user's id onto the given data, overwriting
     * anything a client might have sent — the JWT is the only source of
     * truth for user_id on writes.
     *
     * Uses the 'api' guard, same as CharacterController::index() — NOT the
     * bare JWTAuth facade's getPayload(), which doesn't know which token to
     * read unless parseToken() is called first and throws "A token is
     * required" otherwise (this bit before; the guard resolves the
     * request's token correctly on its own).
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function addUserId(array $data): array
    {
        $data['user_id'] = auth('api')->id();

        return $data;
    }
}
