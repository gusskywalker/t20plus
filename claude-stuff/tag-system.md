# Tag System

How powers, accessories, armors, origins, and classes describe what they do.
Two JSON columns carry it all: `effects` (things that modify a character
directly — powers, accessories, armors) and `grants` (things that hand out
other things — origins only; see the note on gods below). Everything below
is one shared vocabulary reused across both.

## `type`, `usability`, `action_cost`, `duration`

See `tag-library.md` for the value lists. Notes that don't fit a one-liner:

- `type`: `class` is the choosable pool a class picks from at level-up
  (step 9's "Níveis e Poderes" dropdowns); `class_granted` is what that
  same class hands you automatically at a given level with no choice
  (e.g. every Ataque Especial tier — `prerequisites.min_level` alone
  decides when a Guerreiro has it). `item_granted`/`complication_granted`/
  `age_granted` are all synthetic, app-specific buckets — never
  player-picked directly, excluded from any "choose your powers" list,
  referenced by id from `item_improvements`/`complications.power_ids`/
  the frontend's hardcoded `AGE_BRACKETS.powerIds`
  (character-creation-step-7.ts — no `age_brackets` DB table; removed
  2026-09-01, only ever used in the one spot resolving a bracket's power
  ids at character-save time, didn't justify a fetched table). `tormenta`
  costs Carisma when
  taken — not implemented yet.
- `usability`: `trigger` also shows up on manual rolls today (e.g. a
  Vontade roll) with the player self-reporting whether it applies — same
  self-report pattern covers movement-based powers, since position isn't
  tracked; the combat engine will eventually take this over. `active` vs.
  `roll_toggle`: whether an active power resolves instantly or persists is
  `duration`'s job, not `usability`'s (Medicina resolves instantly,
  Percepção Temporal persists). `roleplay` differs from `passive` in that
  it's a chosen action whose resolution never touches the app at all (no
  `effects`, no meaningful `pm_cost`/`duration`/`trigger_on`) — `passive`
  is a constant background fact even with zero numeric effect.
- `action_cost`: see `t20-rules-summary.md` for the actual ação
  padrão/movimento/completa/extra/livre rules. `none` covers
  `passive`/`trigger`/`roll_toggle` — none of them cost a separate action.
- `duration`: only set on `active` powers. Will be used by the combat
  engine to know when an active effect expires; nothing auto-expires yet,
  the player turns it off manually for now.

## `trigger_on`

Strictly for the combat engine. Only set when `usability = trigger`.

- JSON array of strings, not one bare string — a power can care about more
  than one atomic condition (e.g. Júbilo na Dor: `enemy_is_hit` AND
  `you_take_damage`). Keep every value atomic — no compound one-offs — the
  intended resolver works by action-type dictionary lookup ("rolling an
  attack? scan every character power for these values"), which only works
  if each value means one thing a lookup can search for directly.
- Not an enum — grows as new powers get seeded.
- General-first, then narrowing — prefix-matchable.
- `targets_you_*` — incoming, done to you.
- `enemy_*` — outgoing, happens to an enemy because of you.
- Exception: `you_*` (not `targets_you_*`) is for something that happens to
  *you* specifically because of your own action or state, not an external
  source acting on you — e.g. `you_take_damage` (Júbilo na Dor). Distinct
  from `enemy_*`, which is about something happening to an enemy.

## `range`

Meters (integer, matches `weapons.base_reach`'s convention, always meters —
never a curto/médio/longo enum). Null = personal (affects only the
character holding the power — true for almost everything seeded so far).
Set when a power's effects reach beyond the character, e.g. an aura
affecting nearby enemies (Esotérico - Matéria Vermelha, `range: 9`).

**Never used for automated distance math** — there's no board/grid at all
(see `combat-engine-plans.md`). Purely a flag so a roll screen can surface
"this power might apply" to whoever's rolling (e.g. a master rolling a
save for an NPC standing near the wielder), who then self-reports whether
the target is actually in range. Same permanent self-report trust model as
movement-conditional powers — this never graduates to real automation.

## `prerequisites`

Array of typed requirement checks, e.g.:

```json
[
  { "type": "attribute", "attribute": "str", "min": 1 },
  { "type": "power", "power_id": 5 },
  { "type": "class", "class_ids": [1], "min_level": 2 },
  { "type": "skill_trained", "skill_id": 3 },
  { "type": "god", "god_id": 1 },
  { "type": "character_level", "min": 5 },
  { "type": "race", "race_ids": [1] }
]
```

`power`/`class`/`skill_trained`/`god`/`race` reference their target by id.
`skill_trained` is deliberately not just `skill` — it only ever checks "is
the character trained in this skill," never a numeric bonus threshold; a
future power needing the latter gets its own distinct type instead of
overloading this one. `class` and `race` hold a list (any one qualifies) —
`race` gates a `races` typed power's step 9 level-up pool entry the same
way `class` gates a `class` typed one. `god` is how a `divine_granted`
power ties to its
deity — **gods don't have a `grants` column** (removed 2026-08-31): a god
only ever granted powers, and powers already have a prerequisite system, so
a `gods.grants` list was redundant with just putting `{type: 'god',
god_id}` on the power itself. That also makes it reusable at every future
level-up, not just a one-time grant step — `origins.grants` stays, since
origins also grant skills/items, which have no prerequisite system to
piggyback on.

`character_level` gates on the character's total level (summed across every
class — `orderedClassIds`/`totalLevel` on the frontend draft), not one
class's own relative level the way `class`'s `min_level` is. First used for
Aumento de Atributo's 4 patamar-gated tiers per attribute (ids 46-69):
each tier chains a `power` prerequisite on the previous tier plus a
`character_level` floor (5/11/17 — Veterano/Campeão/Lenda; Iniciante has
neither), so "only once per patamar per attribute" falls out of the
prerequisite chain itself — no separate "count how many times this was
picked" validation needed anywhere.

`power_type` (+ `value`, one of `powers.type`'s values) — "requires at
least one other power of this category," e.g. `{type: 'power_type', value:
'tormenta'}` for Armamento Aberrante's "outro poder da Tormenta." Different
from `power` (a specific power by id) — this checks the category, not one
exact power, since most Poderes da Tormenta share this same prerequisite.

## `effects` (and `grants`)

Array of entries, each `{ tag, op, value?, ...extra }`:

```json
{ "tag": "mod_max_pm", "op": "add_per_level", "value": 1, "per_levels": 2 }
```

- `tag` — what's targeted (see `tag-library.md`).
- `op` — see `tag-library.md` for the full list. `override` is currently
  only used on `skill_attribute` (a power that changes which attribute
  governs a skill test).
- `value` — usually a number; can also be an attribute code
  (`str`/`dex`/`con`/`int`/`knw`/`car`), meaning "character's current value
  for that attribute" instead of a fixed number.
- `limit` — caps the result (attribute-sourced bonuses) or caps
  accumulation over time (`temp_pm`), depending on what it's attached to.
- `stack_group` — optional; entries sharing the same value don't stack,
  only the best applies. Absent = stacks normally.
- `when_category` — optional; restricts an entry to only apply when the
  item carrying it is installed on/is a specific category (see
  `tag-library.md`'s item categories). Absent = universal, always applies.
  Only meaningful on `item_improvements` (materials like Matéria Vermelha
  behave differently per item type — see below).
- `when_type` — optional, pairs with `when_category` for a finer split —
  matches the target item's own `type` column (e.g. `armors`/`shields`
  both have `light`/`heavy`). Used when a category alone isn't specific
  enough (Matéria Vermelha's miss-chance differs for light vs heavy).

`grants` also supports a choice-group entry instead of a plain one:
`{ "type": "choice", "label": "...", "picks": N, "options": [...] }` — pick
`picks` of `options`. `classes.skills` uses the bare `{picks, options}` pair
without the wrapper.

See `tag-library.md` for the full effect-tag, prerequisite-tag, and
trigger_on-tag list.

## Referencing by id vs. by name

Reference by id (`skill_id`, `power_id`, `accessory_id`, `armor_id`,
`class_ids`) whenever the target is a row in an already-seeded table —
every seeder hardcodes its own ids so other files can reference them
directly. Plain string tags are for things that aren't rows in any table
(`mod_max_pm`, `resting`, `temp_pm`, every `trigger_on` value).

## Character inventory & item improvements

`character_inventory` — a character owns a specific item instance.
`item_type` (`accessory`/`armor`/`weapon`/`shield`) + `item_id` says which
item; `worn` says if it's equipped. Polymorphic (one table, not one per
item type) so "show this character's whole inventory" stays a single
query.

**Exotéricos are not a 5th item_type / catalog.** A unique named item
exotérico (e.g. Cajado Arcano) is fundamentally still a weapon, armor,
accessory, or shield — it needs the exact same `effects`/`worn` machinery
those tables already have, so it's just a row in whichever matches its real
nature, flagged by an `is_exoteric` bool (all four tables have one).
Confirmed 2026-08-31 by real examples: Bolsa de Pó is an accessory,
Cajado Arcano is a weapon (uses Bordão's stats, explicitly *empunhado*
— the weapon-specific verb, not "worn" like armor/accessories). This is
also why `weapons` gained an `effects` column, matching `accessories`/
`armors` — Cajado Arcano's +1 arcane PM limit/CD needed somewhere to live.

Melhorias (improvements) and encantamentos (enchantments) — separate item
slots, so `character_inventory` has two separate JSON id-lists:
`improvement_ids` and `enchantment_ids`, each referencing their own catalog
table (`item_improvements` built; `enchantments` not yet).

`item_improvements` — one row per named melhoria (e.g. "Certeira," "Cruel").
`applies_to` is a JSON array of category strings (see `tag-library.md`'s
item categories) since one improvement often covers several at once.
`effects` is the same shape as everywhere else. `extra_cost`
is only set on `is_material` rows (special materials have their own cost);
regular melhorias follow a flat by-count price/CD table instead, tracked
elsewhere, not per-row. `is_material` also flags the "only one material per
item" rule the app needs to enforce.

## Parked — not designed yet

Matéria Vermelha (`item_improvements` id 2) — fully seeded: universal
Carisma penalty, weapon/armor(light+heavy)/shield/esoteric(×2)/tool grants
(powers 14-19) all wired via `when_category`/`when_type`. Esotérico split
into two powers — 17 (Portador, self, no range) and 19 (Inimigos Próximos,
`range: 9`) — rather than one power half-implementing an AoE; `range`
exists specifically so this pattern works without real AoE math (see the
`range` section above). One real gap remains: Lefeu/Lefou immunity
(asymmetric — weapon part immune for Lefou+Lefeu, armor part immune for
Lefeu only — no race-exception mechanism exists, treated as self-reported/
narrative for now).

Corromper Equipamento (Aharadak) — still not seeded. Can now reference
Matéria Vermelha (id 2). Both now seeded — Armamento Aberrante (power 20,
`type: 'tormenta'`, new `power_type` prerequisite) and Corromper
Equipamento (power 21, `divine_granted`). Aharadak is done for practical
purposes: all 5 divine_granted powers (9-12, 21) plus everything they
reference exist. Two things left deliberately unmodeled on power 21/20,
noted in their own seeder comments rather than half-built: player-choice-
at-activation granting the matching Matéria Vermelha power (special case,
not generic — same treatment as other choice-at-activation powers), and
the -1 PM discount checking a weapon's provenance (nothing tracks item
source yet). Also unmodeled on power 20: temporary weapon-copy creation
with scaling damage steps — needs weapon templating + live power-count
scaling, neither exists.
