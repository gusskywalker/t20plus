<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['character_id', 'level', 'class_id', 'class_level', 'power_id', 'spell_ids'])]
class CharacterLevel extends Model
{
    protected $casts = [
        'spell_ids' => 'array',
    ];

    public function characterClass(): BelongsTo
    {
        return $this->belongsTo(CharacterClass::class, 'class_id');
    }
}
