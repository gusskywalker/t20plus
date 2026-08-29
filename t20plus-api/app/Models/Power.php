<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'usability', 'pm_cost', 'prerequisites', 'power_effects'])]
class Power extends Model
{
    protected $casts = [
        'prerequisites' => 'array',
        'power_effects' => 'array',
    ];
}
