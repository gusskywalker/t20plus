# T20 Rules Summary

Condensed reference notes on Tormenta 20 rules, kept here so mechanics can be
looked up quickly while building the app instead of re-reading the sourcebook
each time. Organized by topic in sections below — this file will grow as more
rules get covered. Game-term names (skills, powers, action names) are kept in
Portuguese since those are the actual sourcebook terms and get referenced
constantly in powers/skills text; everything else is in English.

## Action Economy

**Round** = ~6 seconds. Turn order follows Iniciativa; a round runs from the
highest-Iniciativa character's turn to the lowest, and "lasts X rounds" is
measured from that same position in the Iniciativa order.

### What you can do in a turn

Pick one of the three combos below, plus any number of ações livres and
reações:

- **Ação padrão + ação de movimento** (you can swap the ação padrão for an
  extra ação de movimento, but never the reverse — never two ações padrão)
- **Two ações de movimento**
- **One ação completa** (gives up both the ação padrão and the ação de
  movimento)

### Ação Padrão

The main thing you do on your turn — attack, cast a spell, use an ability or
magic item, or ready an action for later (a reação under a condition you
define, usable until your next turn), etc.

Specific rules:
- **Shooting into melee**: -5 on a ranged attack if the target (or you) is
  within an enemy's natural reach.
- **Ranged weapon range**: you can shoot up to double the weapon's range, at
  -5.
- **Fintar (feint)**: opposed Enganação vs. the target's Reflexos at short
  range; on success the target is desprevenido against your next attack only,
  until the end of your next turn.

#### Manobras de Combate (replace a melee ação padrão attack)

Maneuver check = melee attack check, opposed by the target creature (it uses
Luta even if it's a ranged attacker). Ties: higher bonus wins; if bonuses are
equal, reroll. Can't maneuver with a ranged weapon.

| Manobra | Effect |
|---|---|
| Agarrar (grapple) | Target becomes desprevenido and immobile, -2 on attacks, can only use light weapons. It can break free with an ação padrão + opposed maneuver check. You need a free unarmed/natural-weapon hand to grapple and it stays occupied while grappling; your movement is halved (dragging the grappled target). You can let go as an ação livre. You can attack a grappled target with your free hand, or swap an attack for another grapple check — on success, deal unarmed/natural-weapon impact damage. Ranged attacks against a grappled target have a 50% chance of hitting the wrong one. |
| Derrubar (trip) | Target falls (normally no damage). Win by 5+: also push the target 1 square; if that pushes them off a ledge, they get a Reflexos CD 20 check to grab on. |
| Desarmar (disarm) | Item drops at the target's feet. Win by 5+: item is also pushed 1 square. |
| Empurrar (push/bull rush) | Push 1.5m, +1.5m per 5 points of margin on the check. You can spend an ação de movimento to follow (up to your speed). |
| Quebrar (sunder) | Hit an item the target is holding (see "Breaking Objects"). |
| Atropelar (overrun) | Ação padrão during a move, advancing through a creature's space. It can yield (no check) or resist (opposed maneuver check — win: it falls and you continue; it wins: it stays up and you stop). Becomes an ação livre if done during an investida. |

### Ação de Movimento

Moving (normal speed, swimming, climbing, riding), standing up, drawing/
sheathing an item, manipulating an item (grabbing something from a pack,
opening a door, throwing a rope), aiming (cancels the -5 Pontaria penalty
against a melee-engaged target this turn).

### Ação Completa

Gives up both the ação padrão and ação de movimento (extra actions/ações
livres/reações are still allowed).

- **Investida (charge)**: move up to 2x speed (min 3m) in a straight line,
  then melee attack. +2 on the attack, -2 Defesa until your next turn. No
  difficult terrain. Can atropelar as an ação livre during an investida (but
  can't atropelar and attack the same target).
- **Golpe de Misericórdia (coup de grâce)**: against an adjacent, helpless
  target — automatic critical hit + chance of instant death (25% for
  PCs/important NPCs, 75% for minor NPCs).
- **Corrida (running)**: see the Atletismo skill.
- **Spells with cast time > 1 ação completa**: costs one ação completa per
  round until finished.

### Ação Livre

Costs almost nothing, but only on your own turn (unlimited per turn, within
reason — the GM can veto anything too complex). Talking (doesn't cover
spells/abilities that require speech), dropping prone (no damage), dropping
an item (dropping without intent is free — throwing to hit something is an
ação padrão, tossing it to someone is an ação de movimento), delaying your
Iniciativa.

**Delaying**: voluntarily lower your Iniciativa until whenever you choose to
act (floor: -10 minus your Iniciativa bonus; once you hit that, you must act
or lose the turn). Among multiple characters delaying: highest Iniciativa
bonus has the edge — decides first when acting on the same count, and acts
last when the order between them is contested.

### Reação

An automatic/reflexive response to something, not a conscious choice made on
your turn — so it can happen even outside your turn or while stunned.
Unlimited, like an ação livre. E.g. a Percepção check to notice something
hidden, a Reflexos check to dodge an explosion, or a readied action triggering
when its condition happens.

### Why this matters for the app

Powers/skills that "cost an action" (e.g. Medicina — an ação completa) are
mechanically different from powers that just modify a roll (e.g. Ataque
Especial, a toggle at attack time). `powers.usability` distinguishes this:
`passive` (always-on) / `toggle` (player switches it on for a specific roll —
whether it costs PM is `pm_cost`'s job, and whether it's a damage-roll or
skill-test toggle is already implied by its `effects`' tags, so `usability`
doesn't need an `active_toggle`/`passive_toggle` split to encode either) /
`action` (spends an action, per `action_cost`).

## How Origins Work

Confirmed against the official sourcebook (cross-checked across 4 independent
sources, all identical): **every origin gives a list of benefícios — some mix
of perícias (skills) and poderes (powers) — and the character picks 2 of them,
in any combination**: 2 skills, 2 powers, or 1 of each. You do not get every
listed benefit for free.

This "pick 2" rule is a general system rule that lives in the rules chapter,
not something repeated in each origin's own text — that's why an origin's
description doesn't say "escolha 2" explicitly, it's just implied by the
system. Acólito's list, for example, has 6 total options (Cura, Religião,
Vontade as skills; Medicina, Membro da Igreja, Vontade de Ferro as powers) —
the player picks 2 of those 6.

Each skill picked this way becomes trained, same as any other trained-skill
source.

### Why this matters for the app

`origins.grants` (and `gods.grants`, same shape) reuses the exact `{picks, options}` shape `classes.skills`
already uses for its own "choose N" slots (e.g. Guerreiro's "2 a sua escolha
entre..."). Every grant an origin makes lives inside one of these choice
groups — even Acólito's 2 starting items, which aren't a real choice (you get
both), but are still modeled as `{picks: 2, options: [2 items]}` rather than
as unconditional entries outside the wrapper. That's deliberate: it keeps one
shape for every group so the frontend always renders the same way (checkboxes
capped at `picks`), instead of a separate code path for "always granted."
Acólito ends up with two separate groups — an "Itens" group (picks 2 of 2,
forced) and a "Perícias e Poderes" group (picks 2 of 6, a real choice) — each
group also carries a `label` for the section heading. Skill/item/power
references inside `options` all use ids (fixed, already-seeded tables),
unlike `prerequisites`' "power" entries which stay name-referenced.

**Naming: `grants` vs `effects`.** `powers`/`accessories`/`armors` use a
column named `effects` because those entities really do carry effects (stat
modifiers, skill bonuses) — they're things a character possesses that affect
rolls. `origins` and `gods` don't have effects of their own; they just hand
out other things (skills, powers, items) which themselves may carry effects.
Hence `grants`, a deliberately different name for a structurally similar but
conceptually distinct column. Don't rename one to match the other — the
distinction is intentional (confirmed 2026-08-30).

## Tormenta Powers & Carisma Loss

`powers.type === 'tormenta'` marks a power as a Poder da Tormenta. In the
sourcebook, taking one of these normally costs the character permanent
Carisma — **not implemented yet** (no resolver, no Carisma-loss tracking on
characters). When it is built, it can use `character_levels` (an event log,
timestamped, one row per level-up) to find, in order, which Tormenta-type
powers a character has taken — "first Tormenta power" is just the earliest
such row.

Some effects modify that future rule, e.g. Aharadak's granted power Afinidade
com a Tormenta: "seu primeiro poder da Tormenta não conta para perda de
Carisma." Rather than hardcoding "if character has power named Afinidade com
a Tormenta" into the future resolver, this is expressed as a normal effects
entry using a new op, `waive`: `{ "tag": "tormenta_power_carisma_loss", "op":
"waive", "value": 1 }` — value = how many Tormenta powers' Carisma loss gets
skipped. The resolver just scans the character's effects for this tag when
granting a Tormenta-type power, generic to any future power that grants a
similar waiver.
