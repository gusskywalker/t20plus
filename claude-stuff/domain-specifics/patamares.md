# Patamares

## What they are

- Tiers of character level: patamar 1 (níveis 1-4), patamar 2 (5-10), patamar 3 (11-16), patamar 4 (17-20).
- A new patamar begins at level 5, 11 and 17 — confirmed by Coração Heroico's own text: "Quando atinge um novo patamar (no 5°, 11° e 17° níveis), recebe +3 PM." (`OriginGrantedPowerSeeder.php`).

## Where the boundary levels live

- `t20plus-frontend/src/app/shared/helpers/scale-level-effect/scale-level-effect.ts` exports `PATAMAR_LEVELS = [5, 11, 17]` — the only constant, reuse it, never hardcode 5/11/17 elsewhere.

## Modeling a "X per patamar" effect

- Convention seen across every existing case (Herança de Vitália, Arsenal de Alihanna, Estirpe Arcana, Coração Heroico): a flat `op: 'add'` effect for patamar 1's base amount, PLUS an `op: 'add_per_patamar'` effect with the SAME value — the per-patamar effect scales by how many of `PATAMAR_LEVELS` the character's level has passed (0 at patamar 1, up to 3 at patamar 4), so total = value × current patamar number.
- `scaleLevelEffect(effect, characterLevel)` (same file) turns `add_per_patamar` into a flat `add` at read time; every consumer that wants patamar-scaled effects to resolve must run effects through it first (e.g. `power-details-modal.ts`'s PM-restore branch already does; the PV-restore branch does not, as of writing).
