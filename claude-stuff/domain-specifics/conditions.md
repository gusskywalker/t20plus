# Conditions

Two separate tables, both named after T20's status conditions, serving different purposes.

## `Condition` (`conditions` table, `ConditionSeeder.php`)

- Plain reference glossary: `id`, `name`, `description`, `type` (`metabolism`/`mental`/`senses`/`movement`/`tired`/`fear`/`null` — flavor only, not read anywhere).
- Served read-only via `GET /conditions` (`ConditionController::index`), never linked to a character.
- Ids 1-29 are the T20 rulebook's own canonical status conditions. A bespoke, weapon/item-specific condition (e.g. Açoite Finntroll's -2) also gets an entry here for consistency, past id 29.

## `Power` with `source: condition_granted` (`ConditionPowerSeeder.php`, ids 7000-7999; ids under that are older conditions that kept their original id)

- The mechanical, grantable half — same shape as any other power (`effects`, tags, `usability`).
- `usability: passive` for every condition so far; `effects` uses ordinary tags (`mod_def`, `mod_hit`, `skill`, `all_skills`, `dodge_chance`, `mod_dmg`, ...) exactly like any other passive power.
- A bespoke weapon/item-inflicted condition (not in the rulebook's own list) gets a power here too, named `<Thing> (Condição)`, with no matching entry expected unless it's also added to the `Condition` glossary.

## Granting/removing on a character

- `AddConditionModal` lists `staticRegistry.powers` filtered to `source: condition_granted`, minus ones the character already has, and grants the picked one via `addCharacterActiveEffect` — an ordinary `character_active_effects` row, `source_inventory_id` null.
- `character-main.ts`'s `activeConditionRows` reads the character's `active_effects` filtered the same way, for the sheet's active-conditions list; `condition_granted` powers are excluded from the ordinary Poderes lists (`isHiddenFromPowersList`).
- `ConditionDetailsModal` removes one via `destroyCharacterActiveEffect`.

## How the effects actually apply

- A granted condition's tags flow through the same generic pipelines as any other passive power's, since `getActiveEffects`/`resolveTag` don't special-case the source: `mod_def` reaches Defesa, `mod_movement` reaches Deslocamento, `skill`/`all_skills`/`skill_group` reach `calculateSkillBonus` (used for every skill roll, including Luta/Pontaria in the attack modal's hit roll — `all_skills` has no combat-skill exclusion, unlike `all_skills_no_combat`).
- The attack modal's `mod_hit`/`mod_dmg` of a passive condition reach the roll through `currentlyActivePowerRows()`, which keeps a power only if it matches the selected weapon's `applies_when` (e.g. `weapon_purpose`). The same power's `skill` effects reach `calculateSkillBonus` without that weapon check.
