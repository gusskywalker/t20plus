# Tag Library

## Power Effect

- `mod_str` / `mod_dex` / `mod_con` / `mod_int` / `mod_knw` / `mod_car` -> attribute modifier
- `mod_max_pm` -> bonus max PM
- `mod_max_pv` -> bonus max PV
- `mod_size` -> size category shift
- `mod_movement` -> bonus to Deslocamento
- `mod_inventory_space` -> bonus max carry slots
- `mod_hit` -> modifies attack roll
- `mod_dmg` -> modifies damage roll
- `mod_def` -> modifies Defesa
- `mod_dc` (`scope`) -> modifies a CD others must beat
- `skill` (`skill_id`) -> bonus or trained on a skill
- `skill_group` (`attribute`) -> targets every skill under an attribute
- `skill_attribute` (`skill_id`, `value`) -> overrides which attribute governs a skill
- `power` (`power_id`) -> grants a power
- `accessory` (`accessory_id`) -> grants an accessory
- `armor` (`armor_id`) -> grants an armor
- `resting` -> rest quality
- `temp_pm` -> temporary PM
- `on_<circumstance>` (`condition_id`) -> inflicts a status condition when `<circumstance>` happens (e.g. `on_critical_strike`); `op: 'inflict'`
- `tormenta_power_carisma_loss` -> marks Carisma-loss mechanic as waivable
- `level_up_attribute_increase_lock` (`scope`) -> blocks Aumento de Atributo for a scope
- `self_damage` -> direct PV loss
- `dodge_chance` -> flat % chance to avoid an attack
- `damage_reduction` -> reduces incoming damage — flat number or percent string (`"50%"`)
- `restore_pm` -> instantly restores current PM by a rolled amount
- `reduce_qty` -> reduces a stackable item's quantity
- `extra_attack` -> grants N additional attacks, sums across sources
- `reroll_dice_below` -> reroll any single damage die at or below `value`; op `grant`
- `ignore_dr` -> ignores damage reduction — flat number or `"100%"`; op `add`
- `weapon_step_increase` -> bumps the weapon's damage die up `value` steps (1d6->1d8->...); op `add`

## Op values

- `add` -> sums
- `set` -> overrides
- `grant` -> you just have it
- `trains` -> skill becomes trained
- `add_per_level` -> scales with level
- `waive` -> excuses the first N occurrences of the tag
- `override` -> replaces a fixed property with a new value
- `roll` -> value is dice notation, rolled fresh each time — the result IS the whole value (e.g. restore_pm)
- `extra_die` -> value is dice notation, rolled and added on top (mod_dmg only) — own breakdown line, never scaled by a crit multiplier (unlike the weapon's own die)

## Effect entry fields

Beyond `tag`/`op`/`value`, an effect entry can carry:
- `skill_id` -> which skill (pairs with `skill`/`skill_attribute`)
- `per_levels` -> only with `op: add_per_level` — total = floor(character.level / per_levels) * value
- `die_steps_per_levels` -> only with `op: 'roll'` — steps the base die (`value`) up one size per this-many levels past level 1
- `limit` -> caps the result — an attribute code (`knw`) or the literal string `character_level`, never bare `level`
- `stack_group` -> entries sharing the same value don't stack, only the best applies
- `when_category` -> restricts to items of a category (`item_improvements` only, see Item categories below)
- `when_type` -> finer restriction pairing with `when_category` (e.g. `armors`/`shields` `light`/`heavy`)
- `requires_hp_at_or_below` -> effect only counts while `current_pv` is at or below this percent of max PV

Other `value` shapes:
- Sentinel strings: an attribute code (`knw`), `character_level`, or `mod_def_from_shield` (currently equipped shield's own `mod_def`)
- Formula strings: `"<base>+<per-match>*per_dependent_power[<id,id,...>]"` -> base plus per-match for every other power the character has whose `prerequisites` reference any id in the list (e.g. `"2+1*per_dependent_power[99]"`)

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
- `specific` -> never independently held/picked — a menu option referenced by id from some other bespoke build (e.g. Golpe Pessoal's Elemental/Brutal/Letal); which ids belong to which build is hardcoded frontend-side (same as Ataque Especial's tier ids), not tracked in the DB

## Power Usability

- `passive` -> always on, no decision, even when conditional (condition lives in the effect's own tag/fields)
- `roll_active` -> decided fresh at one specific roll, self-reported checkbox on whichever roll-type screen it belongs to
- `active` -> standalone activation, not riding on any specific roll — instant vs. persisting is `duration`'s job
- `roleplay` -> narrative only, no mechanical resolution
- `resting` -> only matters at the moment of resting, self-reported checkbox on a future rest screen

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
