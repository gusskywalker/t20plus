<?php

namespace App\Http\Traits;

trait HasUserContext
{

    protected function addUserId(array $data): array
    {
        $data['user_id'] = auth('api')->id();

        return $data;
    }
}
