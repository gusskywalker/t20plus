<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'mod_def', 'armor_penalty', 'cost', 'slots', 'effects', 'is_exoteric'])]
class Armor extends Model
{
    protected $casts = [
        'effects' => 'array',
    ];
}
