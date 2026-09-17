# Tag Library

IMPORTANT!!!!!!!!!!!!!
CLAUDE READ THIS THIS TIME!!!!!!!!!!!!
This is a lookup list, not documentation — every entry is one short bullet,
brief and scannable. No prose, no multi-clause explanations, no reasoning, no archeology, no history of changes, no "not implemented yet". Just explain what it is shortly.
If something needs "why," it belongs in tag-system.md instead.

## Power Effect

Every entry in a power's `effects` array is `{tag, op, value, ...}`.

`character_active_effects.custom_effect` uses this exact same shape — per-character customization for one specific granted-power instance (e.g. Espião's open skill choice), folded into `getActiveEffects()` alongside the power's own `effects`.

### tag

- `mod_str` / `mod_dex` / `mod_con` / `mod_int` / `mod_knw` / `mod_car` -> attribute modifier
- `mod_base_str` / `mod_base_dex` / `mod_base_con` / `mod_base_int` / `mod_base_knw` / `mod_base_car` -> Aumentar Atributo directly increases the base attribute for a character
- `mod_hit_or_dmg` -> Ataque Especial's own bonus splitting mechanism
- `mod_max_pm` -> bonus max PM
- `mod_max_pv` -> bonus max PV
- `mod_size` -> size category shift
- `mod_movement` -> Deslocamento — op `add`/`set` (replaces base outright, e.g. Caído's fixed 1,5m) resolved via resolveTag; op `multiply` (e.g. Lento's 0.5) applied last, own step in calculate-movement.ts since resolveTag doesn't handle it
- `mod_inventory_space` -> bonus max carry slots (see max-slots.ts)
- `mod_hit` -> modifies attack roll
- `mod_dmg` -> modifies damage roll
- `mod_dmg_attribute` -> which attribute adds to damage; defaults by `weapons.purpose` (melee/thrown -> str, fired -> none), op `set` overrides (`value: 'none'` = no attribute)
- `mod_def` -> modifies Defesa
- `mod_multiplier` -> bumps the weapon's own crit damage multiplier (base_multiplier)
- `mod_margin` -> added to the weapon's base_margin (negative = wider crit threat range)
- `mod_maneuver` -> bonus to combat maneuver tests (desarmar, quebrar, etc.); no maneuver system exists
- `mod_armor_penalty` -> reduces the worn armor/shield's own armor_penalty; item_improvements aren't wired to any active bonus
- `mod_pm_cost_each` -> reduces the PM cost of EVERY other checked ability with a PM cost, by `value`, per ability (3 checked costed abilities = 3x the reduction, not a one-time flat reduction); item_improvements aren't wired to any active bonus
- `mod_own_pm_cost` -> op `add`; discounts a power's OWN pm_cost (resolve-power-pm-cost.ts), only ever paired with `trigger: on_other_sources_satisfied` (e.g. Engenhosidade)
- `remaining_uses` -> op `set` (base) / `add` (bonus, summed via resolveTag); only on a `usability: 'item_enhancer'` power — starting use-count copied into its `other_effects_power_ids` entry when applied, decremented on a landed hit, entry removed at 0 (e.g. Natureza Venenosa, value 1). Absent = no fixed expiry, cleared only by the player's own Remover click.
- `all_skills_no_combat` -> like `all_skills` but excludes Luta/Pontaria (hardcoded ids, `COMBAT_SKILL_IDS`) — for a roll_active power that can't be used on an attack test
- `skill` -> bonus or trained on a skill
- `skill_group` -> targets every skill under an attribute
- `all_skills` -> flat bonus to every skill check, regardless of attribute
- `skill_attribute` -> overrides which attribute governs a skill
- `power` -> grants a power
- `blocks_condition` -> op `grant`; character is immune to the given `condition_id` (e.g. Falcão vs. Surpreendido/Desprevenido)
- `condition_type_immunity` -> op `grant`, `value` (a `conditions.type` value: `fear`/`metabolism`/`movement`/`senses`/`mental`/`tired`); immune to every condition of that whole category (e.g. Osteon, `tired` and `metabolism`)
- `grant_or_reduce_spell_pm_cost_by_1` -> op `grant`; lets you cast `spell_id` even if unknown (synthesized via `character_levels.other_source_spell_ids`, server-derived from this effect — see `Power::grantedOtherSourceSpellIds`); if you also know it for real, costs -1 PM instead of granting a duplicate (e.g. Pakk). `spell_id: null` is a player-choice placeholder, filled at grant time from the character's own `custom_effect` for that power, in order (e.g. Tatuagem Mística/Canção dos Mares)
- `grant_spell` -> op `grant`; writes `spell_id` straight into `character_levels.spell_ids` at grant time (see `Power::grantedSpellIds`) — genuinely known, no PM discount involved (e.g. Familiar (T'peel))
- `grant_spell_type` -> op `grant`; lets a character also pick spells of `spell_type` (up to `max_circle`) on top of their class's own normal type/circle cap, independent caps — see `resolveAvailableSpellOptions`
- `accessory` -> grants an accessory
- `armor` -> grants an armor
- `weapon` -> grants a weapon (origins.grants only)
- `general_item` -> grants a general_item (origins.grants only)
- `tibares` -> grants a flat tibares bonus (origins.grants only, folded into character-creation-step-8's baseTibares)
- `resting` -> rest quality
- `temp_pm` -> temporary PM
- `spend_tibares` -> tibares cost paid on power activation (character-main.ts's toggleActivePower/useInstantPower)
- `on_critical_strike` -> `op` `inflict` means the condition applies on a critical hit
- `on_marca_da_presa_hit` -> `op` `inflict` means the condition applies on hitting a creature marked by Marca da Presa
- `on_spell_success` -> `op` `inflict` means the condition applies when the target fails its resistance roll; `op` `override` on a checked enhancement's own `condition` effect REPLACES the spell's base `inflict` entirely instead of stacking with it (e.g. Hipnotismo's truque: "em vez de fascinado, o alvo fica pasmo")
- `on_other_sources_satisfied` -> `trigger` value; gated by other_sources_state 'satisfied' (e.g. Empatia Selvagem) — see tag-system.md
- `waive_tool_absent_penalty` -> op `grant`; Ofício-roll resolver tag (e.g. Engenhoso) — see tag-system.md
- `tool_present` -> op `add`; Ofício-roll resolver tag (e.g. Engenhoso) — see tag-system.md
- `resting_floor_pv` / `resting_floor_pm` -> op `set`; resting resolver tag, minimum PV/PM recovered (e.g. Rato das Ruas, value `character_level`)
- `rest_pm_recovery` -> op `set`; resting resolver tag, overrides how much PM a rest recovers outright (value `0` = none) — independent of `resting`'s own quality scale (e.g. Transformação Anfíbia's "sem contato com água")
- `on_sono_cast` -> Sono's own bespoke condition set; branching resolved by a dedicated resolver, not the generic spell tags
- `on_aparencia_perfeita_cast` -> op `set_or_add` applies Aparência Perfeita's conditional Carisma bonus
- `tormenta_power_carisma_loss` -> marks Carisma-loss mechanic as waivable
- `level_up_attribute_increase_lock` -> blocks Aumentar Atributo for a scope
- `self_damage` -> direct PV loss
- `dodge_chance` -> flat % chance to avoid an attack
- `damage_reduction` -> reduces incoming damage; optional `damage_reduction_type` for "RD X/tipo"'s bypass type
- `damage_immunity` -> op `grant`; full immunity to `damage_reduction_type` (reused field)
- `change_heal_to_damage` -> op `grant`; healing magic damages you instead (e.g. Osteon)
- `change_damage_to_heal` -> op `grant`, `value` (a damage type); that damage type heals you instead of hurting (e.g. Osteon, `darkness`)
- `restore_pm` -> op `roll` (dice notation, self-reported active-power use) or op `add` with `value: 'spell_circle'` + `trigger: 'on_spell_success'` (resolved in spell-casting-modal.ts, capped by the PM actually spent that cast — e.g. Sifão de Mana)
- `reroll_dice_below` -> reroll any single damage die at or below `value`
- `ignore_dr` -> ignores damage reduction
- `ignore_lefeu_critical_immunity` -> op `grant` only; informational damage-breakdown line, same treatment as `push_distance`
- `weapon_step_increase` -> bumps the weapon's damage die up `value` steps (1d6->1d8->...)
- `grants_natural_weapon` -> op `grant`, `weapon_id`; adds a weapon id to `characters.natural_weapon_ids` (computed once at creation, see tag-system.md) — e.g. Minotauro's Chifres
- `all_die_step_increase` -> bumps every damage die (weapon's own + every extra_die) up `value` steps
- `push_distance` -> informational knockback readout, no board/grid to apply it on
- `advantage` (`scope`, e.g. `hit`; `scope: 'skill'` also takes `skill_id`) -> op `grant` only; roll two, take the best
- `allow_improve_ammo` -> op `grant` only; lets a general_item (ammo) take a melhoria
- `allow_dual_wield_full` -> op `grant` only; allows character to wield two one_hand weapons with no `leve` distinction
- `nullify_ranged_weapon_melee_penalty` -> op `grant` only; cancels the -5 Pontaria penalty for firing/arremessando at a melee-engaged target (e.g. Mirar) 
- `reduce_weapon_size_penalty` -> op `set` only; overrides the default -5 oversized-weapon hit penalty (Empunhadura Poderosa)
- `doubles_marca_da_presa_dice` -> op `grant` only; doubles Marca da Presa's own die count in place (Inimigo de (Criatura))
- `waive_weapon_proficiency` -> stops a specific equipment id's proficiency from being checked
- `waive_prerequisites` -> op `grant`; `power_ids` field lists power catalog ids that skip their own prerequisites entirely for this character (e.g. Ginete Natural letting a Centauro pick Carga de Cavalaria without Ginete) — checked by resolveWaivedPrerequisitePowerIds, used in both character-creation-powers-step.ts and level-change-modal.ts's own checkPrerequisites
- `spell_key_attribute` -> which attribute drives a spell's CD (Int/Sab/Car); op `set`; on a caster power (Bruxo/Feiticeiro/Mago) it's per-class, OR directly on a spell's own `effects` to fix that spell's CD attribute regardless of caster (e.g. Olhar Atordoante) — checked in that order by resolve-spell-caster-info.ts
- `power_granted_spell_key_attribute` -> op `set`; same idea as `spell_key_attribute` but scoped to one power's own `grant_or_reduce_spell_pm_cost_by_1` grant(s) only, whether the spell_id is player-chosen or fixed (e.g. Tatuagem Mística/Canção dos Mares/Luz Sagrada) — a separate tag on purpose, since `spell_key_attribute` is blindly scanned for by resolveCasterKeyAttribute/resolveCasterMaxCircle (character-wide caster lookups) which assume it only ever lives on a real class caster power
- `starting_spell_count` -> flat starting known/prepared spell count; op `set`
- `spell_count_growth` -> additional spells known per level past the first; op `add_after_first`, `per_class_level` varies by casting path
- `mod_spell_dmg` -> modifies spell damage — forked from `mod_dmg` on purpose, no weapon/crit/attack-roll pipeline behind it
- `mod_spell_def` -> op `add`; bumps a spell buff's own `mod_def` contribution — caster's own copy only, never a target they buff
- `mod_cd` -> op `add`; bumps spell CD
- `mod_spell_pm_cost` -> op `add`; bumps a spell's final PM cost, floored at 1
- `mod_spell_dmg_per_die` -> op `add`; per-die damage bonus, multiplied by the spell's own final combined dice count (not a flat add) — see spell-casting-modal.ts
- `base_spell_dmg_flat` -> op `add`; a plain number folded straight into the "Dano da Magia" line's own total, alongside `base_spell_dmg`'s rolled dice — for a spell whose base damage is dice+flat (e.g. Despedaçar's 1d8+2), since `base_spell_dmg`/`rollDice` only ever accept pure dice notation, never a suffix
- `mod_spell_dmg_flat` -> op `add`; same as `base_spell_dmg_flat` but on a spell's own native enhancement — only counted while that enhancement is checked, scaled by how many times it's checked if `repeatable`
- `fluff_summon_minions` -> op `grant` only; informational spell-cast breakdown line for a checked enhancement that summons temporary allies (e.g. Gênese Elemental)
- `fluff_split_area` -> op `grant` only; informational spell-cast breakdown line for a checked enhancement that splits the spell's area in two (e.g. Magia Dividida)
- `fluff_target_count` -> op `grant` only; informational spell-cast breakdown line "Atingiu X alvos!" — `value` is a sentinel (e.g. `key_attribute`) resolved the normal way before display (e.g. Raio Dividido)
- `fluff_change_target` -> op `grant` only; informational spell-cast breakdown line "Alterou o alvo para {value}!" — `value` is the literal display text (e.g. "objeto mundano Médio"), for an enhancement that changes what the spell targets with no numeric consequence to model (e.g. Despedaçar's target-size upgrades)
- `change_usability` -> op `set` only; a checked enhancement overrides the spell's own `usability` for this cast (e.g. Bênção's "muda o alvo para 1 cadáver" truque becomes 'utility' instead of 'buff') — see resolve-effective-spell-usability.ts

### op

- `add` -> sums
- `multiply` -> multiplies the base value (e.g. `value: 0.5` halves it)
- `set` -> overrides
- `grant` -> you just have it
- `trains` -> skill becomes trained
- `add_per_level` -> scales with level
- `add_per_patamar` -> scales by how many of the fixed patamar levels (5/11/17) have been reached
- `add_after_first` -> like `add_per_level`, but level 1 contributes nothing — for a cadence layered on top of a separate flat starting value
- `waive` -> excuses the first N occurrences of the tag
- `override` -> replaces a fixed property with a new value
- `roll` -> value is dice notation, rolled fresh each time — the result IS the whole value
- `extra_die` -> value is dice notation, rolled and added on top — own breakdown line, never scaled by a crit multiplier
- `marca_da_presa_dice` (`mod_dmg` only) -> Marca da Presa's own die — separate from `extra_die`: doubled by `doubles_marca_da_presa_dice`, scaled by the crit multiplier when Tiro de Abate is active
- `inflict` -> used by `on_<circumstance>` to apply a condition
- `set_or_add` -> if base value >= `min`, add `value`; else set to `min`
- `per_die` (`damage_reduction` only) -> scales by the incoming attack's own dice count instead of a flat amount; a negative `value` models a per-die vulnerability (e.g. Esquife de Gelo's -1/dado to fire)

### value

- Plain number -> flat amount for `add`/`set`/`override`
- Percent string (e.g. `"50%"`) -> `damage_reduction`, `ignore_dr`
- Dice notation string -> only with op `roll` or `extra_die`

Sentinel strings:
- an attribute code (e.g. `knw`) -> that attribute's current bonus
- `key_attribute` -> the character's own spell key attribute; resolved generically by resolve-effect-sentinels.ts (covers `mod_max_pv`, `mod_spell_dmg`, any future sentinel-driven tag) via resolve-spell-caster-info.ts's `resolveCasterKeyAttribute` — `skill_attribute` calls that same function directly since it needs the raw code, not a resolved number
- `character_level` -> character's total level
- `arcanista_levels` -> character's own Arcanista class-relative level count (Poder Mágico) — resolved by resolve-arcanista-levels.ts, tied to one specific class rather than a generic concept
- `mod_def_from_shield` -> currently equipped shield's own `mod_def`
- `weapon_die` (op `extra_die` only) -> rolls an additional die matching the weapon already in use for the attack
- `spell_die` (tag `mod_spell_dmg`, op `extra_die` only) -> rolls ONE additional die matching the spell's own base die SIZE (not a duplicate of the full base notation, which can be multi-die e.g. Raio Arcano's Xd8) — see spell-casting-modal.ts
- `spell_circle` (tag `restore_pm`, op `add` only) -> the CAST spell's own círculo — a cast-context sentinel like `spell_die`/`weapon_die` above, not a character fact, so it's resolved directly in spell-casting-modal.ts rather than through resolve-effect-sentinels.ts

Formula strings:
- `"<base>+<per-match>*per_dependent_power[<id,id,...>]"` -> base plus per-match for every other power whose `prerequisites` reference any listed id (e.g. `"2+1*per_dependent_power[99]"`)
- `"<meters>m/<amount><unit>"` (tag `push_distance` only) -> `floor(<unit's current value> / <amount>) * <meters>` — `<unit>` is spelled out per entry (`damage`, maybe `pm` for a future power, etc.) since the denominator isn't always damage; frontend reads the unit to know what to divide (e.g. `"1.5m/10damage"`)

### other fields

Housed under a specific tag/op:
- `skill_id` -> tags `skill` / `skill_attribute` / `advantage` (with `scope: 'skill'`)
- `per_character_level` -> op `add_per_level` — total = ceil(character.level / per_character_level) * value (overall character level)
- `per_class_level` -> op `add_after_first` (spell_count_growth) — total = floor((classLevel - 1) / per_class_level) * value (CLASS-relative, resolved by resolve-caster-spell-slots.ts/resolve-new-spell-slots-at-level.ts directly off power.effects, not through getActiveEffects)
- `die_steps_per_levels` -> op `roll` — steps the base die up one size per this-many levels past level 1
- `condition_id` -> tags `on_critical_strike` / `on_marca_da_presa_hit` / `on_spell_success`
- `min` -> op `set_or_add` — the threshold value compared against and set to
- `when_category` / `when_type` -> `item_improvements` entries only (see Item categories below)
- `scope` -> tag `advantage` — which roll it's granted for, see that tag's own line above

General-purpose (any entry):
- `limit` -> caps the result — an attribute code or `character_level`, never bare `level`
- `stack_group` -> entries sharing the same value don't stack, only the best applies (numeric comparison for `add`/`set`/`override`; for `extra_die`, the bigger die step wins — see `extraDieStepIndex`)
- `requires_hp_at_or_below` -> effect only counts while `current_pv` is at or below this percent of max PV

## `origins.items`/`origins.grants` choice tags

- `choose_tool` / `choose_simple_weapon` / `choose_martial_weapon` -> gives specific choices during character creation
- `choose_skill_not_combat` -> lets the player pick any trained skill (except Luta/Pontaria) to write a `skill_attribute` override into that granted active_effect's `custom_effect`

## `powers.applies_when`

Top-level JSON column (not nested in `effects`) — scopes WHEN a power counts (currently equipped weapon; may grow to cover other runtime context later), independent of whether its `effects` are modeled. Distinct from `prerequisites`, which gates having the power at all. Null = always relevant. Keys (no `requires_` prefix — redundant here):
- `weapon_grip` -> wielding a weapon whose `grip` matches (`light`/`one_hand`/`two_hand`/`natural`)
- `weapon_purpose` -> equipped weapon's `purpose` — array (e.g. `['thrown', 'fired']`)
- `weapon_ability` -> equipped weapon has this `weapon_abilities` id
- `weapon_any` -> OR across the above — array of `{grip, purpose, ability, weapon_id}` objects, any one matching (weapon_id: for isolating one specific weapon from the rest of its own purpose category, e.g. Arremessador's Funda vs. other 'fired' weapons)
- `spell_action_costs` -> spell's `action_cost` is one of these (array)
- `spell_damage_types` -> spell's `damage_type` is one of these (array)
- `spell_has_affected_area` -> boolean; spell's `info_affected_area` isn't null
- `spell_ranges` -> spell's `range` is one of these (array)
- `spell_schools` -> spell's `school` is one of these (array) — checked for `passive` powers too, not just `spell_enhancement`
- `spell_resistances` -> spell's `resistance` is one of these (array) — same `passive`-too reasoning as `spell_schools`
- `caster_min_circle` -> gates on the CASTER's own current circle access (resolveCasterMaxCircle), not the spell being cast — e.g. Fortalecimento Arcano's second +1 stacking to +2 past circle 4
- `spell_double_known` -> boolean; spell is known BOTH for real (spell_ids) AND via some other granted source (other_source_spell_ids) at once — e.g. O Próprio Sangue's +2 CD

## Power source

- `general` -> Poderes Gerais
- `class` -> Poderes de Classe (choosable pool)
- `class_granted` -> class hands it to you automatically, no choice
- `divine_granted` -> Poderes Concedidos
- `race_granted` -> race hands it to you automatically, no choice
- `race_optional` -> race offers it as one of several optional racial power picks
- `tormenta` -> Poderes da Tormenta
- `group` -> Poderes de Grupo
- `item_granted` -> synthetic, granted by an item improvement (passive/trigger — gear you're wearing/wielding)
- `consumable_granted` -> synthetic, granted by a general_items effect (active — a deliberate one-shot use)
- `complication_granted` -> synthetic, granted by a complication
- `age_granted` -> synthetic, granted by an age bracket
- `origin_granted` -> synthetic, granted by an origin's `grants`
- `power_granted` -> synthetic, granted by another power's own `tag: 'power', op: 'grant'` effect (e.g. Espreitar's two children); added to the character alongside its parent, same as any other grant source
- `general_action` -> universal action anyone can use when conditions are met, never picked or added to `character_active_effects`
- `condition_granted` -> synthetic, mirrors one `conditions` row — same name/description, its mechanical effects (where resolvable) so a character can carry a condition via the normal `character_active_effects` add-power flow
- `specific` -> never independently held/picked — a menu option referenced by id from a bespoke build (e.g. Golpe Pessoal's Elemental/Brutal/Letal); owning ids are hardcoded frontend-side, not tracked in the DB

## Power Usability

- `passive` -> always on, no decision, even when conditional (condition lives in the effect's own tag/fields)
- `roll_active` -> decided fresh at one specific roll, self-reported checkbox on whichever roll-type screen it belongs to
- `active` -> standalone activation, not riding on any specific roll — instant vs. persisting is `duration`'s job
- `roleplay` -> narrative only, no mechanical resolution
- `resting` -> only matters at the moment of resting, self-reported checkbox on a future rest screen
- `vessel` -> pickable dropdown entry with no effect of its own, exists only to grant `power_granted` children (e.g. Escaramuça); still added to `character_active_effects` when picked (harmless, `is_active` defaults false same as any non-passive), but filtered out of every Poderes display list
- `item_enhancer` -> self-applied to one specific item instance via that item's own item-details-modal button, not a character-wide toggle — writes `{power_id, remaining_uses?}` into that item's `other_effects_power_ids` (see tag-system.md)

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

Used by `effects.when_category`/`when_type`, `item_improvements`/`item_enchantments` `categories`, and a `usability: 'item_enhancer'` power's own `applies_when.categories`.
- `weapon` -> weapons
- `armor` -> armors
- `shield` -> shields
- `esoteric` -> exotéricos
- `tool` -> tools
- `clothing` -> clothing (`categories` only, not `when_category`)
- `general_item` -> general_items, any type — narrow with `restrictions.type` (`categories` only, not `when_category`)
