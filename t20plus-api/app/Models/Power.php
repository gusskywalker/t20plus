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

    /** Spell ids this power grants access to via add_or_reduce_spell_pm_cost_by_1 (e.g. Pakk). */
    public function grantedOtherSourceSpellIds(): array
    {
        return collect($this->effects ?? [])
            ->where('tag', 'add_or_reduce_spell_pm_cost_by_1')
            ->where('op', 'grant')
            ->pluck('spell_id')
            ->filter(fn ($spellId) => $spellId !== null)
            ->values()
            ->all();
    }
}
