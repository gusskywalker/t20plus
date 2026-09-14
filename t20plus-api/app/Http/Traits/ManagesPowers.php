<?php

namespace App\Http\Traits;

use App\Models\Character;
use App\Models\CharacterActiveEffect;
use App\Models\CharacterGolpePessoal;
use App\Models\Power;
use Illuminate\Support\Facades\DB;

trait ManagesPowers
{

    private const GOLPE_PESSOAL_POWER_ID = 115;

    private const BASE_ATTRIBUTE_FIELDS = ['str', 'dex', 'con', 'int', 'knw', 'car'];

    /**
     * Every grant-time edge case a power can carry, in one place — every
     * path that gives a character a power (a real level-up pick,
     * Adicionar Poder, a vessel's child grants) calls this instead of
     * creating the CharacterActiveEffect row itself, so a future edge case
     * only ever needs adding here.
     */
    protected function grantPower(Character $character, int $powerId): void
    {
        DB::transaction(function () use ($character, $powerId) {
            $power = Power::find($powerId);

            CharacterActiveEffect::create([
                'character_id' => $character->id,
                'power_id' => $powerId,
                'is_active' => $power?->usability === 'passive',
            ]);

            if ($powerId === self::GOLPE_PESSOAL_POWER_ID) {
                CharacterGolpePessoal::create(['character_id' => $character->id]);
            }

            foreach ($power?->effects ?? [] as $effect) {
                if (($effect['op'] ?? null) !== 'add' || !str_starts_with($effect['tag'] ?? '', 'mod_base_')) {
                    continue;
                }
                $attribute = substr($effect['tag'], strlen('mod_base_'));
                if (in_array($attribute, self::BASE_ATTRIBUTE_FIELDS, true)) {
                    $character->increment('base_' . $attribute, (int) ($effect['value'] ?? 0));
                }
            }
        });
    }

    /**
     * The reverse of grantPower, same structure — every path that takes a
     * power away from a character (Remover, Reduzir Nível) calls this
     * instead of deleting the CharacterActiveEffect row itself. Also walks
     * the power's own `tag: 'power', op: 'grant'` children (and their own
     * children, recursively) and revokes each one still actually granted,
     * so removing a vessel takes its children with it — the same
     * relationship grantPower's callers already expand on the way in
     * (resolveGrantedPowerIds/grantChildPowers), just walked in reverse.
     */
    protected function revokePower(Character $character, int $powerId): void
    {
        DB::transaction(function () use ($character, $powerId) {
            foreach ($this->resolveDescendantPowerIds($powerId) as $id) {
                $this->revokeSinglePower($character, $id);
            }
        });
    }

    private function resolveDescendantPowerIds(int $rootId): array
    {
        $result = [$rootId];
        $frontier = [$rootId];

        while (!empty($frontier)) {
            $next = [];
            foreach ($frontier as $id) {
                $power = Power::find($id);
                foreach ($power?->effects ?? [] as $effect) {
                    $childId = $effect['power_id'] ?? null;
                    if (($effect['tag'] ?? null) === 'power' && ($effect['op'] ?? null) === 'grant' && $childId !== null && !in_array($childId, $result, true)) {
                        $result[] = $childId;
                        $next[] = $childId;
                    }
                }
            }
            $frontier = $next;
        }

        return $result;
    }

    private function revokeSinglePower(Character $character, int $powerId): void
    {
        $deleted = CharacterActiveEffect::where('character_id', $character->id)->where('power_id', $powerId)->delete();
        if ($deleted === 0) {
            return;
        }

        if ($powerId === self::GOLPE_PESSOAL_POWER_ID) {
            CharacterGolpePessoal::where('character_id', $character->id)->delete();
        }

        $power = Power::find($powerId);
        foreach ($power?->effects ?? [] as $effect) {
            if (($effect['op'] ?? null) !== 'add' || !str_starts_with($effect['tag'] ?? '', 'mod_base_')) {
                continue;
            }
            $attribute = substr($effect['tag'], strlen('mod_base_'));
            if (in_array($attribute, self::BASE_ATTRIBUTE_FIELDS, true)) {
                $character->decrement('base_' . $attribute, (int) ($effect['value'] ?? 0));
            }
        }
    }
}
