<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'energy_type', 'grants'])]
class God extends Model
{
    protected $casts = [
        'grants' => 'array',
    ];
}
