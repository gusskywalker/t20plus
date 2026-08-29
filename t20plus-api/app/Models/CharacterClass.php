<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'initial_pv', 'initial_pm', 'level_pv', 'level_pm'])]
class CharacterClass extends Model
{
    protected $table = 'classes';
}
