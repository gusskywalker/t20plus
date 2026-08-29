<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'key_attribute', 'trained_only', 'armor_penalty'])]
class Skill extends Model
{
}
