# Tag Library

## Power Effect

- `mod_str` / `mod_dex` / `mod_con` / `mod_int` / `mod_knw` / `mod_car` -> attribute modifier
- `mod_pm` -> bonus PM
- `mod_pv` -> bonus PV
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

## Op values

- `add` -> sums
- `set` -> overrides
- `grant` -> you just have it
- `trains` -> skill becomes trained
- `add_per_level` -> scales with level
- `waive` -> excuses the first N occurrences of the tag
- `override` -> replaces a fixed property with a new value

## Power types

- `general` -> Poderes Gerais
- `class` -> Poderes de Classe (choosable pool)
- `class_granted` -> class hands it to you automatically, no choice
- `divine_granted` -> Poderes Concedidos
- `races` -> Poderes Raciais
- `tormenta` -> Poderes da Tormenta
- `group` -> Poderes de Grupo
- `resting` -> rest-quality bonus (app-specific)
- `item_granted` -> synthetic, granted by an item improvement
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

## Item categories

Used by `effects.when_category`/`when_type` and `item_improvements.applies_to`.
- `weapon` -> weapons
- `armor` -> armors
- `shield` -> shields
- `esoteric` -> exotéricos
- `tool` -> tools
- `clothing` -> clothing (`applies_to` only, not `when_category`)
