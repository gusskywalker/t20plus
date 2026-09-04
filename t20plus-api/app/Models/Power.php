<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'usability', 'action_cost', 'duration', 'decay_after', 'range', 'pm_cost', 'prerequisites', 'effects', 'icon_file_name'])]
class Power extends Model
{
    protected $casts = [
        'prerequisites' => 'array',
        'effects' => 'array',
    ];
}
