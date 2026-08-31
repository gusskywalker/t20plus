<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'power_ids'])]
class Complication extends Model
{
    protected $casts = [
        'power_ids' => 'array',
    ];
}
