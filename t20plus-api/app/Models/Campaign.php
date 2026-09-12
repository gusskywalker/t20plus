<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'name', 'password', 'icon_file_name'])]
class Campaign extends Model
{
    // Never serialized out — nothing should read this back over the wire,
    // joining a campaign checks it server-side (see secret_code, which
    // stays visible since that one's meant to be shared).
    protected $hidden = ['password'];

    // Case-sensitive, 5 characters drawn from this exact alphabet — the
    // invite code a player types into "Entrar em Campanha" to find this
    // campaign. No i/I/l/L — too easy to mix up with each other (and 1)
    // when read off a screen.
    private const SECRET_CODE_ALPHABET = 'abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ!@#%_';

    protected static function booted(): void
    {
        static::creating(function (Campaign $campaign) {
            if (!$campaign->secret_code) {
                $campaign->secret_code = static::generateSecretCode();
            }
        });
    }

    private static function generateSecretCode(): string
    {
        do {
            $code = '';
            for ($i = 0; $i < 5; $i++) {
                $code .= self::SECRET_CODE_ALPHABET[random_int(0, strlen(self::SECRET_CODE_ALPHABET) - 1)];
            }
        } while (static::where('secret_code', $code)->exists());

        return $code;
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
