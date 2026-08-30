<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'type', 'mod_def', 'armor_penalty', 'cost', 'slots', 'effects'])]
class Armor extends Model
{
    protected $casts = [
        'effects' => 'array',
    ];
}
