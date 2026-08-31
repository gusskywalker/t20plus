# Combat Engine — Rough Ideation

Not designed, not implemented — this is a starting shape to build against
later, capturing the direction from planning discussions so it isn't lost.
Everything here is a rough idea, not a commitment.

## No board, no grid

Deliberately skipping spatial/graphical combat entirely — too much work for
what it buys. No positions, no movement tracking, no range/area-of-effect
geometry.

**Consequence: movement/positioning-conditional powers stay permanently
self-reported.** No new mechanism for this — they use the same existing
categories everything else does (`roll_toggle` for a per-roll "+X if you
moved this turn"-style bonus, `active` + `duration` for a persistent
movement-based stance). The only difference from other `roll_toggle`/
`trigger` powers is
that these specific ones never graduate to automatic verification, because
the underlying fact (position) is never tracked — same trust model the
manual roll screen already uses for everything today, just permanent for
this one category instead of temporary. Everything else below *does* get
real verification once the engine tracks the relevant event.

## Screen shape

A turn/initiative-order list. Each combat participant — player character or
NPC/enemy, no distinction in how they're handled — gets a card, shown
top-down in initiative order.

Click a card → action menu:
- **Attack** → the flow in `roll-screen-attack.md`.
- **Cast Spell** → pick a spell, then select target(s). Spells need their
  own metadata for this (single-target vs. multi-target, at minimum) — not
  designed yet, noted as a gap in `tag-system.md`'s "Parked" section
  already applies here too.

## The actual point: trigger_on gets verified for real

Every combat action the engine resolves — an attack landing, a spell being
cast, a save being rolled — is a **structured event**, not just a
pass/fail outcome. At minimum an event needs: who acted, what they did
(attack / spell), the relevant category (e.g. spell school — arcana/divina/
universal), who was targeted, and the result (hit/miss, save success/fail
and which save type).

Whenever an event affects a participant, the engine scans **that
participant's** own powers for a matching `trigger_on` condition and fires
(or offers, for `roll_toggle`) automatically — no player self-report
needed, unlike today's manual roll screen. This applies identically to
player characters and NPCs/enemies; there's no special-casing by side, same
card mechanism either way.

Concrete examples (both directions, to make sure the mechanism is
side-agnostic):
- An **enemy** is targeted by a divine spell, and that enemy has Rejeição
  Divina → the engine automatically counts it for that enemy's resistance
  roll. Nobody had to remember it or toggle it.
- A **player character** is targeted by a divine spell, and that character
  has Rejeição Divina → same automatic counting, same mechanism, just the
  other direction.

This is the actual payoff of the `trigger_on` naming work already done in
`tag-system.md` (general-category-first, narrowing qualifiers, e.g.
`targets_you_spell_divine`, `enemy_fails_save_vontade`) — those strings were
built to be matched against real structured event data, not just to read
nicely for a human filtering a checklist by hand.

## Open items

- What exactly counts as a "combat event" and what fields it needs isn't
  designed — the examples above (spell school, target, save type/result)
  are the minimum implied by what's already been seeded, not a full list.
- Spell targeting metadata (single/multi-target and whatever else "select
  targets" needs) — not designed.
- How `roll_toggle` powers get offered mid-engine (still needs player
  confirmation, per `tag-system.md`) vs. `trigger` firing with zero
  confirmation — the mechanism exists conceptually, not built.
