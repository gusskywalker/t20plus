<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'source', 'usability', 'default_checked', 'action_cost', 'duration', 'decay_after', 'range', 'pm_cost', 'prerequisites', 'effects', 'visibility_reqs', 'icon_file_name'])]
class Power extends Model
{
    protected $casts = [
        'prerequisites' => 'array',
        'effects' => 'array',
        'visibility_reqs' => 'array',
        'default_checked' => 'boolean',
    ];
}
