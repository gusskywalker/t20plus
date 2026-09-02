<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'name',
    'mod_str',
    'mod_dex',
    'mod_con',
    'mod_int',
    'mod_knw',
    'mod_car',
    'mod_other',
    'mod_other_excluded_attributes',
    'base_movement',
    'base_size',
])]
class Race extends Model
{
    protected $casts = [
        'mod_other_excluded_attributes' => 'array',
    ];
}
