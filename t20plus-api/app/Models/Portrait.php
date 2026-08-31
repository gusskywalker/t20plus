<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'file_name', 'race_ids'])]
class Portrait extends Model
{
    protected $casts = [
        'race_ids' => 'array',
    ];
}
