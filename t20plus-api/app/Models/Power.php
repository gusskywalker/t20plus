<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'name', 'description', 'source', 'usability', 'default_checked', 'action_cost', 'duration', 'decay_after', 'range', 'pm_cost', 'prerequisites', 'effects', 'applies_when', 'icon_file_name'])]
class Power extends Model
{
    protected $casts = [
        'prerequisites' => 'array',
        'effects' => 'array',
        'applies_when' => 'array',
        'default_checked' => 'boolean',
    ];

    /** Spell ids this power grants access to via grant_or_reduce_spell_pm_cost_by_1 (e.g. Pakk). */
    public function grantedOtherSourceSpellIds(): array
    {
        return collect($this->effects ?? [])
            ->where('tag', 'grant_or_reduce_spell_pm_cost_by_1')
            ->where('op', 'grant')
            ->pluck('spell_id')
            ->filter(fn ($spellId) => $spellId !== null)
            ->values()
            ->all();
    }

    /** Spell ids this power grants as genuinely known via grant_spell (e.g. Familiar (T'peel)) — no PM discount involved, straight into spell_ids. */
    public function grantedSpellIds(): array
    {
        return collect($this->effects ?? [])
            ->where('tag', 'grant_spell')
            ->where('op', 'grant')
            ->pluck('spell_id')
            ->filter(fn ($spellId) => $spellId !== null)
            ->values()
            ->all();
    }

    /** Weapon ids this power grants as a natural weapon via grants_natural_weapon (e.g. Minotauro's Chifres). */
    public function grantedNaturalWeaponIds(): array
    {
        return collect($this->effects ?? [])
            ->where('tag', 'grants_natural_weapon')
            ->where('op', 'grant')
            ->pluck('weapon_id')
            ->filter(fn ($weaponId) => $weaponId !== null)
            ->values()
            ->all();
    }
}
