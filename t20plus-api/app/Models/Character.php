<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'campaign_id',
    'name',
    'level',
    'secret_code',
    'base_str',
    'base_dex',
    'base_con',
    'base_int',
    'base_knw',
    'base_car',
])]
class Character extends Model
{
}
