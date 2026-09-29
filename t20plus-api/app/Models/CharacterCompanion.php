<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['character_id', 'companion_id', 'name', 'extra_character_related_effects', 'companion_related_effects', 'granted_spells', 'current_pv'])]
class CharacterCompanion extends Model
{
    protected $casts = [
        'extra_character_related_effects' => 'array',
        'companion_related_effects' => 'array',
        'granted_spells' => 'array',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function companion(): BelongsTo
    {
        return $this->belongsTo(Companion::class);
    }
}
