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
- `companion_related_effects` (nullable JSON): modifiers from spells, enhancements and powers that change the companion's own stats, meant to be summed onto `base_stats` in the frontend.
- `current_pv` (nullable integer): the current value only. Max PV is calculated in the frontend from `base_stats` plus `companion_related_effects`.
- `CharacterCompanion` has `character` and `companion` relations.

## Character sheet
- `character-main` has a "Companheiros" expandable card between Magias and Condições (`companionsExpanded`, `toggleCompanions`, classes `companions-section`/`companions-title`).
- Expanded, it shows five labeled subsection rows: Parceiros, Capangas, Conjurados, Familiares, Montarias.

## Rules reference
- `claude-stuff/tormenta-book-rules/companions.md`: the parceiro rules (type and tier bonuses, no stats or actions, one helped character at a time).
