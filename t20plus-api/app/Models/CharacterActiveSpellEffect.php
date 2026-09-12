<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['character_id', 'spell_id', 'caster_character_id', 'effects', 'chosen_enhancement_indices'])]
class CharacterActiveSpellEffect extends Model
{
    protected $casts = [
        'effects' => 'array',
        'chosen_enhancement_indices' => 'array',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function spell(): BelongsTo
    {
        return $this->belongsTo(Spell::class);
    }
}
