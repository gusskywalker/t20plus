<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'categories', 'restrictions', 'effects', 'prerequisites', 'incompatible_ids'])]
class ItemEnchantment extends Model
{
    protected $casts = [
        'categories' => 'array',
        'restrictions' => 'array',
        'effects' => 'array',
        'prerequisites' => 'array',
        'incompatible_ids' => 'array',
    ];
}
