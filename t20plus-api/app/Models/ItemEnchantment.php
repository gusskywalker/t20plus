<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'applies_to', 'effects', 'prerequisites'])]
class ItemEnchantment extends Model
{
    protected $casts = [
        'applies_to' => 'array',
        'effects' => 'array',
        'prerequisites' => 'array',
    ];
}
