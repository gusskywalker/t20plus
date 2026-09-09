# Tag Library

IMPORTANT!!!!!!!!!!!!!
CLAUDE READ THIS THIS TIME!!!!!!!!!!!!
This is a lookup list, not documentation — every entry is one short bullet,
brief and scannable. No prose, no multi-clause explanations, no reasoning, no archeology, no history of changes, no "not implemented yet". Just explain what it is shortly.
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
- `mod_dmg_attribute` -> which attribute adds to damage; defaults by `weapons.purpose` (melee/thrown -> str, fired -> none), op `set` overrides (`value: 'none'` = no attribute)
- `mod_def` -> modifies Defesa
- `mod_dc` -> modifies a CD others must beat; usability `dc_active`
- `mod_multiplier` -> bumps the weapon's own crit damage multiplier (base_multiplier)
- `mod_margin` -> added to the weapon's base_margin (negative = wider crit threat range)
- `mod_maneuver` -> bonus to combat maneuver tests (desarmar, quebrar, etc.) — not resolved yet, no maneuver system exists
- `mod_armor_penalty` -> reduces the worn armor/shield's own armor_penalty — not resolved yet, item_improvements aren't wired to any active bonus yet
- `mod_pm_cost_each` -> reduces the PM cost of EVERY other checked ability with a PM cost, by `value`, per ability (3 checked costed abilities = 3x the reduction, not a one-time flat reduction) — not resolved yet, item_improvements aren't wired to any active bonus yet
- `skill` -> bonus or trained on a skill
- `skill_group` -> targets every skill under an attribute
- `all_skills` -> flat bonus to every skill check, regardless of attribute
- `skill_attribute` -> overrides which attribute governs a skill
- `power` -> grants a power
- `accessory` -> grants an accessory
- `armor` -> grants an armor
- `weapon` -> grants a weapon (origins.grants only)
- `general_item` -> grants a general_item (origins.grants only)
- `tibares` -> grants a flat tibares bonus (origins.grants only, folded into character-creation-step-8's baseTibares)
- `resting` -> rest quality
- `temp_pm` -> temporary PM
- `spend_tibares` -> tibares cost paid on power activation (character-main.ts's toggleActivePower/useInstantPower)
- `on_<circumstance>` -> inflicts a status condition when `<circumstance>` happens (e.g. `on_critical_strike`, `on_marca_da_presa_hit`)
- `tormenta_power_carisma_loss` -> marks Carisma-loss mechanic as waivable
- `level_up_attribute_increase_lock` -> blocks Aumentar Atributo for a scope
- `self_damage` -> direct PV loss
- `dodge_chance` -> flat % chance to avoid an attack
- `damage_reduction` -> reduces incoming damage
- `restore_pm` -> instantly restores current PM by a rolled amount
- `reduce_qty` -> reduces a stackable item's quantity
- `reroll_dice_below` -> reroll any single damage die at or below `value`
- `ignore_dr` -> ignores damage reduction
- `ignore_lefeu_critical_immunity` -> op `grant` only; informational damage-breakdown line, same treatment as `push_distance`
- `weapon_step_increase` -> bumps the weapon's damage die up `value` steps (1d6->1d8->...)
- `all_die_step_increase` -> bumps every damage die (weapon's own + every extra_die) up `value` steps
- `push_distance` -> informational knockback readout, no board/grid to apply it on
- `advantage` (`scope`, e.g. `hit`; `scope: 'skill'` also takes `skill_id`) -> op `grant` only; roll two, take the best
- `allow_improve_ammo` -> op `grant` only; lets a general_item (ammo) take a melhoria
- `allow_dual_wield_full` -> op `grant` only; allows character to wield two one_hand weapons with no `leve` distinction
- `nullify_ranged_weapon_melee_penalty` -> op `grant` only; cancels the -5 Pontaria penalty for firing/arremessando at a melee-engaged target (e.g. Mirar) 
- `reduce_weapon_size_penalty` -> op `set` only; overrides the default -5 oversized-weapon hit penalty (Empunhadura Poderosa)
- `doubles_marca_da_presa_dice` -> op `grant` only; doubles Marca da Presa's own die count in place (Inimigo de (Criatura))

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
- `marca_da_presa_dice` (`mod_dmg` only) -> Marca da Presa's own die — separate from `extra_die`: doubled by `doubles_marca_da_presa_dice`, scaled by the crit multiplier when Tiro de Abate is active
- `inflict` -> used by `on_<circumstance>` to apply a condition

### value

- Plain number -> flat amount for `add`/`set`/`override`
- Percent string (e.g. `"50%"`) -> `damage_reduction`, `ignore_dr`
- Dice notation string -> only with op `roll` or `extra_die`

Sentinel strings:
- an attribute code (e.g. `knw`) -> that attribute's current bonus
- `character_level` -> character's total level
- `mod_def_from_shield` -> currently equipped shield's own `mod_def`
- `weapon_die` (op `extra_die` only) -> rolls an additional die matching the weapon already in use for the attack

Formula strings:
- `"<base>+<per-match>*per_dependent_power[<id,id,...>]"` -> base plus per-match for every other power whose `prerequisites` reference any listed id (e.g. `"2+1*per_dependent_power[99]"`)
- `"<meters>m/<amount><unit>"` (tag `push_distance` only) -> `floor(<unit's current value> / <amount>) * <meters>` — `<unit>` is spelled out per entry (`damage`, maybe `pm` for a future power, etc.) since the denominator isn't always damage; frontend reads the unit to know what to divide (e.g. `"1.5m/10damage"`)

### other fields

Housed under a specific tag/op:
- `skill_id` -> tags `skill` / `skill_attribute` / `advantage` (with `scope: 'skill'`)
- `per_levels` -> op `add_per_level` — total = floor(character.level / per_levels) * value
- `die_steps_per_levels` -> op `roll` — steps the base die up one size per this-many levels past level 1
- `condition_id` -> tag `on_<circumstance>`
- `when_category` / `when_type` -> `item_improvements` entries only (see Item categories below)
- `scope` -> tag `advantage` — which roll it's granted for, see that tag's own line above

General-purpose (any entry):
- `limit` -> caps the result — an attribute code or `character_level`, never bare `level`
- `stack_group` -> entries sharing the same value don't stack, only the best applies (numeric comparison for `add`/`set`/`override`; for `extra_die`, the bigger die step wins — see `extraDieStepIndex`)
- `requires_hp_at_or_below` -> effect only counts while `current_pv` is at or below this percent of max PV

## `powers.applies_when`

Top-level JSON column (not nested in `effects`) — scopes WHEN a power counts (currently equipped weapon; may grow to cover other runtime context later), independent of whether its `effects` are modeled. Distinct from `prerequisites`, which gates having the power at all. Null = always relevant. Keys (no `requires_` prefix — redundant here):
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
- `power_granted` -> synthetic, granted by another power's own `tag: 'power', op: 'grant'` effect (e.g. Espreitar's two children); added to the character alongside its parent, same as any other grant source
- `general_action` -> universal action anyone can use when conditions are met, never picked or added to `character_active_effects`
- `specific` -> never independently held/picked — a menu option referenced by id from a bespoke build (e.g. Golpe Pessoal's Elemental/Brutal/Letal); owning ids are hardcoded frontend-side, not tracked in the DB

## Power Usability

- `passive` -> always on, no decision, even when conditional (condition lives in the effect's own tag/fields)
- `roll_active` -> decided fresh at one specific roll, self-reported checkbox on whichever roll-type screen it belongs to
- `active` -> standalone activation, not riding on any specific roll — instant vs. persisting is `duration`'s job
- `roleplay` -> narrative only, no mechanical resolution
- `resting` -> only matters at the moment of resting, self-reported checkbox on a future rest screen
- `dc_active` -> only matters while computing a specific CD, self-reported checkbox on a future CD-calculator screen
- `vessel` -> pickable dropdown entry with no effect of its own, exists only to grant `power_granted` children (e.g. Escaramuça); still added to `character_active_effects` when picked (harmless, `is_active` defaults false same as any non-passive), but filtered out of every Poderes display list

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
- `power` (`power_ids_any`) -> requires having any one of these powers
- `class` (`class_ids`, `min_level`) -> requires a class at its own min level
- `skill_trained` (`skill_id`) -> requires being trained in a skill
- `god` (`god_ids`) -> requires one of these gods
- `power_type` (`value`) -> requires a power of a given type
- `character_level` (`min`) -> requires total character level
- `race` (`race_ids`) -> requires one of these races

## Item Improvement/Enchantment Prerequisite

- `item_improvements.prerequisites` / `item_enchantments.prerequisites` -> plain array of same-table ids the item must already have
- `item_improvements.incompatible_ids` / `item_enchantments.incompatible_ids` -> plain array of same-table ids the item must NOT already have

## Item Improvement/Enchantment Restrictions

- `item_improvements.restrictions` / `item_enchantments.restrictions` -> `{grip?, purpose?, damage_type?, is_firearm?, type?}`, narrows a `weapon`/`shield`/`general_item` categories entry (`type` means `shields.type` or `general_items.type` depending on which category is present)

## Item Improvement extra_cost categories

`item_improvements.extra_cost` -> `{category: cost}` — cost can vary by which category the improvement is applied to, independent of `is_material`.
- `weapons`
- `light_armors` -> `armors` where `type` is `light`
- `heavy_armors` -> `armors` where `type` is `heavy`
- `vestments` -> `armors` where `type` is `vestment`
- `shields`
- `exoterics` -> any category where `is_exoteric` is true, checked before the category-specific key above

## Race mod_other_excluded_attributes

- `str` / `dex` / `con` / `int` / `knw` / `car` -> attribute mod_other's free points can't go into (e.g. Meio-Elfo excludes `con`); null/empty = no restriction

## Item categories

Used by `effects.when_category`/`when_type` and `item_improvements`/`item_enchantments` `categories`.
- `weapon` -> weapons
- `armor` -> armors
- `shield` -> shields
- `esoteric` -> exotéricos
- `tool` -> tools
- `clothing` -> clothing (`categories` only, not `when_category`)
- `general_item` -> general_items, any type — narrow with `restrictions.type` (`categories` only, not `when_category`)
