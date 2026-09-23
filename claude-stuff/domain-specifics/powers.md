# Powers

## Where powers are seeded

- All in `t20plus-api/database/seeders/PowerSeeders/`, one file per source: `RaceGrantedPowerSeeder.php`, `RaceOptionalPowerSeeder.php`, `ClassSharedPowerSeeder.php`, `GeneralPowerSeeder.php`, `Class<Name>PowerSeeder.php` (e.g. `ClassGuerreiroPowerSeeder.php`, `ClassArcanistaPowerSeeder.php`).
- Race-granted powers are not grouped by race in the file: ids run sequentially in creation order, new ones are appended at the end with the next free id.
- Race ids live in `RaceSeeder.php`, skill ids in `SkillSeeder.php`.

## Power record shape

- Keys: `id`, `name`, `description`, `source`, `usability`, `icon_file_name`, `prerequisites`, `effects`; optional `action_cost`, `pm_cost`, `applies_when`.
- `source`: `race_granted` for a race's own powers, `power_granted` for a child power reached through a vessel, `specific` for one-offs.
- `usability` values seen: `passive` (always on), `roll_active` (conditional, shows as a checklist row on a roll), `vessel` (only grants children), `item_enhancer`, `roleplay` (no effects).
- Race restriction: `'prerequisites' => [['type' => 'race', 'race_ids' => [21]]]`; one power can list several race ids.
- `effects` are tag entries, see `claude-stuff/t20plus-stuff/tag-library.md`; an unconditional flat skill bonus is one `skill` / `add` entry per skill.
- A power with no modelled effects omits `effects` entirely.

## Vessel + children

- A `vessel` power's `effects` are `power` / `grant` entries pointing at child power ids; each child is its own `power_granted` power with its own `usability`.
- Split into vessel + children only when the pieces need different `usability` values, otherwise keep one flat power.
- `vessel` powers are not shown in character-sheet (not shown to the user). Their power-granted children are.

## Description text

- Rule text is copied verbatim from the book.
- `<br><br>No APP, ...` notes are written by the user only.
