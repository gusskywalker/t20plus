<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'cost', 'proficiency_id', 'purpose', 'is_firearm', 'grip', 'base_dmg', 'base_margin', 'base_multiplier', 'base_reach', 'damage_type', 'slots', 'ability_ids', 'effects', 'pre_applied_upgrade_ids', 'is_exoteric', 'icon_file_name'])]
class Weapon extends Model
{
    protected $casts = [
        'ability_ids' => 'array',
        'effects' => 'array',
        'pre_applied_upgrade_ids' => 'array',
        'is_firearm' => 'boolean',
        'is_exoteric' => 'boolean',
    ];
}
