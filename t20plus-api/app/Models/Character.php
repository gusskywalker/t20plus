<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
    'race_id',
    'origin_id',
    'god_id',
    'portrait_id',
    'trained_skill_ids',
    'age',
    'age_bracket',
    'complication_ids',
    'power_ids',
])]
class Character extends Model
{
    protected $casts = [
        'trained_skill_ids' => 'array',
        'complication_ids' => 'array',
        'power_ids' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Character $character) {
            if (!$character->secret_code) {
                $character->secret_code = static::generateSecretCode();
            }
        });
    }

    /**
     * A short code other players use to look this character up (e.g. to
     * add them to a campaign) without exposing the numeric id. Retries on
     * the rare collision instead of trusting a single random draw.
     */
    private static function generateSecretCode(): string
    {
        do {
            $code = strtoupper(Str::random(5));
        } while (static::where('secret_code', $code)->exists());

        return $code;
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(CharacterLevel::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(CharacterInventory::class);
    }
}
