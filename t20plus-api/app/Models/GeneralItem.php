<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'cost', 'slots', 'icon_file_name', 'effects', 'consumable', 'base_dmg'])]
class GeneralItem extends Model
{
    protected $casts = [
        'effects' => 'array',
        'consumable' => 'boolean',
        // Without this, the DECIMAL(4,1) column serializes as the string
        // "1.0" (padded to its declared scale) instead of a real JSON
        // number — a plain int-column slots value like weapons/armors never
        // has this problem. Casting to float here means JS receives an
        // actual number (String(1) === "1"), not a padded decimal string.
        'slots' => 'float',
    ];
}
