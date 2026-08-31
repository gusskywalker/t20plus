# Tag System

How T20Plus represents "this thing modifies/grants something" across the
whole schema — powers, accessories, armors, origins, gods, classes. Written
so the full picture is in one place instead of scattered across migration
comments. No resolver actually reads any of this yet (all deferred) — this
is purely the data shape.

## `powers.usability` — how to pick the right value

Work through these **in order**; the first one that fits is the answer.
Getting this wrong on the first pass has already happened twice (Percepção
Temporal, Rejeição Divina/Afinidade com a Tormenta) — read this before
seeding a new power, don't just pattern-match against the nearest-looking
example.

1. **Always-on, nothing to activate, no decision ever?** → `passive`
   (Vontade de Ferro, Membro da Igreja)
2. **Does using it require the player to actively *do* something as its own
   standalone act** — not riding on another roll — **even if the effect
   then lasts a while afterward?** → `action` (Medicina; Percepção
   Temporal — it activates on its own, then persists for a `duration`, but
   the activation itself isn't part of any other roll)
3. **Does it modify a roll the player is *already* making, as a genuine
   choice a rational player could decline** (usually because it costs a
   resource)? → `toggle` (Ataque Especial — costs PM, rides on the attack
   roll you're already making, worth declining if you want to save PM)
4. **Does it apply because an external condition is true, where declining
   it would never make sense once that condition is real** (usually free,
   `pm_cost: 0`)? → `trigger` (Êxtase da Loucura — enemy fails a save, no
   cost, no reason to decline; Rejeição Divina / Afinidade com a Tormenta —
   situational defensive bonus, no cost, no reason to decline)

**The test that matters is "would a rational player ever decline this,"
not "whose roll does it touch."** Rejeição Divina and Afinidade com a
Tormenta both modify the *character's own* resistance roll — that looks
like `toggle` at a glance (same shape as Ataque Especial: a bonus applied
to a roll the character is making) — but they're free and purely
situational, so there's never a real choice to make once the condition is
real. That's `trigger`, not `toggle`, despite modifying the character's own
roll. Don't categorize by which roll gets touched; categorize by whether
the player has a genuine reason to say no.

**This distinction is for the future automated combat engine, not for how
things look right now.** On a manual roll screen (no combat automation),
`toggle` and `trigger` powers relevant to a roll show up exactly the same
way — both just appear in the list of possible options for that roll, and
the player decides for themselves whether each one applies, same as
`toggle` always worked. The categorization only starts *behaving*
differently once real combat context exists to check against: `trigger`
powers fire automatically with no player input, `toggle` powers still wait
for the player to opt in even then. Get the categorization right now anyway
— it's what lets the later engine work correctly without re-triaging every
power that's already been seeded.

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
  (`grant`, `trains`). **Polymorphic**: usually a literal number (a flat
  bonus), but can also be an attribute code (`str`/`dex`/`con`/`int`/`knw`/
  `car`) meaning "the character's current effective value for that
  attribute, looked up at resolution time" rather than a fixed number (e.g.
  Percepção Temporal: `value: 'knw'` = add current Sabedoria). The resolver
  tells the two apart by inspecting what it got — no separate field marks
  which kind it is, deliberately (see the id-vs-name/no-added-noise
  reasoning throughout this doc).
- **Extra fields** — some tags need more than `{tag, op, value}` to fully
  identify their target or behavior (e.g. `skill_id`, `per_levels`, `limit`,
  `stack_group`). Add fields as needed rather than forcing everything
  through `value` alone.

## Operations list

- **`add`** — numeric bonus, summed with every other source.
- **`set`** — overrides to a fixed value (not summed).
- **`grant`** — boolean capability/ownership, no magnitude ("you have this power/item").
- **`trains`** — skill-only; marks the skill trained, not a numeric bonus.
- **`add_per_level`** — scales with the character's current total level: `floor(level / per_levels) * value`.
- **`waive`** — excuses the character from a penalty/mechanic named by `tag`, for the first `value` occurrences.

Note: a trigger-based power's *consequence* still uses an ordinary op like
`add` above (see `temp_pm` below) — there's no separate "trigger" op. The
"when does this fire" question is answered by `powers.usability = 'trigger'`
+ `powers.trigger_on`, a real column, not an op. See "Trigger conditions"
below.

## Tags list

- **`mod_str` / `mod_dex` / `mod_con` / `mod_int` / `mod_knw` / `mod_car`** — attribute modifier (`add`). Universal convention so one future resolver sums these regardless of source. Not yet actually seeded on any row.
- **`mod_pm`** — bonus Pontos de Mana (`add_per_level`). Seeded on Vontade de Ferro.
- **`mod_hit` / `mod_dmg`** — attack roll / damage roll modifier (`add`). `mod_hit` seeded on Percepção Temporal (attribute-sourced, see `value`'s polymorphism above); `mod_dmg` documented but not yet seeded — Ataque Especial's bonus is a player-split choice at activation time, handled as a special case instead.
- **`mod_def`** — Defesa modifier (`add`). Seeded on Percepção Temporal.
- **`skill`** (+ `skill_id`) — targets a skill by id (`add` = numeric bonus, `trains` = becomes trained).
- **`power`** (+ `power_id`) — the character gains a specific power (`grant`).
- **`accessory`** (+ `accessory_id`) — the character gains a specific accessory (`grant`).
- **`armor`** (+ `armor_id`) — the character gains a specific armor (`grant`).
- **`resting`** — rest quality provided by a source (`set`; e.g. Membro da Igreja sets it to 1 = "hospedagem confortável"). Scale beyond 0/1 not defined yet.
- **`tormenta_power_carisma_loss`** — names the not-yet-built Carisma-loss-per-Tormenta-power mechanic (`waive`), so a power can waive it for the first N occurrences without the resolver hardcoding a specific power. See `t20-rules-summary.md`, "Tormenta Powers & Carisma Loss."
- **`temp_pm`** (+ optional `limit`) — temporary PM, a distinct resource from `mod_pm` (the permanent pool bonus): granted by `add`, resets on some boundary the runtime tracks (e.g. "por cena," not encoded here), and can carry a `limit` referencing an attribute code — the max amount grantable this way within that boundary (e.g. Êxtase da Loucura: `{tag: 'temp_pm', op: 'add', value: 1, limit: 'knw'}`, capped at Sabedoria per scene).

## `limit` — one field name, two meanings by context

`limit` shows up in two different roles depending on what it's attached to,
deliberately reusing one field name rather than inventing a second (the
resolver already has to interpret every entry with full knowledge of its
tag/op, so it can tell these apart from context, same as it does for
`value`'s polymorphism):

- On `temp_pm` (`op: add`, numeric `value`): an **accumulation cap** — the
  max total grantable within some time boundary (e.g. per scene).
- On an attribute-sourced bonus (`value` is an attribute code, e.g.
  Percepção Temporal's `mod_hit`/`mod_def`/`skill` entries): a **ceiling on
  the computed result** — `min(current <value attribute>, current <limit
  attribute-or-"level">)`. E.g. `{value: 'knw', limit: 'level'}` = add
  current Sabedoria, but never more than the character's current level.

## Stacking (`stack_group`)

Optional field on an `add` entry. Absent = stacks normally with everything
(the default for every effect seeded so far — two different `mod_hit`
bonuses with no `stack_group` both just apply and sum, same as an armor
bonus and a weapon bonus normally would). Present = this entry does **not**
stack with any other entry sharing the same `stack_group` value; when two or
more collide, only the best one applies.

Naming convention: `bonus_<target>_<attribute>` (general→specific, same
ordering as `mod_pm`/`enemy_fails_save_<tipo>`), e.g. `bonus_hit_knw`. Note
this is scoped to *target + attribute*, not just *attribute* or just
*target* — verified against real source text (Percepção Temporal doesn't
stack with another Sabedoria-to-Defesa source, but two different Lutador
powers — Braços Calejados adding FOR to Defesa, Sexto Sentido adding SAB to
Defesa — have no such clause between them despite sharing a target and one
even sharing the same attribute+target as a hypothetical case). The
restriction lives in each specific power's own text, not a universal system
rule — don't assume any two same-tag `add` entries compete unless their
source text says so. `stack_group`'s value is technically reconstructable
from `tag` + `value` in the common case, but is kept as an explicit field
since some source text restricts more broadly than plain tag+attribute would
imply (per the note above).

## Duration (`powers.duration`)

How long an activated power's effect lasts: `turn` / `scene` / `day`, or
null (just the one roll it was toggled for — e.g. Ataque Especial). Unlike
`tag`/`trigger_on`, this **is** a real closed enum — T20 draws durations
from a small, system-defined list (same reasoning as `action_cost`), not an
open vocabulary discovered per-power. Expand the enum if a duration category
these three don't cover shows up.

Nothing auto-expires anything yet — a power with a duration just means the
player turns it on and later turns it off themselves, tracked via a future
"currently active" list on the character. No scene/turn/day boundary
tracking exists yet either.

## Trigger conditions (`powers.trigger_on`)

Only meaningful when `powers.usability = 'trigger'` — names the external
condition that makes the power fire (e.g. Êxtase da Loucura: an enemy fails
a save; Rejeição Divina: targeted by a divine spell). Before combat is
automated, this is what a future roll screen would use to filter "which of
this character's powers could apply to the roll I'm making" — the player
still confirms it applies (we can't verify real combat context yet), but
`trigger_on` is what makes it show up as an option at all, same as it will
later be what makes the engine fire it with no player input needed.

Plain string column, not a DB enum, same reasoning as `tag`: this vocabulary
grows as new powers get seeded, and forcing a migration for every new
condition would defeat the point of a low-friction content pipeline.
Document new values here as they show up:

- **`enemy_fails_save_vontade`** — a creature fails a Vontade test (as
  opposed to Reflexos or Fortitude — the save type is the part that varies
  between different trigger powers, so it's always spelled out).
- **`targets_you_spell_divine`** — a divine spell is cast targeting the
  character with this power.

Naming: general condition category first, narrowing qualifiers after —
same left-to-right order as `mod_pm`/`bonus_hit_knw` — so a resolver can
prefix-match to find every trigger in a broad family regardless of its
specific narrowing. Two families established so far:

- `enemy_fails_save_<tipo>` / `enemy_succeeds_save_<tipo>` — `<tipo>` is
  `vontade`/`reflexos`/`fortitude`. (Very common — spells constantly key off
  "if the target fails/succeeds its save.")
- `targets_you_<what>` / `targets_you_<what>_<narrower>` — e.g.
  `targets_you_spell` (any spell, no school distinction needed) vs.
  `targets_you_spell_divine` (divine specifically). Only add the narrowing
  suffix when the power actually cares which kind; use the bare general form
  when it doesn't.

Not all variants are seeded yet in either family — add the specific one as
each real power needs it.

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
`mod_hit`, `resting`, `tormenta_power_carisma_loss`, `temp_pm`) are named
with a plain string tag instead — there's nothing to reference by id. The
same reasoning is why `powers.trigger_on` is a plain string column, not an
enum — see "Trigger conditions" above.
