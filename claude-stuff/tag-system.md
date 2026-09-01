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
- `item_granted` — synthetic power an item_improvement `grant`s, never
  player-picked, excluded from any "choose your powers" list (e.g. Farpada
  → "Causar Sangramento")

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
- `roleplay` — a chosen action, like `active`, but nothing about it is ever
  resolver-facing: no `effects`, no meaningful `pm_cost`/`duration`/
  `trigger_on`, not even a self-reported roll-screen toggle. The roll (if
  any) and its consequences resolve entirely in narrative between player
  and master (e.g. Espalhar a Corrupção). Distinct from `passive` — passive
  is a constant background fact even with zero numeric effect; `roleplay`
  is a chosen action whose resolution never touches the app at all.

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

`power_type` (+ `value`, one of `powers.type`'s values) — "requires at
least one other power of this category," e.g. `{type: 'power_type', value:
'tormenta'}` for Armamento Aberrante's "outro poder da Tormenta." Different
from `power` (a specific power by id) — this checks the category, not one
exact power, since most Poderes da Tormenta share this same prerequisite.

## `effects` (and `grants`)

Array of entries, each `{ tag, op, value?, ...extra }`:

```json
{ "tag": "mod_pm", "op": "add_per_level", "value": 1, "per_levels": 2 }
```

- `tag` — what's targeted (see full list below).
- `op` — `add` (sums), `set` (overrides), `grant` (you just have it),
  `trains` (skill becomes trained), `add_per_level` (scales with level),
  `waive` (excuses the first N occurrences of whatever `tag` names),
  `override` (replaces a fixed property with a new value — currently just
  `skill_attribute`, e.g. a power that changes which attribute governs a
  skill test).
- `value` — usually a number; can also be an attribute code
  (`str`/`dex`/`con`/`int`/`knw`/`car`), meaning "character's current value
  for that attribute" instead of a fixed number.
- `limit` — caps the result (attribute-sourced bonuses) or caps
  accumulation over time (`temp_pm`), depending on what it's attached to.
- `stack_group` — optional; entries sharing the same value don't stack,
  only the best applies. Absent = stacks normally.
- `when_category` — optional; restricts an entry to only apply when the
  item carrying it is installed on/is a specific category (`weapon`/
  `armor`/`shield`/`esoteric`/`tool`). Absent = universal, always applies.
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

## Every tag, one line each

| tag | lives in | meaning |
|---|---|---|
| `mod_str`/`mod_dex`/`mod_con`/`mod_int`/`mod_knw`/`mod_car` | effects | attribute modifier (first seeded on age_granted power "Criança") |
| `mod_pm` | effects | bonus Pontos de Mana |
| `mod_pv` | effects | bonus/penalty Pontos de Vida (e.g. Abatido's "–2 PV por nível", `add_per_level`) |
| `mod_size` | effects | size-category shift, same -2..+3 scale as `races.base_size` (e.g. age power "Tamanho Menor", `add: -1`) |
| `mod_hit` | effects | attack roll modifier |
| `mod_dmg` | effects | damage roll modifier |
| `mod_def` | effects | Defesa modifier |
| `skill` (+ `skill_id`) | effects/grants | targets a skill — bonus (`add`) or trained (`trains`). **Testes de resistência (Fortitude/Reflexos/Vontade) are ordinary skills here** (ids 10/26/29 — see `SkillSeeder.php`), not a separate mod_* tag family — always use `skill`+`skill_id`, e.g. Vontade de Ferro, Aharadak's Rejeição Divina, Matéria Vermelha, the medalhão accessory, and the "Protegido dos Deuses" age power all do this. |
| `skill_group` (+ `attribute`, optional `exclude_skill_id`) | effects | targets every skill currently resolving to that attribute for this character (respects `skill_attribute` overrides, not `skills.key_attribute` alone), minus any excluded skill |
| `skill_attribute` (+ `skill_id`, `value`) | effects | `override` — changes which attribute governs a skill's tests for this character; resolved live, never persisted |
| `power` (+ `power_id`) | effects/grants | grants a specific power |
| `accessory` (+ `accessory_id`) | grants | grants a specific accessory |
| `armor` (+ `armor_id`) | grants | grants a specific armor |
| `resting` | effects | rest quality a source provides |
| `temp_pm` | effects | temporary PM, separate from `mod_pm`'s permanent pool |
| `condition` (+ `condition_id`, `removal_check`, `removal_cd`, `removal_frequency`) | effects | inflicts a status condition on whoever the trigger applies to; the inflicting entry always supplies its own removal rule — `removal_check` is a `skill_id` (number) or a raw attribute code (string), same polymorphism as `value` — see `combat-engine-plans.md` |
| `tormenta_power_carisma_loss` | effects | marks the (unbuilt) Carisma-loss-per-Tormenta-power mechanic, so a power can `waive` it |
| `level_up_attribute_increase_lock` (+ `scope`) | effects | marks the (unbuilt) level-up Aumento de Atributo mechanic as blocked for a category of attributes — `scope` names which (e.g. `physical` — For/Des/Con); `op: grant` since it's a flag, not a numeric value (e.g. age power "Velho") |
| `self_damage` | effects | direct PV loss to whoever holds the power — not a `mod_*` roll modifier, an instantaneous deduction (e.g. Matéria Vermelha's weapon backlash) |
| `dodge_chance` | effects | flat % chance an incoming attack simply misses, regardless of the roll (e.g. Matéria Vermelha armor/shield's "borrada" visual) |
| `mod_dc` (+ `scope`) | effects | modifier to the CD other creatures must beat to resist a specific category of the character's own abilities (`scope` names which, e.g. `bard_abilities_non_spell`) |
| `damage_reduction` | effects | flat reduction to incoming damage; cumulative (`op: add`) sources on the same power build one running total, capped by `limit`, reset by `decay_after` (see Júbilo na Dor) |

## Every `trigger_on` value, one line each

| value | meaning |
|---|---|
| `enemy_fails_save_vontade` | a creature fails a Vontade test (Reflexos/Fortitude variants follow the same pattern when needed) |
| `enemy_is_hit_critical` | you land a critical hit on a creature |
| `enemy_is_hit` | you land any hit on a creature (general form of the above) |
| `targets_you_spell_divine` | a divine spell is cast targeting this character (`arcane`/`universal` variants follow the same pattern) |
| `you_take_damage` | you take damage from any source (part of the `you_*` family — see above) |
| `targets_you_tormenta` | targeted by a Tormenta effect/creature or an Aharadak devotee |

## Referencing by id vs. by name

Reference by id (`skill_id`, `power_id`, `accessory_id`, `armor_id`,
`class_ids`) whenever the target is a row in an already-seeded table —
every seeder hardcodes its own ids so other files can reference them
directly. Plain string tags are for things that aren't rows in any table
(`mod_pm`, `resting`, `temp_pm`, every `trigger_on` value).

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

Status conditions (Sangramento etc., from Farpada) — resolved: `conditions`
catalog built (Sangrando seeded), `condition` tag added (op `inflict`,
carries its own `removal_check`/`removal_cd`/`removal_frequency` — see
`combat-engine-plans.md`). Power 13 ("Farpada", `item_granted`) and item
improvement 1 ("Farpada") are both seeded, and `weapons` + the `weapon`
`item_type` now exist — the full chain is complete end to end, just no
actual weapon row has Farpada attached yet (no weapons seeded).

Still open: `weapons` has only Espada Curta and Cimitarra seeded so far;
weapon abilities (Ágil, Ocultável, etc.) now live in their own
`weapon_abilities` table (id/name/description/power_ids, same shape as
complications/age_brackets — `weapons.ability_ids` references it by id,
replacing the earlier plain-string-code column) but still have no
mechanical resolution, purely user-reported for now.

Exotéricos (Bolsa de Pó, Cetro Elemental, Cajado Arcano) — schema decided
(`is_exoteric` bool on `accessories`/`armors`/`weapons`/`shields`, no
separate catalog), but not seeding any yet. Most of the interesting ones
are triggered by casting a spell of a given school/type ("quando lança uma
magia de encantamento/ilusão...") — that needs a real spell system (schools,
casting, targeting) which doesn't exist at all yet. Deferred until that's
built, not just until `weapons`/`armors`/`accessories`/`shields` have more
rows.

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
