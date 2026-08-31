<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'is_material', 'extra_cost', 'applies_to', 'effects'])]
class ItemImprovement extends Model
{
    protected $casts = [
        'applies_to' => 'array',
        'effects' => 'array',
    ];
}
