<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['character_id', 'item_type', 'item_id', 'worn', 'improvement_ids', 'enchantment_ids'])]
class CharacterInventory extends Model
{
    protected $table = 'character_inventory';

    protected $casts = [
        'improvement_ids' => 'array',
        'enchantment_ids' => 'array',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
