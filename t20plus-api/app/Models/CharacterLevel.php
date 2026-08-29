<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['character_id', 'class_id', 'power_id'])]
class CharacterLevel extends Model
{
}
