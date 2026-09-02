<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['character_id', 'power_id'])]
class CharacterActiveEffect extends Model
{
    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function power(): BelongsTo
    {
        return $this->belongsTo(Power::class);
    }
}
