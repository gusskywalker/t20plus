<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'initial_pv', 'initial_pm', 'level_pv', 'level_pm', 'skills', 'divine_power_picks', 'proficiency_ids'])]
class CharacterClass extends Model
{
    protected $table = 'classes';

    protected $casts = [
        'skills' => 'array',
        'proficiency_ids' => 'array',
    ];
}
