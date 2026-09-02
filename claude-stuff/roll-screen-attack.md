# Attack Roll Screen — Design

Two-phase flow (roll to hit, then roll damage if it lands), each phase built
from the same two-step pattern already established in `tag-system.md`'s
"Roll-screen filter" note. Not implemented yet — this is the design to build
against once the frontend gets there.

## Step 1 — Base value (automatic, no player interaction)

Computed once, before the player sees any choices. Two numbers get built
this way: **base hit value** and **base damage value**.

**Base hit value** =
- The attack's governing skill total (Luta for melee, Pontaria for ranged —
  itself already includes the character's attribute, training, and any
  skill-level bonuses baked into that skill's own number)
- **+** every `passive` effect tagged `mod_hit`
- **+** every currently-active `active`-with-`duration` effect tagged
  `mod_hit` (per the "currently active" list — an `active` power the player
  turned on earlier, e.g. Percepção Temporal, folds in here automatically,
  same as passive)

**Base damage value** = weapon's own damage stat + every `passive`/
currently-active-`active` effect tagged `mod_dmg`, same pattern.

Nothing here requires a decision — it's a pure sum, same as the character
sheet already shows a skill's total before any roll-time choices exist.

## Step 2 — Roll-time choices (player picks, before rolling to hit)

Scan the character's `roll_active` and `trigger` powers (see `tag-system.md`
— these two render identically on a manual screen) for any effect tagged
`mod_hit` **or** `mod_dmg`, and show all of them together as checkboxes —
not split into a separate "hit choices" list and "damage choices" list.

Why together: some powers (Ataque Especial) resolve as one decision that
affects both the upcoming hit roll and the upcoming damage roll at once
(spend PM once, the split between hit/dmg is chosen at that moment — still
a deferred special case per the powers migration, but whatever UI handles
it needs to appear at this single decision point, not twice). Showing every
relevant `roll_active`/`trigger` up front, regardless of which tag it
touches, means the player makes one attack-level decision, and both the hit
calculation and (if it lands) the damage calculation read from the same
selections — no re-prompting at the damage step.

**Costs are paid here, before the outcome is known — this must happen
before Step 3, not after.** Most `roll_active` powers with a `pm_cost` (or
other tradeoff, like Ataque Poderoso's -2 to hit) have to be committed to
before rolling to hit at all, since the whole point is deciding whether the
risk is worth it without knowing the result yet. That means a player can
select Ataque Especial, pay the PM, and still miss the attack — the cost
isn't refunded on a miss, because it was never contingent on hitting in the
first place. Step 2 has to fully resolve (selections locked, costs paid)
before Step 3's roll happens.

## Step 3 — Roll to hit

`d20 + base hit value + sum of selected checkboxes' mod_hit contributions`,
compared against the target's Defesa. Pass or fail.

## Step 4 — Roll damage (only if Step 3 passed)

`weapon damage die + base damage value + sum of the *same already-selected*
checkboxes' mod_dmg contributions` — no new prompt, reuses whatever the
player picked in Step 2.

## Open items (not solved here, just noted)

- Ataque Especial/Ataque Poderoso-style split-choice powers still need
  their own bespoke UI within Step 2's checkbox list (see the "organized
  edge case handling" / registry idea discussed in this session) — the flow
  above assumes a simple checkbox per option, which doesn't cover "player
  types in how much of the bonus goes where."
- No `trigger`/`roll_active` power currently seeded touches `mod_dmg` alone
  (only Ataque Especial would, once its special case is built), so Step 2's
  "both tags together" behavior hasn't been exercised against real data yet
  beyond the deferred case.
