# Tag Library

## Power Effect

- `mod_str` / `mod_dex` / `mod_con` / `mod_int` / `mod_knw` / `mod_car` -> attribute modifier
- `mod_max_pm` -> bonus max PM
- `mod_max_pv` -> bonus max PV
- `mod_size` -> size category shift
- `mod_movement` -> bonus to Deslocamento — not resolved yet, no Movimento stat displayed on the sheet yet either, but a real near-term addition (Soldado de Infantaria), not speculative
- `mod_inventory_space` -> bonus max carry slots — not resolved yet, but `calculateMaxSlots` (max-slots.ts) already exists and just needs `powers` threaded in the same way `calculateDefense`/`calculateMaxPv`/`calculateMaxPm` already do (Soldado de Infantaria)
- `mod_hit` -> modifies attack roll
- `mod_dmg` -> modifies damage roll
- `mod_def` -> modifies Defesa
- `mod_dc` (`scope`) -> modifies a CD others must beat
- `skill` (`skill_id`) -> bonus or trained on a skill — `value` is usually a flat number or an attribute code (e.g. `knw`), but can also be a derived-from-equipment sentinel like `mod_def_from_shield` (Solidez: "whatever `mod_def` the currently equipped shield grants" — `character_hands` -> `character_inventory` -> `shields.mod_def`; two shields worn at once, take whichever comes up first, no tie-break; no shield worn = nothing to grab, self-contained condition, no separate `requires_*` field needed) or a parseable formula string like `"2+1*per_dependent_power[99]"` (Xadrez de Batalha — see `value` formula strings below)
- `skill_group` (`attribute`) -> targets every skill under an attribute
- `skill_attribute` (`skill_id`, `value`) -> overrides which attribute governs a skill
- `power` (`power_id`) -> grants a power
- `accessory` (`accessory_id`) -> grants an accessory
- `armor` (`armor_id`) -> grants an armor
- `resting` -> rest quality
- `temp_pm` -> temporary PM
- `on_<circumstance>` (`condition_id`) -> inflicts a status condition when `<circumstance>` happens — the "when" lives in the tag name itself (e.g. `on_critical_strike`, Farpada) rather than a separate `trigger_on`-style field, since that column's gone; `op: 'inflict'`; no removal-rule fields (`removal_check`/`removal_cd`/`removal_frequency`) — those were for the deprioritized combat engine's automated condition tracking (see `combat-engine-plans.md`), self-reported now like everything else
- `tormenta_power_carisma_loss` -> marks Carisma-loss mechanic as waivable
- `level_up_attribute_increase_lock` (`scope`) -> blocks Aumento de Atributo for a scope
- `self_damage` -> direct PV loss
- `dodge_chance` -> flat % chance to avoid an attack
- `damage_reduction` -> reduces incoming damage — value is a flat number, or a percent string like `"50%"` when the source describes a fraction. Not currently used by any seeded power (Durão/Júbilo na Dor/Especialização em Armadura all dropped it — no incoming-damage calculation exists anywhere in the app for it to plug into, self-reported prose only for now); kept documented for whenever that changes
- `restore_pm` -> instantly restores current PM by a rolled amount
- `reduce_qty` -> reduces a stackable item's quantity (e.g. a consumable used up on activation)
- `extra_attack` -> grants N additional attacks — sums across multiple sources, no per-source special-casing
- `reroll_dice_below` -> grants the ability to reroll any single damage die result at or below `value` — generic threshold (not baked into the tag name) so a different threshold reuses this same tag; op is `grant`, not `add`/`set` (this hands you a capability, not a number to sum)
- `ignore_dr` -> ignores a target's damage reduction — same flat-number-or-percent-string convention as `damage_reduction` (a flat number = ignore that many points, e.g. Romper Resistências' `10`; `"100%"` = ignore all of it, e.g. Golpe Demolidor); op is `add`. Unlike `damage_reduction`, this one's cheap enough to show as a checkbox on the future damage roll screen even before an incoming-damage calculation exists
- `weapon_step_increase` -> bumps the weapon's damage die up `value` steps (1d6->1d8->1d10->...) — op is `add`, steps sum across sources same as `extra_attack`. Not resolved yet (parked for the damage roll screen, same as Executor's dice-step scaling), but cheap enough to tag now

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
- `die_steps_per_levels` -> only with `op: 'roll'` — `value` is the base die (e.g. `"1d6"`) at level 1, stepping up one die size (1d6->1d8->1d10->...) for every this-many levels past level 1 (e.g. Executor: `value: "1d6", die_steps_per_levels: 4`)
- `limit` -> caps the result (attribute-sourced bonuses) or caps accumulation over time (`temp_pm`) — value is either an attribute code (e.g. `knw`, capping by that attribute's score) or the literal string `character_level` (capping by total character level, matching the `prerequisites` type name), never bare `level`
- `stack_group` -> entries sharing the same value don't stack, only the best applies; absent = stacks normally
- `when_category` -> restricts to items of a category (`item_improvements` only, see Item categories below)
- `when_type` -> finer restriction pairing with `when_category`, matches the target item's own `type` column (e.g. `armors`/`shields` `light`/`heavy`)
- `requires_hp_at_or_below` -> effect only counts while `current_pv` is at or below this percent of max PV (percent string, same convention as `damage_reduction`/`ignore_dr`) — unlike range/position, this is real checkable data (`current_pv`/`maxPv` are already live on the sheet), so a real reusable condition instead of permanent self-report (e.g. Determinação Inabalável: `"50%"`). Stays on the effect entry (not `visibility_reqs` below) because it gates whether THIS specific summed effect counts toward a standing total — a resolver-level concern, not a UI-visibility one

## `powers.visibility_reqs`

A separate top-level JSON column on `powers` (not nested in `effects`) — gates whether the power is even relevant to SURFACE in a self-report checklist UI (e.g. the planned attack-mode picker: atacar com mão direita/esquerda/duas mãos, which will know the character's current loadout), independent of whether the power's own `effects` are numerically modeled at all. Works for a power with zero `effects` (e.g. Inércia do Aço's unmodeled splash damage still needs to show up only when attacking two-handed). Moved out of individual effect entries 2026-09-04 since it's a property of the power's relevance, not of any one numeric effect — see the migration's own comment for the full reasoning. Null = always relevant (most powers). Keys drop the `requires_` prefix each used to have as an effect field — redundant once already inside a column named `visibility_reqs`:
- `weapon_grip` -> relevant only while wielding a weapon whose `grip` matches (`light`/`one_hand`/`two_hand`) — real checkable data (`character_hands` → `character_inventory` → the weapon row) (e.g. Destruidor, Inércia do Aço)
- `weapon_purpose` -> same idea, matches the equipped weapon's `purpose` — an array since "à distância" covers both `thrown` and `fired` (e.g. Arqueiro: `['thrown', 'fired']`)
- `weapon_ability` -> same family, but checks `weapons.ability_ids` (a `weapon_abilities` id) instead of `grip`/`purpose` (e.g. Esgrimista's "ágil" id 2, Manobra Dupla's "versátil" id 9)
- `weapon_any` -> OR across weapon conditions — an array of small objects, each one key from `{grip, purpose, ability}`, satisfied if any one object matches (e.g. Esgrimista: "leve ou ágil" = `[{grip: 'light'}, {ability: 2}]`). Use the singular fields when only one condition applies; use this when the source text has an "ou" across different weapon-condition types

### `value` formula strings

`value` isn't always a plain number or a bare sentinel (`knw`, `character_level`, `mod_def_from_shield`) — it can also be a small parseable formula string, same "the string itself carries the meaning" idea as a percent string like `"50%"`. Shape so far:

- `"<base>+<per-match>*per_dependent_power[<id,id,...>]"` -> `<base>` plus `<per-match>` for every *other* power the character has whose `prerequisites` contain `{type: 'power', power_id: X}` for any X in the bracketed, comma-separated id list. E.g. `"2+1*per_dependent_power[99]"` (Xadrez de Batalha: "+2, +1 para cada outro poder que você possua que tenha [power 99] como pré-requisito") — base 2, +1 per dependent, counting dependents of power 99 only. A comma-separated list (`per_dependent_power[99,100]`) would count dependents of either id. Not resolved yet — parked, same as everything else.

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

Down to four values — `trigger`/`trigger_active` dropped 2026-09-04 (no
combat engine planned; both collapsed cleanly once `trigger_on` was gone —
see `tag-system.md`).

- `passive` -> always on, no decision ever, even when conditional — an automatic proc whose condition just lives in the effect's own tag (e.g. Farpada's `on_critical_strike`, Arqueiro/Destruidor's `requires_weapon_*`)
- `roll_active` -> decided fresh at one specific roll, self-reported checkbox on whichever roll-type screen it belongs to — either riding the player's own roll (Ataque Especial) or judging an external circumstance against that roll (Rejeição Divina, on a Fortitude/Reflexos/Vontade roll)
- `active` -> standalone activation, not riding on any specific roll — instant vs. persisting is `duration`'s job; also covers a PM-costed reactive use in response to something that just happened (Ataque Reflexo, Golpe de Raspão)
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
