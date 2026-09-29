# Companions

Two tables: `companions` (the seeded catalog) and `character_companions` (one row per companion a character has). Models `Companion` and `CharacterCompanion` in `t20plus-api/app/Models/`.

## `companions` (migration 0032)
- `name`, `description`, `icon_file_name` (nullable).
- `type` enum: `parceiro`, `capanga`, `conjurado`, `familiar`, `montaria`.
- `parceiro_type` (nullable enum, only for `type` parceiro): `aberrante`, `adepto`, `ajudante`, `arauto`, `artesao`, `assassino`, `atirador`, `besta_de_carga`, `carregador`, `combatente`, `destruidor`, `emissario`, `espiao`, `fortao`, `guardiao`, `magivocador`, `medico`, `menestrel`, `mentor`, `mistico`, `perseguidor`, `sabio`, `vigilante`.
- `parceiro_tier` (nullable enum, only for parceiro): `iniciante`, `veterano`, `mestre`. Each tier of a parceiro type is its own row.
- `base_stats` (nullable JSON): the companion's starting stat block.
- `character_related_effects` (nullable JSON): `power grant` entries, what the character receives while the companion is with them.
- `source_spell_id`, `source_power_id` (nullable FKs to `spells` and `powers`): what grants this companion.

## `character_companions` (migration 0033)
- `character_id` (FK, cascade delete), `companion_id` (FK to `companions`, required).
- `name` (nullable): a custom name; the template's name otherwise.
- `extra_character_related_effects` (nullable JSON): `power grant` entries a spell or power adds for the character beyond the template's `character_related_effects`.
- `companion_related_effects` (nullable JSON): the `companion_*` effects of the spell enhancements checked when the companion was cast, copied by `store`.
- `current_pv` (nullable integer): the current value only. Max PV is calculated in the frontend.
- `granted_spells` (nullable JSON): `{ spell_ids, other_source_spell_ids }`, the spell ids this companion actually added to the character's first level; only those are removed again.
- `CharacterCompanion` has `character` and `companion` relations.
- Routes: `POST/PATCH/DELETE characters/{character}/companions[/{id}]` (each returns the character's full companion list; `update` accepts only `name` and `current_pv`), `GET companions` (catalog).

## Base stats and effects
- `base_stats` keys are `base_<stat>`: `size` (numeric: -2 Minúsculo, -1 Pequeno, 0 Médio, 1 Grande, 2 Enorme, 3 Colossal), `defense`, `movement`, `str`, `dex`, `con`, `int`, `knw`, `car`, `fortitude`, `reflexes`, `vontade`, `max_pv`, `attack` (`dmg`, `reach`, `damage_types`), `immunities`. `defense` and `reflexes` may be the string `caster`.
- `calculate-companion-stats.ts` resolves them: `companion_<stat>` `set` is the new base, `companion_mod_<stat>` `add`/`add_per_size` stack (a shared `stack_group` keeps the highest), `multiply` applies last. Size resolves first.
- Effects come from the row's `companion_related_effects` plus every active character effect (`getActiveEffects`) whose tag starts with `companion_`.
- The companion modal reads every stat from that resolved result.
- Modal: name card (rename page), Vida card (PV add/remove page), stat rows, resistance rows (`I` for immune), attack rows, description, Remover.

## Granted powers
- A companion grants powers through `character_related_effects` (seeded) and the row's `extra_character_related_effects`: `tag: power, op: grant` entries, plus every descendant power those grant.
- Each is a `character_active_effects` row with `source_companion_id` set (FK to `character_companions`, cascade on delete); `is_active` = the power is passive. The source is `companion_granted`, ids 15000-15999 (`CompanionGrantedPowerSeeder`).
- A power with a `companion` `grant` effect (the Arcanista Familiar picks, ids 2014-2041) creates the companion row when granted (`grantPower`, character creation) and deletes it when revoked (`revokePower`). The companion template's `source_power_id` is that power.
- `ManagesPowers`: `grantCompanion` (row + `syncCompanionPowers`), `revokeCompanion` (row delete; the cascade removes its power rows), `syncCompanionPowers` (grants missing, revokes stale, keeps existing rows).
- A companion whose template has `source_power_id` can't be removed through the API (422); it is removed with its power. A spell-summoned companion can.
- A `companion` grant on a checked spell enhancement (Gênese Elemental) creates `count` + `count_bonus` rows in one `POST` (`count`, 1-20), on any spell, regardless of the resist result.
- Spells the granted powers grant: `grant_spell` ids go into the first level's `spell_ids` (skipped when the character already knows the spell), `grant_or_reduce_spell_pm_cost_by_1` ids into its `other_source_spell_ids`; `syncCompanionSpells` records what it added in `granted_spells` and strips exactly that when the power or the companion goes.
- `POST`/`DELETE` companions return `{ character_companions, active_effects }`; the power grant/revoke endpoints return `character_companions` on the character.

## Character sheet
- `character-main` has a "Companheiros" expandable card between Magias and Condições (`companionsExpanded`, `toggleCompanions`, classes `companions-section`/`companions-title`).
- Expanded, it shows five labeled subsection rows: Parceiros, Capangas, Conjurados, Familiares, Montarias.

## Rules reference
- `claude-stuff/tormenta-book-rules/companions.md`: the parceiro rules (type and tier bonuses, no stats or actions, one helped character at a time).
