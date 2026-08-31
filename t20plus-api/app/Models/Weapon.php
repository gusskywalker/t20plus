<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'price', 'proficiency', 'purpose', 'grip', 'base_dmg', 'base_margin', 'base_multiplier', 'base_reach', 'damage_type', 'space', 'abilities'])]
class Weapon extends Model
{
    protected $casts = [
        'abilities' => 'array',
    ];
}
