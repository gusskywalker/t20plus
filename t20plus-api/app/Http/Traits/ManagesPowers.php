<?php

namespace App\Http\Traits;

use App\Models\Accessory;
use App\Models\Armor;
use App\Models\Character;
use App\Models\CharacterActiveEffect;
use App\Models\CharacterGolpePessoal;
use App\Models\CharacterHand;
use App\Models\CharacterInventory;
use App\Models\CharacterLevel;
use App\Models\GeneralItem;
use App\Models\ItemEnchantment;
use App\Models\ItemImprovement;
use App\Models\Power;
use App\Models\Shield;
use App\Models\Weapon;
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
    protected function grantPower(Character $character, int $powerId, array $customEffect = [], bool $landOnFirstLevel = true): void
    {
        DB::transaction(function () use ($character, $powerId, $customEffect, $landOnFirstLevel) {
            $power = Power::find($powerId);

            // Own the "already granted?" check here (rather than each
            // caller doing it before deciding whether to call grantPower
            // at all) so a second grant from a different source can flip
            // other_sources_state instead of just being silently skipped.
            $existing = CharacterActiveEffect::where('character_id', $character->id)->where('power_id', $powerId)->whereNull('source_inventory_id')->first();
            if ($existing) {
                if ($existing->other_sources_state === 'open') {
                    $existing->update(['other_sources_state' => 'satisfied']);
                }
                return;
            }

            // 'open' only for a power whose own effects carry a
            // trigger: 'on_other_sources_satisfied' entry (e.g. Empatia
            // Selvagem) — null for every ordinary power. See tag-system.md.
            $hasOtherSourcesEffect = collect($power?->effects ?? [])->contains(fn ($effect) => ($effect['trigger'] ?? null) === 'on_other_sources_satisfied');

            CharacterActiveEffect::create([
                'character_id' => $character->id,
                'power_id' => $powerId,
                'is_active' => $power?->usability === 'passive',
                'custom_effect' => empty($customEffect) ? null : $customEffect,
                'other_sources_state' => $hasOtherSourcesEffect ? 'open' : null,
            ]);

            if ($powerId === self::GOLPE_PESSOAL_POWER_ID) {
                CharacterGolpePessoal::create(['character_id' => $character->id]);
            }

            $this->syncNaturalWeaponIds($character);

            foreach ($power?->effects ?? [] as $effect) {
                if (($effect['op'] ?? null) !== 'add' || !str_starts_with($effect['tag'] ?? '', 'mod_base_')) {
                    continue;
                }
                $attribute = substr($effect['tag'], strlen('mod_base_'));
                if (in_array($attribute, self::BASE_ATTRIBUTE_FIELDS, true)) {
                    $character->increment('base_' . $attribute, (int) ($effect['value'] ?? 0));
                }
            }

            foreach ($power?->effects ?? [] as $effect) {
                if (($effect['tag'] ?? null) === 'enable_hand' && ($effect['op'] ?? null) === 'grant') {
                    CharacterHand::where('character_id', $character->id)->where('name', 'hand_' . (int) $effect['value'])->update(['enabled' => true]);
                }
            }

            // grant_or_reduce_spell_pm_cost_by_1 (e.g. Amiga das Plantas) —
            // this power has no character_levels row of its own to carry
            // other_source_spell_ids on (it isn't picked at a level), so it
            // lands on the character's own first level instead. Never
            // merged into spell_ids (that's grant_spell's own separate tag)
            // — staying OUT of spell_ids is what keeps the spell pickable
            // for real later, which the -1 PM discount is contingent on.
            // See tag-system.md's own section on this pipeline.
            $otherSourceSpellIds = $landOnFirstLevel ? ($power?->grantedOtherSourceSpellIds($customEffect) ?? []) : [];
            if (!empty($otherSourceSpellIds)) {
                $firstLevel = $character->levels()->orderBy('level')->first();
                if ($firstLevel) {
                    $firstLevel->update([
                        'other_source_spell_ids' => array_values(array_unique([...($firstLevel->other_source_spell_ids ?? []), ...$otherSourceSpellIds])),
                    ]);
                }
            }
        });
    }

    /**
     * characters.natural_weapon_ids re-derived from every power the
     * character currently has (grants_natural_weapon), so a natural weapon
     * granted or removed after creation (e.g. Asas de Aço, a race_optional
     * power picked later or via Adicionar Poder) stays in sync. Same
     * null-when-empty shape CharacterController::store() leaves it in.
     */
    private function syncNaturalWeaponIds(Character $character): void
    {
        $powerIds = CharacterActiveEffect::where('character_id', $character->id)->pluck('power_id');
        $naturalWeaponIds = Power::whereIn('id', $powerIds)->get()
            ->flatMap(fn (Power $power) => $power->grantedNaturalWeaponIds())
            ->unique()
            ->values()
            ->all();

        $character->update(['natural_weapon_ids' => empty($naturalWeaponIds) ? null : $naturalWeaponIds]);
    }

    /**
     * The reverse of grantPower, same structure — every path that takes a
     * power away from a character (Remover, Reduzir Nível) calls this
     * instead of deleting the CharacterActiveEffect row itself. Also walks
     * the power's own `tag: 'power', op: 'grant'` children (and their own
     * children, recursively) so removing a vessel takes its children with
     * it — the same relationship grantPower's callers already expand on
     * the way in (resolveGrantedPowerIds/grantChildPowers), just walked in
     * reverse. A swept-up child (not the power being revoked directly)
     * that's still independently deserved from another active source is
     * kept, not deleted — see stillIndependentlyGranted.
     */
    protected function revokePower(Character $character, int $powerId): void
    {
        DB::transaction(function () use ($character, $powerId) {
            foreach ($this->resolveDescendantPowerIds($powerId) as $id) {
                $this->revokeSinglePower($character, $id, $id === $powerId);
            }
        });
    }

    /**
     * Mirrors grantPower's own "already granted?" check, in reverse: a
     * descendant power swept up by revoking a vessel (never the vessel
     * being revoked directly — that one's an intentional removal) doesn't
     * get deleted if the character still independently deserves it — e.g.
     * a Dahllan Caçador un-picking their class's Empatia Selvagem vessel
     * must not also strip the same power's own race-granted row. Currently
     * only checks race_granted (the one real case this covers today,
     * Empatia Selvagem) — extend if another source type ever needs it.
     */
    private function stillIndependentlyGranted(Character $character, ?Power $power): bool
    {
        if (!$power || $power->source !== 'race_granted') {
            return false;
        }
        foreach ($power->prerequisites ?? [] as $prerequisite) {
            if (($prerequisite['type'] ?? null) === 'race' && in_array($character->race_id, $prerequisite['race_ids'] ?? [], true)) {
                return true;
            }
        }
        return false;
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

    private function revokeSinglePower(Character $character, int $powerId, bool $isRoot = true): void
    {
        $power = Power::find($powerId);

        if (!$isRoot && $this->stillIndependentlyGranted($character, $power)) {
            $existing = CharacterActiveEffect::where('character_id', $character->id)->where('power_id', $powerId)->whereNull('source_inventory_id')->first();
            if ($existing && $existing->other_sources_state === 'satisfied') {
                $existing->update(['other_sources_state' => 'open']);
            }
            return;
        }

        $deleted = CharacterActiveEffect::where('character_id', $character->id)->where('power_id', $powerId)->whereNull('source_inventory_id')->delete();
        if ($deleted === 0) {
            return;
        }

        if ($powerId === self::GOLPE_PESSOAL_POWER_ID) {
            CharacterGolpePessoal::where('character_id', $character->id)->delete();
        }

        $this->syncNaturalWeaponIds($character);

        foreach ($power?->effects ?? [] as $effect) {
            if (($effect['op'] ?? null) !== 'add' || !str_starts_with($effect['tag'] ?? '', 'mod_base_')) {
                continue;
            }
            $attribute = substr($effect['tag'], strlen('mod_base_'));
            if (in_array($attribute, self::BASE_ATTRIBUTE_FIELDS, true)) {
                $character->decrement('base_' . $attribute, (int) ($effect['value'] ?? 0));
            }
        }

        foreach ($power?->effects ?? [] as $effect) {
            if (($effect['tag'] ?? null) !== 'enable_hand' || ($effect['op'] ?? null) !== 'grant') {
                continue;
            }
            $hand = CharacterHand::where('character_id', $character->id)->where('name', 'hand_' . (int) $effect['value'])->first();
            if (!$hand) {
                continue;
            }
            if ($hand->inventory_ids ?? []) {
                CharacterInventory::whereIn('id', $hand->inventory_ids)->update(['worn' => false]);
            }
            $hand->update(['enabled' => false, 'inventory_ids' => []]);
            $this->syncItemPowers($character);
        }

        // Reverse of grantPower's own other_source_spell_ids merge —
        // whichever level row ended up holding these ids (the first level
        // for a race-granted power like Amiga das Plantas, or the level it
        // was actually picked at for a class power like Pakk) gets them
        // stripped back out.
        $otherSourceSpellIds = $power?->grantedOtherSourceSpellIds() ?? [];
        if (!empty($otherSourceSpellIds)) {
            foreach (CharacterLevel::where('character_id', $character->id)->get() as $level) {
                $remaining = array_values(array_diff($level->other_source_spell_ids ?? [], $otherSourceSpellIds));
                if ($remaining !== ($level->other_source_spell_ids ?? [])) {
                    $level->update(['other_source_spell_ids' => empty($remaining) ? null : $remaining]);
                }
            }
        }
    }

    protected function grantItemPower(Character $character, int $powerId, int $inventoryId): void
    {
        $power = Power::find($powerId);
        if (!$power) {
            return;
        }

        CharacterActiveEffect::firstOrCreate(
            ['character_id' => $character->id, 'power_id' => $powerId, 'source_inventory_id' => $inventoryId],
            ['is_active' => $power->usability === 'passive'],
        );
    }

    protected function revokeItemPower(Character $character, int $powerId, int $inventoryId): void
    {
        CharacterActiveEffect::where('character_id', $character->id)
            ->where('power_id', $powerId)
            ->where('source_inventory_id', $inventoryId)
            ->delete();
    }

    /**
     * Makes the character's item-sourced rows match what the inventory grants
     * right now: armor/shield/accessory when worn, a weapon when in hand,
     * a general item while owned (never a consumable or ammo). Every path that changes the inventory, a
     * hand, an accessory slot, or an item's improvements/enchantments calls
     * this afterwards.
     */
    protected function syncItemPowers(Character $character): void
    {
        DB::transaction(function () use ($character) {
            $desired = [];
            foreach (CharacterInventory::where('character_id', $character->id)->get() as $item) {
                if ($item->item_type !== 'general_item' && !$item->worn) {
                    continue;
                }
                if ($item->item_type === 'general_item') {
                    $generalItem = GeneralItem::find($item->item_id);
                    if ($generalItem?->consumable || $generalItem?->type === 'ammo') {
                        continue;
                    }
                }
                foreach ($this->itemGrantedPowerIds($item) as $powerId) {
                    $desired[$powerId . ':' . $item->id] = [$powerId, $item->id];
                }
            }

            $existing = CharacterActiveEffect::where('character_id', $character->id)->whereNotNull('source_inventory_id')->get();
            foreach ($existing as $row) {
                $key = $row->power_id . ':' . $row->source_inventory_id;
                if (isset($desired[$key])) {
                    unset($desired[$key]);
                } else {
                    $this->revokeItemPower($character, $row->power_id, $row->source_inventory_id);
                }
            }

            foreach ($desired as [$powerId, $inventoryId]) {
                $this->grantItemPower($character, $powerId, $inventoryId);
            }

            $this->syncNaturalWeaponIds($character);
        });
    }

    /**
     * Every power one inventory item grants — its own effects plus its
     * improvements' and enchantments' — including the powers those powers
     * grant in turn. Mirrors the frontend's getItemGrantedPowers: a grant
     * with when_category/when_type only applies to that item category/type.
     */
    private function itemGrantedPowerIds(CharacterInventory $item): array
    {
        $source = $this->itemPowerSource($item);
        if ($source === null) {
            return [];
        }
        [$ownEffects, $category, $type] = $source;

        $grantedIds = [];
        $collect = function (?array $effects) use (&$grantedIds, $category, $type) {
            foreach ($effects ?? [] as $effect) {
                if (($effect['tag'] ?? null) !== 'power' || ($effect['op'] ?? null) !== 'grant' || !isset($effect['power_id'])) {
                    continue;
                }
                if (!empty($effect['when_category']) && $effect['when_category'] !== $category) {
                    continue;
                }
                if (!empty($effect['when_type']) && $effect['when_type'] !== $type) {
                    continue;
                }
                $grantedIds[] = (int) $effect['power_id'];
            }
        };

        $collect($ownEffects);
        foreach (ItemImprovement::whereIn('id', $item->improvement_ids ?? [])->get() as $improvement) {
            $collect($improvement->effects);
        }
        foreach (ItemEnchantment::whereIn('id', $item->enchantment_ids ?? [])->get() as $enchantment) {
            $collect($enchantment->effects);
        }

        $allIds = [];
        foreach (array_unique($grantedIds) as $powerId) {
            $allIds = [...$allIds, ...$this->resolveDescendantPowerIds($powerId)];
        }

        return Power::whereIn('id', array_unique($allIds))->pluck('id')->map(fn ($id) => (int) $id)->all();
    }

    /**
     * [own effects, category, type] for the catalog item behind an inventory
     * row.
     */
    private function itemPowerSource(CharacterInventory $item): ?array
    {
        switch ($item->item_type) {
            case 'weapon':
                $catalogItem = Weapon::find($item->item_id);
                return $catalogItem ? [$catalogItem->effects, 'weapon', null] : null;
            case 'armor':
                $catalogItem = Armor::find($item->item_id);
                return $catalogItem ? [$catalogItem->effects, 'armor', null] : null;
            case 'shield':
                $catalogItem = Shield::find($item->item_id);
                return $catalogItem ? [$catalogItem->effects, 'shield', null] : null;
            case 'accessory':
                $catalogItem = Accessory::find($item->item_id);
                return $catalogItem ? [$catalogItem->effects, 'accessory', null] : null;
            case 'general_item':
                $catalogItem = GeneralItem::find($item->item_id);
                return $catalogItem ? [$catalogItem->effects, 'general_item', null] : null;
        }

        return null;
    }
}
