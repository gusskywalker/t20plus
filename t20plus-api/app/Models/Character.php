<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
