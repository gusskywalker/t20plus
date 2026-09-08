<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
