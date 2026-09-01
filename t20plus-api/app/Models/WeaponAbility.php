<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'power_ids'])]
class WeaponAbility extends Model
{
    protected $casts = [
        'power_ids' => 'array',
    ];
}
