# Tag Library

## Power Effect

- `mod_str` / `mod_dex` / `mod_con` / `mod_int` / `mod_knw` / `mod_car` -> attribute modifier
- `mod_max_pm` -> bonus max PM
- `mod_max_pv` -> bonus max PV
- `mod_size` -> size category shift
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
- `condition` (`condition_id`) -> inflicts a status condition
- `tormenta_power_carisma_loss` -> marks Carisma-loss mechanic as waivable
- `level_up_attribute_increase_lock` (`scope`) -> blocks Aumento de Atributo for a scope
- `self_damage` -> direct PV loss
- `dodge_chance` -> flat % chance to avoid an attack
- `damage_reduction` -> flat reduction to incoming damage
- `restore_pm` -> instantly restores current PM by a rolled amount
- `reduce_qty` -> reduces a stackable item's quantity (e.g. a consumable used up on activation)

## Op values

- `add` -> sums
- `set` -> overrides
- `grant` -> you just have it
- `trains` -> skill becomes trained
- `add_per_level` -> scales with level
- `waive` -> excuses the first N occurrences of the tag
- `override` -> replaces a fixed property with a new value
- `roll` -> value is dice notation, rolled fresh each time (not a flat number)

## Effect entry fields

Beyond `tag`/`op`/`value`, an effect entry can carry:
- `skill_id` -> which skill (pairs with `skill`/`skill_attribute`)
- `per_levels` -> only with `op: add_per_level` — total = floor(character.level / per_levels) * value
- `limit` -> caps the result (attribute-sourced bonuses) or caps accumulation over time (`temp_pm`)
- `stack_group` -> entries sharing the same value don't stack, only the best applies; absent = stacks normally
- `when_category` -> restricts to items of a category (`item_improvements` only, see Item categories below)
- `when_type` -> finer restriction pairing with `when_category`, matches the target item's own `type` column (e.g. `armors`/`shields` `light`/`heavy`)

## Power types

- `general` -> Poderes Gerais
- `class` -> Poderes de Classe (choosable pool)
- `class_granted` -> class hands it to you automatically, no choice
- `divine_granted` -> Poderes Concedidos
- `races` -> Poderes Raciais
- `tormenta` -> Poderes da Tormenta
- `group` -> Poderes de Grupo
- `resting` -> rest-quality bonus (app-specific)
- `item_granted` -> synthetic, granted by an item improvement (passive/trigger — gear you're wearing/wielding)
- `consumable_granted` -> synthetic, granted by a general_items effect (active — a deliberate one-shot use)
- `complication_granted` -> synthetic, granted by a complication
- `age_granted` -> synthetic, granted by an age bracket

## Power Usability

- `passive` -> always on, no decision
- `trigger` -> applies on an external condition (`trigger_on`)
- `roll_toggle` -> rides a roll, decided fresh each time
- `active` -> standalone activation
- `roleplay` -> narrative only, no mechanical resolution

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

## Power Trigger_on

- `enemy_fails_save_vontade` -> a creature fails a Vontade test
- `enemy_is_hit_critical` -> you land a critical hit on a creature
- `enemy_is_hit` -> you land any hit on a creature
- `targets_you_spell_divine` -> a divine spell is cast targeting this character
- `you_take_damage` -> you take damage from any source
- `targets_you_tormenta` -> targeted by a Tormenta effect/creature or an Aharadak devotee

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
