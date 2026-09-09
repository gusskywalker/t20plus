<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['character_id', 'power_id', 'is_active', 'is_favorite'])]
class CharacterActiveEffect extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
        'is_favorite' => 'boolean',
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
