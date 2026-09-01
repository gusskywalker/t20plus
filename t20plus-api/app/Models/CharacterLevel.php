<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['character_id', 'level', 'class_id', 'class_level', 'power_id'])]
class CharacterLevel extends Model
{
}
