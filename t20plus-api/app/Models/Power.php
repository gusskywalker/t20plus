<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'type', 'usability', 'action_cost', 'duration', 'trigger_on', 'decay_after', 'range', 'pm_cost', 'prerequisites', 'effects'])]
class Power extends Model
{
    protected $casts = [
        'prerequisites' => 'array',
        'effects' => 'array',
        'trigger_on' => 'array',
    ];
}
