# Tag Library

This is a lookup list, not documentation — every entry is one short bullet,
brief and scannable. No prose, no multi-clause explanations, no reasoning.
If something needs "why," it belongs in tag-system.md instead.

## Power Effect

Every entry in a power's `effects` array is `{tag, op, value, ...}`.

### tag

- `mod_str` / `mod_dex` / `mod_con` / `mod_int` / `mod_knw` / `mod_car` -> attribute modifier
- `mod_max_pm` -> bonus max PM
- `mod_max_pv` -> bonus max PV
- `mod_size` -> size category shift
- `mod_movement` -> bonus to Deslocamento
- `mod_inventory_space` -> bonus max carry slots
- `mod_hit` -> modifies attack roll
- `mod_dmg` -> modifies damage roll
- `mod_def` -> modifies Defesa
- `mod_dc` -> modifies a CD others must beat; usability `dc_active`
- `mod_multiplier` -> bumps the weapon's own crit damage multiplier (base_multiplier)
- `mod_margin` -> added to the weapon's base_margin (negative = wider crit threat range)
- `skill` -> bonus or trained on a skill
- `skill_group` -> targets every skill under an attribute
- `skill_attribute` -> overrides which attribute governs a skill
- `power` -> grants a power
- `accessory` -> grants an accessory
- `armor` -> grants an armor
- `resting` -> rest quality
- `temp_pm` -> temporary PM
- `on_<circumstance>` -> inflicts a status condition when `<circumstance>` happens (e.g. `on_critical_strike`)
- `tormenta_power_carisma_loss` -> marks Carisma-loss mechanic as waivable
- `level_up_attribute_increase_lock` -> blocks Aumento de Atributo for a scope
- `self_damage` -> direct PV loss
- `dodge_chance` -> flat % chance to avoid an attack
- `damage_reduction` -> reduces incoming damage
- `restore_pm` -> instantly restores current PM by a rolled amount
- `reduce_qty` -> reduces a stackable item's quantity
- `extra_attack` -> grants N additional attacks, sums across sources
- `reroll_dice_below` -> reroll any single damage die at or below `value`
- `ignore_dr` -> ignores damage reduction
- `weapon_step_increase` -> bumps the weapon's damage die up `value` steps (1d6->1d8->...)
- `push_distance` -> informational knockback readout, no board/grid to apply it on
- `advantage` (`scope`, e.g. `hit`) -> op `grant` only; roll two, take the best

### op

- `add` -> sums
- `set` -> overrides
- `grant` -> you just have it
- `trains` -> skill becomes trained
- `add_per_level` -> scales with level
- `waive` -> excuses the first N occurrences of the tag
- `override` -> replaces a fixed property with a new value
- `roll` -> value is dice notation, rolled fresh each time — the result IS the whole value
- `extra_die` -> value is dice notation, rolled and added on top — own breakdown line, never scaled by a crit multiplier
- `inflict` -> used by `on_<circumstance>` to apply a condition

### value

- Plain number -> flat amount for `add`/`set`/`override`
- Percent string (e.g. `"50%"`) -> `damage_reduction`, `ignore_dr`
- Dice notation string -> only with op `roll` or `extra_die`

Sentinel strings:
- an attribute code (e.g. `knw`) -> that attribute's current bonus
- `character_level` -> character's total level
- `mod_def_from_shield` -> currently equipped shield's own `mod_def`
- `weapon_die` (op `extra_die` only) -> rerolls the weapon already in use for the attack

Formula strings:
- `"<base>+<per-match>*per_dependent_power[<id,id,...>]"` -> base plus per-match for every other power whose `prerequisites` reference any listed id (e.g. `"2+1*per_dependent_power[99]"`)
- `"<meters>m/<amount><unit>"` (tag `push_distance` only) -> `floor(<unit's current value> / <amount>) * <meters>` — `<unit>` is spelled out per entry (`damage`, maybe `pm` for a future power, etc.) since the denominator isn't always damage; frontend reads the unit to know what to divide (e.g. `"1.5m/10damage"`)

### other fields

Housed under a specific tag/op:
- `skill_id` -> tags `skill` / `skill_attribute`
- `per_levels` -> op `add_per_level` — total = floor(character.level / per_levels) * value
- `die_steps_per_levels` -> op `roll` — steps the base die up one size per this-many levels past level 1
- `condition_id` -> tag `on_<circumstance>`
- `when_category` / `when_type` -> `item_improvements` entries only (see Item categories below)
- `scope` -> tag `advantage` — which roll it's granted for, see that tag's own line above

General-purpose (any entry):
- `limit` -> caps the result — an attribute code or `character_level`, never bare `level`
- `stack_group` -> entries sharing the same value don't stack, only the best applies
- `requires_hp_at_or_below` -> effect only counts while `current_pv` is at or below this percent of max PV

## `powers.visibility_reqs`

Top-level JSON column (not nested in `effects`) — gates whether a power is relevant to surface in a self-report checklist, independent of whether its `effects` are modeled. Null = always relevant. Keys (no `requires_` prefix — redundant here):
- `weapon_grip` -> wielding a weapon whose `grip` matches (`light`/`one_hand`/`two_hand`)
- `weapon_purpose` -> equipped weapon's `purpose` — array (e.g. `['thrown', 'fired']`)
- `weapon_ability` -> equipped weapon has this `weapon_abilities` id
- `weapon_any` -> OR across the above — array of `{grip, purpose, ability}` objects, any one matching

## Power source

Renamed from `type` 2026-09-04 — answers "where did this power come from in the build," every value included.
- `general` -> Poderes Gerais
- `class` -> Poderes de Classe (choosable pool)
- `class_granted` -> class hands it to you automatically, no choice
- `divine_granted` -> Poderes Concedidos
- `races` -> Poderes Raciais
- `tormenta` -> Poderes da Tormenta
- `group` -> Poderes de Grupo
- `item_granted` -> synthetic, granted by an item improvement (passive/trigger — gear you're wearing/wielding)
- `consumable_granted` -> synthetic, granted by a general_items effect (active — a deliberate one-shot use)
- `complication_granted` -> synthetic, granted by a complication
- `age_granted` -> synthetic, granted by an age bracket
- `origin_granted` -> synthetic, granted by an origin's `grants`
- `specific` -> never independently held/picked — a menu option referenced by id from a bespoke build (e.g. Golpe Pessoal's Elemental/Brutal/Letal); owning ids are hardcoded frontend-side, not tracked in the DB

## Power Usability

- `passive` -> always on, no decision, even when conditional (condition lives in the effect's own tag/fields)
- `roll_active` -> decided fresh at one specific roll, self-reported checkbox on whichever roll-type screen it belongs to
- `active` -> standalone activation, not riding on any specific roll — instant vs. persisting is `duration`'s job
- `roleplay` -> narrative only, no mechanical resolution
- `resting` -> only matters at the moment of resting, self-reported checkbox on a future rest screen
- `dc_active` -> only matters while computing a specific CD, self-reported checkbox on a future CD-calculator screen

## Power Action cost

- `standard` -> ação padrão
- `movement` -> ação de movimento
- `complete` -> ação completa
- `extra` -> ação extra
- `free` -> ação livre
- `none` -> no separate action cost

## Power Duration

- `turn` -> lasts one turn
- `scene` -> lasts one scene
- `day` -> lasts one day
- null -> resolves instantly

## Power Prerequisite

- `attribute` (`attribute`, `min`) -> requires minimum attribute score
- `power` (`power_id`) -> requires having a power
- `class` (`class_ids`, `min_level`) -> requires a class at its own min level
- `skill_trained` (`skill_id`) -> requires being trained in a skill
- `god` (`god_id`) -> requires a god
- `power_type` (`value`) -> requires a power of a given type
- `character_level` (`min`) -> requires total character level
- `race` (`race_ids`) -> requires one of these races

## Race mod_other_excluded_attributes

- `str` / `dex` / `con` / `int` / `knw` / `car` -> attribute mod_other's free points can't go into (e.g. Meio-Elfo excludes `con`); null/empty = no restriction

## Item categories

Used by `effects.when_category`/`when_type` and `item_improvements.applies_to`.
- `weapon` -> weapons
- `armor` -> armors
- `shield` -> shields
- `esoteric` -> exotéricos
- `tool` -> tools
- `clothing` -> clothing (`applies_to` only, not `when_category`)
