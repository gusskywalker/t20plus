# Tag System

How powers, accessories, armors, origins, and classes describe what they do.
Two JSON columns carry it all: `effects` (things that modify a character
directly — powers, accessories, armors) and `grants` (things that hand out
other things — origins only; see the note on gods below). Everything below
is one shared vocabulary reused across both.

## `type`

Which sourcebook category a power belongs to.

- `general` — Poderes Gerais
- `class` — Poderes de Classe
- `divine_granted` — Poderes Concedidos
- `races` — Poderes Raciais
- `tormenta` — Poderes da Tormenta (costs Carisma when taken — not
  implemented yet)
- `group` — Poderes de Grupo
- `resting` — grants a rest-quality bonus (app-specific bucket, not a
  sourcebook category)

## `usability`

How the player interacts with it.

- `passive` — always on, no decision ever (e.g. Vontade de Ferro)
- `trigger` — applies on an external condition; `trigger_on` names it and
  will be used by the combat engine. Also shows up on manual rolls today
  (e.g. a Vontade roll) with the player self-reporting whether it applies —
  same self-report pattern covers movement-based powers, since position
  isn't tracked.
- `roll_toggle` — rides a roll the player is already making, decided fresh
  every time, never persists (e.g. Ataque Especial)
- `active` — a standalone activation, not tied to any specific roll;
  whether it resolves instantly or persists is `duration`'s job, not this
  field's (e.g. Medicina resolves instantly, Percepção Temporal persists)

## `action_cost`

Which action-economy slot using the power costs, so the combat engine knows
how much a character can do on their turn (see `t20-rules-summary.md` for
the actual ação padrão/movimento/completa/extra/livre rules). Values:
`standard`, `movement`, `complete`, `extra`, `free`, `none`. `none` covers
`passive`/`trigger`/`roll_toggle` — none of them cost a separate action.

## `duration`

Self-explanatory: `turn` / `scene` / `day`, or null if the power resolves
instantly. Only set on `active` powers. Will be used by the combat engine to
know when an active effect expires; nothing auto-expires yet, the player
turns it off manually for now.

## `trigger_on`

Strictly for the combat engine. Only set when `usability = trigger`. A
plain string (not an enum — grows as new powers get seeded) naming the
condition that fires the power, e.g. `enemy_fails_save_vontade`.

## `prerequisites`

Array of typed requirement checks, e.g.:

```json
[
  { "type": "attribute", "attribute": "str", "min": 1 },
  { "type": "power", "power_id": 5 },
  { "type": "class", "class_ids": [1], "min_level": 2 },
  { "type": "skill", "skill_id": 3 },
  { "type": "god", "god_id": 1 }
]
```

`power`/`class`/`skill`/`god` reference their target by id. `class` holds a
list (any one qualifies). `god` is how a `divine_granted` power ties to its
deity — **gods don't have a `grants` column** (removed 2026-08-31): a god
only ever granted powers, and powers already have a prerequisite system, so
a `gods.grants` list was redundant with just putting `{type: 'god',
god_id}` on the power itself. That also makes it reusable at every future
level-up, not just a one-time grant step — `origins.grants` stays, since
origins also grant skills/items, which have no prerequisite system to
piggyback on.

## `effects` (and `grants`)

Array of entries, each `{ tag, op, value?, ...extra }`:

```json
{ "tag": "mod_pm", "op": "add_per_level", "value": 1, "per_levels": 2 }
```

- `tag` — what's targeted (see full list below).
- `op` — `add` (sums), `set` (overrides), `grant` (you just have it),
  `trains` (skill becomes trained), `add_per_level` (scales with level),
  `waive` (excuses the first N occurrences of whatever `tag` names).
- `value` — usually a number; can also be an attribute code
  (`str`/`dex`/`con`/`int`/`knw`/`car`), meaning "character's current value
  for that attribute" instead of a fixed number.
- `limit` — caps the result (attribute-sourced bonuses) or caps
  accumulation over time (`temp_pm`), depending on what it's attached to.
- `stack_group` — optional; entries sharing the same value don't stack,
  only the best applies. Absent = stacks normally.

`grants` also supports a choice-group entry instead of a plain one:
`{ "type": "choice", "label": "...", "picks": N, "options": [...] }` — pick
`picks` of `options`. `classes.skills` uses the bare `{picks, options}` pair
without the wrapper.

## Every tag, one line each

| tag | lives in | meaning |
|---|---|---|
| `mod_str`/`mod_dex`/`mod_con`/`mod_int`/`mod_knw`/`mod_car` | effects | attribute modifier (planned convention, not seeded yet) |
| `mod_pm` | effects | bonus Pontos de Mana |
| `mod_hit` | effects | attack roll modifier |
| `mod_dmg` | effects | damage roll modifier |
| `mod_def` | effects | Defesa modifier |
| `skill` (+ `skill_id`) | effects/grants | targets a skill — bonus (`add`) or trained (`trains`) |
| `power` (+ `power_id`) | effects/grants | grants a specific power |
| `accessory` (+ `accessory_id`) | grants | grants a specific accessory |
| `armor` (+ `armor_id`) | grants | grants a specific armor |
| `resting` | effects | rest quality a source provides |
| `temp_pm` | effects | temporary PM, separate from `mod_pm`'s permanent pool |
| `tormenta_power_carisma_loss` | effects | marks the (unbuilt) Carisma-loss-per-Tormenta-power mechanic, so a power can `waive` it |

## Every `trigger_on` value, one line each

| value | meaning |
|---|---|
| `enemy_fails_save_vontade` | a creature fails a Vontade test (Reflexos/Fortitude variants follow the same pattern when needed) |
| `targets_you_spell_divine` | a divine spell is cast targeting this character (`arcane`/`universal` variants follow the same pattern) |
| `targets_you_tormenta` | targeted by a Tormenta effect/creature or an Aharadak devotee |

## Referencing by id vs. by name

Reference by id (`skill_id`, `power_id`, `accessory_id`, `armor_id`,
`class_ids`) whenever the target is a row in an already-seeded table —
every seeder hardcodes its own ids so other files can reference them
directly. Plain string tags are for things that aren't rows in any table
(`mod_pm`, `resting`, `temp_pm`, every `trigger_on` value).

## Character inventory & item improvements

`character_inventory` — a character owns a specific item instance.
`item_type` (`accessory`/`armor` so far, `weapon`/`exoteric` once those
catalogs exist) + `item_id` says which item; `worn` says if it's equipped.
Polymorphic (one table, not one per item type) so "show this character's
whole inventory" stays a single query.

Melhorias (improvements) and encantamentos (enchantments) — separate item
slots, so `character_inventory` has two separate JSON id-lists:
`improvement_ids` and `enchantment_ids`, each referencing their own catalog
table (`item_improvements` built; `enchantments` not yet).

`item_improvements` — one row per named melhoria (e.g. "Certeira," "Cruel").
`applies_to` is a JSON array of category strings (`weapon`/`armor`/
`shield`/`esoteric`/`tool`/`clothing`) since one improvement often covers
several at once. `effects` is the same shape as everywhere else. `extra_cost`
is only set on `is_material` rows (special materials have their own cost);
regular melhorias follow a flat by-count price/CD table instead, tracked
elsewhere, not per-row. `is_material` also flags the "only one material per
item" rule the app needs to enforce.

## Parked — not designed yet

Aura Sagrada (Paladin) surfaced three real gaps: a 4th `duration` value
("sustentada"), area-of-effect/ally targeting (nothing today affects anyone
but the possessing character), and checking another power's/character's
live state at runtime (not a build-time `prerequisites` check). See
`combat-engine-plans.md`.
