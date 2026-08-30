<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'effects'])]
class Origin extends Model
{
    protected $casts = [
        'effects' => 'array',
    ];
}
