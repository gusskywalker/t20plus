<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'is_material', 'extra_cost', 'categories', 'restrictions', 'effects', 'prerequisites', 'incompatible_ids'])]
class ItemImprovement extends Model
{
    protected $casts = [
        'categories' => 'array',
        'restrictions' => 'array',
        'effects' => 'array',
        'prerequisites' => 'array',
        'incompatible_ids' => 'array',
        'extra_cost' => 'array',
        'is_material' => 'boolean',
    ];
}
