<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'circle', 'school', 'action_cost', 'range', 'effect', 'duration', 'resistance', 'reagent_ids', 'effects', 'enhancements', 'icon_file_name'])]
class Spell extends Model
{
    protected $casts = [
        'reagent_ids' => 'array',
        'effects' => 'array',
        'enhancements' => 'array',
    ];
}
