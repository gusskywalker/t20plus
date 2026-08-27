<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $userId = $this->resolveUserId();

        if ($userId !== null) {
            $builder->where($model->getTable().'.user_id', $userId);
        }
    }

    private function resolveUserId(): ?int
    {
        try {
            if ($payload = JWTAuth::getPayload()) {
                return $payload->get('user_id');
            }
        } catch (\Throwable) {
            // No token on the request (e.g. console/tinker) — fall through.
        }

        return Auth::id();
    }
}
