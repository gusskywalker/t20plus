<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'parceiro_type', 'parceiro_tier', 'base_stats', 'character_related_effects', 'source_spell_id', 'source_power_id', 'icon_file_name'])]
class Companion extends Model
{
    protected $casts = [
        'base_stats' => 'array',
        'character_related_effects' => 'array',
    ];
}
