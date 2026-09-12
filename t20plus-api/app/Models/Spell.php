<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'circle', 'school', 'usability', 'damage_type', 'action_cost', 'range', 'info_affects', 'info_affected_area', 'buff_affects', 'buff_base_max_targets', 'duration', 'resistance', 'reagent_ids', 'effects', 'enhancements', 'icon_file_name'])]
class Spell extends Model
{
    protected $casts = [
        'reagent_ids' => 'array',
        'buff_affects' => 'array',
        'effects' => 'array',
        'enhancements' => 'array',
    ];
}
