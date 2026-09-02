<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'cost', 'slots', 'icon_id', 'effects', 'consumable', 'base_dmg'])]
class GeneralItem extends Model
{
    protected $casts = [
        'effects' => 'array',
        'consumable' => 'boolean',
    ];
}
