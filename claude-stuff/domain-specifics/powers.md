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

## Reading a character's effects

Three separate readers, each with a different scope — never conflate them:

- `getActiveEffects` (`shared/helpers/get-active-effects`) — flattens every `is_active: true` row (passive powers, always; `active` powers, once toggled on) into one `Effect[]`. Also folds in the non-`roll_active` effects of any active spell buff (`character_active_spell_effects`).
- `getRollActivePowers` (`shared/helpers/get-roll-active-powers`) — every `usability: 'roll_active'` power's row, `is_active` or not (these rows are never toggled), for a roll's own checklist. Also adds one synthetic row per active spell buff, carrying just that buff's `roll_active`-usability effects.
- `usability: 'spell_enhancement'` powers — read neither of the above. `spell-casting-modal.ts`'s own local `matchingSpellEnhancementPowers` filters the character's granted powers (`active_effects`, any row) to this usability, gated by `applies_when` against the spell being cast, and folds them in as extra checkable rows alongside the spell's own book `enhancements`. Not a shared/exported helper.

## Description text

- Rule text is copied verbatim from the book.
- `<br><br>No APP, ...` notes are written by the user only.

## Item enhancer powers
- A `usability: 'item_enhancer'` power is applied to one item instance, not toggled for the character.
- `item-details-modal` lists every such power the character has (any `source`, replaced ones skipped) whose `applies_when.categories` includes the item's kind, as one button each.
- Pressing it spends the power's `pm_cost` and writes `{ power_id, remaining_uses? }` into the item's `other_effects_power_ids`; `remaining_uses` is copied from the power's `remaining_uses` effect when it has one.
- Pressing it again (`Remover <name>`) drops the entry, with no PM refund.
- The attack modal adds the applied entries' effects to attacks with that item and counts `remaining_uses` down on a landed hit; the entry is removed at 0.
- Examples: Natureza Venenosa, the Elemental de Veneno poison powers (`mod_dmg` `extra_die` poison, `remaining_uses` 1).
