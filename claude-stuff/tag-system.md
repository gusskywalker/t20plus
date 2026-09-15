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
  `age_granted`/`power_granted`/`race_granted`/`divine_granted`/
  `origin_granted` are all synthetic, app-specific buckets —
  never player-picked directly, excluded from any "choose your powers"
  list (`character-creation-step-9.ts`/`level-change-modal.ts`'s own
  pick-list filters are inclusion lists — only `general`/`tormenta`/
  `group`/`class` ever match, so every synthetic source falls
  through and is excluded automatically, no separate exclusion rule
  needed), referenced by id from `item_improvements`/
  `complications.power_ids`/the frontend's hardcoded
  `AGE_BRACKETS.powerIds` (character-creation-step-7.ts — no
  `age_brackets` DB table; removed 2026-09-01, only ever used in the one
  spot resolving a bracket's power ids at character-save time, didn't
  justify a fetched table) / another power's own `tag: 'power', op:
  'grant'` effect for `power_granted` — resolved transitively
  (`resolve-granted-power-ids.ts`) whenever a power is added to a
  character (creation's flat `power_ids` + per-level picks;
  `level-change-modal.ts`'s own level-up pick), same treatment as every
  other grant source. `tormenta` costs Carisma when taken — not
  implemented yet.
- `usability`: down to four values — `trigger`/`trigger_active` dropped
  2026-09-04 (no combat engine planned; both collapsed cleanly into the
  remaining ones once `trigger_on` was already gone). A former `trigger`
  power became `passive` if it's an automatic proc with no decision
  (Farpada's `on_critical_strike`) or `roll_active` if it still needs a
  per-roll self-judgment call (Rejeição Divina: "was I just targeted by
  divine magic on THIS roll?" — shows up on manual rolls today, e.g. a
  Vontade roll, same self-report pattern movement-based powers already
  use since position isn't tracked, and this is permanent — see
  `combat-engine-plans.md`). A former `trigger_active` power became
  `active` (Ataque Reflexo, Golpe de Raspão) — a PM-costed reactive use in
  response to something that just happened is a separate activation
  moment, not a roll-riding modifier. `active` vs. `roll_active`: whether
  an active power resolves instantly or persists is `duration`'s job, not
  `usability`'s (Medicina resolves instantly, Percepção Temporal
  persists). `roleplay` differs from `passive` in that it's a chosen
  action whose resolution never touches the app at all (no `effects`, no
  meaningful `pm_cost`/`duration`) — `passive` is a constant background
  fact even with zero numeric effect. `vessel` (added 2026-09-08 for
  Escaramuça/Escaramuça Superior/Espreitar) is the pickable power itself
  when its real behavior needs more than one usability at once — split
  into separate `power_granted` children instead, each with its own real
  usability, granted automatically alongside the vessel (see
  `resolve-granted-power-ids.ts`). A vessel still ends up in
  `character_active_effects` when picked (a level-slot pick's row is
  inserted by the backend unconditionally, so this can't be avoided
  frontend-only — see the session notes) but is filtered out of every
  Poderes display list, since it carries no effect of its own to show.
- `action_cost`: see `t20-rules-summary.md` for the actual ação
  padrão/movimento/completa/extra/livre rules. `none` covers
  `passive`/`roll_active` — neither costs a separate action.
- `duration`: only set on `active` powers. Nothing auto-expires — the
  player turns it off manually, permanently (no combat engine planned to
  take this over — see `combat-engine-plans.md`).

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
  { "type": "power", "power_ids_any": [6, 7, 8] },
  { "type": "class", "class_ids": [1], "min_level": 2 },
  { "type": "skill_trained", "skill_id": 3 },
  { "type": "god", "god_ids": [1] },
  { "type": "character_level", "min": 5 },
  { "type": "race", "race_ids": [1] }
]
```

`power`/`class`/`skill_trained`/`god`/`race` reference their target by id.
`skill_trained` is deliberately not just `skill` — it only ever checks "is
the character trained in this skill," never a numeric bonus threshold; a
future power needing the latter gets its own distinct type instead of
overloading this one. `class`, `race`, and `god` all hold a list (any one
qualifies) — `race` gates a `race_granted` typed power's auto-grant (see
character-draft.ts's grantedPowerIds, never player-picked) the same way
`class` gates a `class` typed power's step 9 level-up pool entry, and `god`
is how a `divine_granted` power ties to one or more deities (e.g. a power
granted by two different gods just lists both ids) — **gods don't have a
`grants` column** (removed 2026-08-31): a god only ever granted powers, and
powers already have a prerequisite system, so a `gods.grants` list was
redundant with just putting `{type: 'god', god_ids}` on the power itself.
That also makes it reusable at every future level-up, not just a one-time
grant step — `origins.grants` stays, since origins also grant skills/items,
which have no prerequisite system to piggyback on.

`character_level` gates on the character's total level (summed across every
class — `orderedClassIds`/`totalLevel` on the frontend draft), not one
class's own relative level the way `class`'s `min_level` is. First used for
Aumentar Atributo's 4 patamar-gated tiers per attribute (ids 46-69):
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
{ "tag": "mod_max_pm", "op": "add_per_level", "value": 1, "per_character_level": 2 }
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

## Spell-granting tags: grant_spell vs. grant_or_reduce_spell_pm_cost_by_1

Both live on a Power's own `effects`, both have `{tag, op: 'grant', spell_id}`
shape, and both look interchangeable at a glance — they aren't. This is a
backend PRE-COMPUTE pipeline, not something read live off active_effects
the way every other tag is (mod_cd, mod_def, ...) — that's the part that's
easy to miss.

- `grant_spell` — the spell is genuinely, permanently known. Merged straight
  into a `character_levels` row's `spell_ids` (indistinguishable from a
  normally-picked spell). Never re-pickable afterward. Resolved by
  `Power::grantedSpellIds()`.
- `grant_or_reduce_spell_pm_cost_by_1` — the spell is castable but NOT
  "really" known yet. Merged into that same row's `other_source_spell_ids`
  instead (a SEPARATE column) — staying out of `spell_ids` is the entire
  point, since it's what still lets the player pick the spell for real
  later. When they do, both columns end up holding the id at once, and
  that's the specific state (`isDoubleKnown` in spell-casting-modal.ts)
  that triggers the -1 PM discount. Resolved by
  `Power::grantedOtherSourceSpellIds()`.

Both resolvers only ever get CALLED from three places, all of which write
onto a `character_levels` row:
- `CharacterController::store()`'s `levels` loop (character creation, a
  level-picked power).
- `CharacterLevelController::store()` (level-up, same shape).
- `ManagesPowers::grantPower()` / `CharacterController::store()`'s
  `power_ids` loop, for a power with NO level row of its own (race/origin/
  complication/general — e.g. Amiga das Plantas, a Dahllan race power).
  There's nothing natural to attach `other_source_spell_ids` to here, so it
  lands on the character's own FIRST level instead — an arbitrary but
  harmless landing spot (it only affects which class the spell nominally
  inherits its PM-limit/CD-attribute from via resolveSpellCasterInfo.ts,
  not whether the grant/discount itself works). `ManagesPowers::
  revokeSinglePower()` reverses this by scanning every level row for the
  id and stripping it back out, since revoke doesn't know in advance which
  row grant landed it on.

If a new power needs one of these tags and doesn't go through any of the
three paths above, it will silently do nothing — always trace which of the
three actually runs for that power's own grant path before assuming this
"just works."

## Power double-grant: other_sources_state + on_other_sources_satisfied

The power equivalent of the spell pipeline above — for a power whose own
text says "if you gain this again, you also get X" (e.g. Dahllan's Empatia
Selvagem: "+2 Adestramento se receber de novo"). ONE canonical Power row,
not a separate copy per granting source — same "one shared row, multiple
prerequisites point at it" pattern as Visão no Escuro, not a per-source
duplicate.

- The power's own `effects` array holds BOTH its always-on effects AND the
  bonus effect(s), permanently, from the moment it's seeded — nothing is
  ever added to this array at grant time. The bonus effect(s) are
  distinguished only by carrying `trigger: 'on_other_sources_satisfied'`.
- `character_active_effects.other_sources_state` (nullable enum: `open` |
  `satisfied`) is the per-CHARACTER fact that gates whether those
  trigger-tagged effects actually count. Stays null forever for any power
  that doesn't have this mechanic at all — only ever touched for a power
  whose effects actually carry the trigger.
- `ManagesPowers::grantPower()` owns the whole lifecycle: first grant of a
  power with the trigger → row created with `other_sources_state: 'open'`.
  A second grant attempt (different source, different prerequisite path,
  same power id) finds the existing row and flips it to `'satisfied'`
  instead of no-op'ing. A third+ attempt still finds `'satisfied'` and does
  nothing further — there's no count, just "has a second source ever
  happened, yes/no."
- `getActiveEffects.ts` only includes an effect carrying
  `trigger: 'on_other_sources_satisfied'` when its own row's
  `other_sources_state === 'satisfied'`.
- `resolveAvailablePowers` (available-power-picks-solver.ts) needs a THIRD
  case beyond the usual granted/repeatable split: a power whose row is
  `'open'` must still show up in pickers (that's how the second grant can
  happen at all) — `'satisfied'` (or null, i.e. no mechanic) goes back to
  the ordinary "already granted → hidden" rule. Two different consumers
  (the picker, and getActiveEffects) end up grouping the 3 states
  differently — picker: open vs. {null, satisfied}; getActiveEffects:
  satisfied vs. {null, open} — which is exactly why this needs 3 states
  and not a plain boolean, even though each consumer only ever sees a
  binary outcome from its own side.

Not yet wired into character-creation-powers-step.ts or Adicionar Poder's
own picker (character-main.ts) — no real second-source case exists in the
seeded data yet (Empatia Selvagem's second real grantor, Druida, isn't
built), so those two callers were deliberately left alone rather than
extended against a hypothetical. Revisit once a second real source exists.

## Ofício tool tags: waive_tool_absent_penalty / tool_present

The canonical tag pair the future Ofício-roll resolver itself will read
(e.g. Goblin's Engenhoso).

- Self-report shape is inverted from the ranged-melee pipeline
  (ranged-melee-penalty-resolver.ts): there, checking the box ADDS a
  penalty (default: no penalty). Here, the default IS the penalty — an
  unchecked "Usando Ferramentas" self-report applies `tool_absent_penalty`
  unless the character has `waive_tool_absent_penalty` granted; a checked
  one instead reads every granted `tool_present` effect.
- `waive_tool_absent_penalty` (op `grant`) only cancels the penalty side —
  it can't hide the self-report checkbox the way `nullify_ranged_weapon_
  melee_penalty` hides its own (Mirar/Disparo Preciso), because the SAME
  checkbox still gates the `tool_present` side. A power with only the
  waiver and not the bonus still needs the checkbox to stay interactive.
- `tool_present` (op `add`, `skill_id`, `value`) is the bonus side — e.g.
  Engenhoso's own +2 Ofício.
- Both tags live directly on the granting power's `effects` (e.g.
  Engenhoso, `usability: passive`) — no `trigger` needed on them, since the
  resolver reads them by tag, not by trigger-gating an already-applying
  effect the way `on_other_sources_satisfied` does above.

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

Especialização em Arma (power 83) — "escolha uma arma... pode escolher
este poder outras vezes para armas diferentes" is a player choice at pick
time (which specific weapon), repeatable per weapon. Deliberately NOT
modeled: no per-instance chosen-weapon reference exists on
`character_active_effects` (would need a new nullable `chosen_weapon_id`
column plus loosening the table's `(character_id, power_id)` unique
constraint to `(character_id, power_id, chosen_weapon_id)`). Rejected —
discussed 2026-09-04 — because it doesn't generalize to just this one
power; every future "escolha X, pode escolher de novo para X diferente"
general power (skills, etc.) would want the same treatment, and growing
`character_active_effects`/the powers table with per-choice-type columns
for each one was judged worse than the alternative: since the app has no
way to check *which* weapon a character is currently using against a
stored chosen-weapon reference anyway (self-reported either way), the
choice itself carries zero mechanical weight the app can act on. So the
whole power collapses to one flat, ungated `mod_dmg +2`, self-reported via
`roll_active` each roll ("am I using a weapon I've specialized in right
now?") — one copy of the power ever needed, since multiple copies would be
indistinguishable to the app regardless. This is a *different* kind of gap
than Arqueiro/Destruidor's `requires_weapon_*` fields: those are
eventually auto-resolvable once the damage roll screen exists (real
tracked data — grip/purpose/ability); this one is permanently
self-reported, same category as movement/position (see
`combat-engine-plans.md`'s "No board, no grid"), because the missing piece
(chosen weapon) was a deliberate choice not to build, not a missing
resolver.

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

Damage roll screen — not built (mirrors the attack roll screen's power
checklist, see `roll-screen-attack.md`, but for `mod_dmg`-family tags).
When building it: list checked powers by unique `power_id`, not one row
per effect entry — any power whose `effects` array has multiple entries
sharing a `stack_group` is still one power and must render as one
checkbox, not one per entry.

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
