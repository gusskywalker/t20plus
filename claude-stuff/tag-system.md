# Tag System

How T20Plus represents "this thing modifies/grants something" across the
whole schema — powers, accessories, armors, origins, gods, classes. Written
so the full picture is in one place instead of scattered across migration
comments. No resolver actually reads any of this yet (all deferred) — this
is purely the data shape.

## The two column families: `effects` vs `grants`

- **`effects`** (on `powers`, `accessories`, `armors`) — these entities
  genuinely modify a character's stats/rolls. A power, a worn accessory, a
  worn armor: things a character *has* that affect them directly.
- **`grants`** (on `origins`, `gods`) — these entities don't have effects of
  their own. They just hand out *other* things (skills, powers, items),
  which may themselves have effects. An origin or a god is a distribution
  mechanism, not a stat modifier.

Both columns hold the same underlying entry shapes described below — the
name difference is purely about what the owning row conceptually *is*, not a
difference in structure. Don't rename one to match the other.

## The base entry shape

Every plain (non-choice-group) entry is `{ tag, op, value?, ...extra }`:

```json
{ "tag": "mod_pm", "op": "add_per_level", "value": 1, "per_levels": 2 }
```

- **`tag`** — what's being targeted. Either a universal modifier code
  (`mod_str`, `mod_pm`, ...) or the name of a target table (`skill`,
  `power`, `accessory`, `armor`) paired with a `<tag>_id` field.
- **`op`** — how to interpret `value` (or how to apply the entry at all).
  Not strictly arithmetical — see Ops below.
- **`value`** — meaning depends on `op`; absent for ops that don't need one
  (`grant`, `trains`).
- **Extra fields** — some tags need more than `{tag, op, value}` to fully
  identify their target or behavior (e.g. `skill_id`, `per_levels`). Add
  fields as needed rather than forcing everything through `value` alone.

## Operations list

- **`add`** — numeric bonus, summed with every other source.
- **`set`** — overrides to a fixed value (not summed).
- **`grant`** — boolean capability/ownership, no magnitude ("you have this power/item").
- **`trains`** — skill-only; marks the skill trained, not a numeric bonus.
- **`add_per_level`** — scales with the character's current total level: `floor(level / per_levels) * value`.
- **`waive`** — excuses the character from a penalty/mechanic named by `tag`, for the first `value` occurrences.

## Tags list

- **`mod_str` / `mod_dex` / `mod_con` / `mod_int` / `mod_knw` / `mod_car`** — attribute modifier (`add`). Universal convention so one future resolver sums these regardless of source. Not yet actually seeded on any row.
- **`mod_pm`** — bonus Pontos de Mana (`add_per_level`). Seeded on Vontade de Ferro.
- **`mod_hit` / `mod_dmg`** — attack/damage roll modifier (`add`). Documented but not yet seeded on a real row — Ataque Especial's bonus is a player-split choice at activation time, handled as a special case instead.
- **`skill`** (+ `skill_id`) — targets a skill by id (`add` = numeric bonus, `trains` = becomes trained).
- **`power`** (+ `power_id`) — the character gains a specific power (`grant`).
- **`accessory`** (+ `accessory_id`) — the character gains a specific accessory (`grant`).
- **`armor`** (+ `armor_id`) — the character gains a specific armor (`grant`).
- **`resting`** — rest quality provided by a source (`set`; e.g. Membro da Igreja sets it to 1 = "hospedagem confortável"). Scale beyond 0/1 not defined yet.
- **`tormenta_power_carisma_loss`** — names the not-yet-built Carisma-loss-per-Tormenta-power mechanic (`waive`), so a power can waive it for the first N occurrences without the resolver hardcoding a specific power. See `t20-rules-summary.md`, "Tormenta Powers & Carisma Loss."

## The choice-group wrapper

For "pick N of these options," an entry can instead be a choice group
instead of a plain `{tag, op, value}` entry:

```json
{ "type": "choice", "label": "Itens", "picks": 2, "options": [ ...plain entries... ] }
```

- **`options`** holds plain entries (the same shape as above).
- **`picks`** = how many of `options` the player must select; only those
  selected ones actually apply. `picks` can equal `options.length` — that
  just means every option is mandatory (no real choice), while keeping the
  identical shape so the frontend renders every group the same way
  (checkboxes capped at `picks`) instead of a separate "always granted" code
  path.
- **`label`** — section heading for the frontend (e.g. "Itens", "Perícias e
  Poderes").

Used inside `origins.grants` and `gods.grants`. `classes.skills` uses the
bare `{picks, options}` pair (no `type`/`label` wrapper) since a class row
only ever needs skill-option groups, not a mixed/labeled set like an
origin's or god's grant pool.

## Prerequisites (a separate, differently-shaped column)

`powers.prerequisites` is not `{tag, op, value}` — it's typed by `type`
instead, since prerequisites are checks, not modifiers:

```json
[
  { "type": "attribute", "attribute": "str", "min": 1 },
  { "type": "power", "power_id": 5 },
  { "type": "class", "class_ids": [1], "min_level": 2 },
  { "type": "skill", "skill_id": 3 }
]
```

- `attribute` — `min` value required on that attribute code (str/dex/con/
  int/knw/car — a fixed enum, not an id reference, since there's no
  "attributes" table).
- `power` / `class` / `skill` — reference their target by id (same
  convention as everywhere else). `class` holds a **list** of ids (OR within
  the entry) so a prerequisite shared by multiple classes needs only one
  entry.

## The id-vs-name referencing rule

Reference by id (`skill_id`, `power_id`, `accessory_id`, `armor_id`,
`class_ids`) whenever the target is a row in an already-seeded, stable
reference table — every seeder hardcodes its own ids explicitly (see each
seeder's own comment) specifically so downstream seeders/files can reference
them directly without a runtime lookup or risking drift.

There's currently no case left where a name/slug string is used instead —
early on, `power` prerequisite entries referenced powers by name (reasoning:
hand-written data with no guaranteed seed order), but that was superseded
once every seeder started hardcoding explicit ids, and existing name
references were migrated to ids (2026-08-30).

Abstract mechanics/resources that aren't rows in any table (`mod_pm`,
`mod_hit`, `resting`, `tormenta_power_carisma_loss`) are named with a plain
string tag instead — there's nothing to reference by id.
