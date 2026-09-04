<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'cost', 'slots', 'effects', 'mp_cost', 'is_exoteric', 'icon_file_name'])]
class Accessory extends Model
{
    protected $casts = [
        'effects' => 'array',
    ];
}
