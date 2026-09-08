# matchesReqs pipeline

One function, `matchesReqs(power, weapon)` in attack-modal.ts (reads `power.applies_when`), called from two places:

1. `attackPowerRows()` — roll_active powers. Decides which checkboxes show in step 3's list.
2. `currentlyActivePowerRows()` — active(toggled)/passive powers. Decides which auto-apply, no checkbox.

Both feed the same `checkedPowerRows` → `checkedEffects` pool, used by both `roll()` (hit) and `markPassed()` (damage). So one check, same result on both screens.

For roll_active: gates visibility (hide/show a checkbox).
For passive: gates application (counts or not) — nothing is shown/hidden, there's no checkbox.
