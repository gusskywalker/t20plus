<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['character_id', 'power_id', 'is_active', 'is_favorite', 'custom_effect', 'other_sources_state', 'source_inventory_id'])]
class CharacterActiveEffect extends Model
{
    protected $hidden = ['source_key'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_favorite' => 'boolean',
        'custom_effect' => 'array',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function power(): BelongsTo
    {
        return $this->belongsTo(Power::class);
    }
}
