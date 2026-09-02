<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['character_id', 'name', 'enabled', 'inventory_id'])]
class CharacterAccessory extends Model
{
    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(CharacterInventory::class, 'inventory_id');
    }
}
