<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Table is character_golpes_pessoais — Eloquent's auto-pluralization would
// mangle "golpe_pessoal" (an English-grammar pluralizer doesn't know
// Portuguese), so the table name is set explicitly instead of relying on
// the model-name convention.
#[Fillable(['character_id', 'name', 'guerreiro_level_picked', 'power_ids'])]
class CharacterGolpePessoal extends Model
{
    protected $table = 'character_golpes_pessoais';

    protected $casts = [
        'power_ids' => 'array',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
