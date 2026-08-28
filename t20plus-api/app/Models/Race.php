<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'mod_str',
    'mod_dex',
    'mod_con',
    'mod_int',
    'mod_knw',
    'mod_car',
    'mod_other',
    'base_movement',
    'base_size',
])]
class Race extends Model
{
}
