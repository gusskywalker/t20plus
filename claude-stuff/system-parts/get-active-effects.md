# getActiveEffects

`t20plus-frontend/src/app/shared/helpers/get-active-effects/get-active-effects.ts`. Flattens a character's `character_active_effects` rows (joined to the powers catalog) and its spell buffs into one `Effect[]`. It only collects; `resolveTag` (tag-solver.ts) combines tags into numbers and knows nothing about the character.

## Input
- `ActiveEffectsSource`: `active_effects`, `active_spell_effects`, `level`, `base_str/dex/con/int/knw/car`, optional `inventory` and `trained_skill_ids`. A `CharacterDraft` (character creation, exposes `active_effects`/`level` getters) can be passed too.

## Which rows count
- Only rows with `is_active: true`. The backend sets it once at insert: true for `usability: passive`, false otherwise. An `active` power's row turns true when its Ativar button flips it. `roll_active` rows are never toggled, so they are never included (the roll modals read them via `getRollActivePowers`).
- Item-granted powers are rows too (`source_inventory_id` set), so worn armor/shield/accessory and in-hand weapon powers arrive the same way.
- A power in several active rows counts once. The character's own row wins; when no row is the character's own, the effect carries `source_inventory_ids` listing every granting inventory row.
- A power replaced by another granted one (`replaces_power`, `resolveReplacedPowerIds`) is skipped.
- `applies_when.active_power_id`: the power's effects count only while that other power's row is `is_active`.

## Per power (first pass)
- Effects = the power's `effects` plus the row's `custom_effect` (per-character, same shape, same scaling).
- An effect with `trigger: 'on_other_sources_satisfied'` counts only when the row's `other_sources_state` is `satisfied` (`isTriggerSatisfied`; the backend flips it when a second, different-source grant of the same power happens).
- `scaleLevelEffect` turns level-scaled ops into a flat `add`: `add_per_level` (`ceil(level / per_character_level) * value`), `add_per_patamar` (`value` per patamar level reached, 5/11/17), `add_after_first` (level 1 never contributes).
- Every returned effect is a copy carrying `source_power_id` (or `source_spell_id`, or `source_inventory_ids`), so callers can label and group them.

## Spell buffs
- Each `active_spell_effects` row's effects are already final for that casting. Entries with `usability: 'roll_active'` are skipped (they belong to a roll modal's checklist); the rest fold in with `source_spell_id`.

## Second pass
- `applies_when.trained_skill_id`: after everything is collected, the trained set is `trained_skill_ids` plus every skill a collected effect trains (`skill op trains`); effects of a power whose gate skill isn't trained are dropped.
- Numeric sentinels (`resolveNumericSentinels`): attribute values are `base_*` plus `resolveTag(effects, 'mod_<code>')`; a bare `knw`-style value becomes that attribute's current value, `character_level` the level, and a `limit` caps it. `attribute_*` names stay as they are.

## Cache
- Memoized per character object in a `WeakMap` and invalidated when the powers catalog changes. A patched character is a new object and recomputes once. A `CharacterDraft` (no `id`) is never cached.
