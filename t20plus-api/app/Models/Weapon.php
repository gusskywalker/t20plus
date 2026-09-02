<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'cost', 'proficiency_id', 'purpose', 'grip', 'base_dmg', 'base_margin', 'base_multiplier', 'base_reach', 'damage_type', 'slots', 'ability_ids', 'effects', 'is_exoteric', 'icon_id'])]
class Weapon extends Model
{
    protected $casts = [
        'ability_ids' => 'array',
        'effects' => 'array',
    ];
}
