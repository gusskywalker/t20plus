# Item-granted powers

An item's powers are rows in `character_active_effects` with `source_inventory_id` set (null = the character's own power).

## Rows
- Unique key: `(character_id, power_id, source_key)`; `source_key` is a virtual `COALESCE(source_inventory_id, 0)`. Virtual, not stored: MySQL rejects a cascade FK on the base column of a stored generated column.
- `source_inventory_id` is a nullable FK to `character_inventory`, cascade on delete.
- The same power from two items is two rows (two daggers with Certeira).

## Sync (`ManagesPowers::syncItemPowers`)
- Recomputes what the inventory grants and diffs it against the item rows: grants missing, revokes stale, never touches existing rows (keeps `is_active`/`is_favorite`).
- What counts: armor/shield/accessory/weapon while `worn` (a weapon is `worn` while in a hand), a general item while owned.
- Never granted by the sync: consumables (a row is inserted when one is used) and ammo (the attack modal reads the picked ammo's powers from the catalog when it's fired).
- Granted powers = item `effects` + its improvements' + enchantments' (`tag: power, op: grant`, `when_category`/`when_type` narrow it), plus every descendant power those grant.
- `is_active` = the power is passive. Active/roll_active rows start off.
- Item rows are written only by `grantItemPower`/`revokeItemPower` (id required). `grantPower`/`revokePower` only touch own rows (`whereNull('source_inventory_id')`). Item rows can't be removed through the API (422).
- Called after every change to the inventory, a hand, an accessory slot, or an item's improvements/enchantments, and at the end of character creation. Those endpoints return `active_effects` and the frontend patches it.

## Readers
- `getActiveEffects` reads rows only. The same power in several rows counts once; when no row is the character's own, the effect carries `source_inventory_ids`.
- `getRollActivePowers` returns the roll_active rows plus a synthetic row per roll_active spell buff. Modals filter it (skill match, `applies_when`, source). A roll_active power of a worn weapon shows on skill rolls while the weapon is in hand.
- Attack modal: the selected weapon's powers are the active rows whose `source_inventory_id` is that weapon (`activePowersOfItem`; its roll_active ones come from `getRollActivePowers`, for the selected weapon only). It drops weapon-sourced rows and effects from the shared readers (`activeEffects()`, `weaponSourcedInventoryIds()`) so a weapon in the other hand never leaks in. The picked ammo's powers come from the catalog (`getItemGrantedPowers`, category `weapon`, type `ammo`).

## Seeding rules
- An item's `effects` (weapon, armor, shield, accessory, general item) and an improvement's/enchantment's only ever hold `tag: power, op: grant` entries. A direct tag there is never read: write the effect as an `item_granted` power (ids 14000-14999, `ItemGrantedPowerSeeder`) and grant it.
- Example: a heavy armor grants a passive power carrying `heavy_armor_movement_penalty`; `calculateMovement` reads that tag from the active effects and applies -3m unless `waive_heavy_armor_movement_penalty` is present.
- A power on a weapon that should apply only to that weapon's attacks is scoped by the attack modal through the row's source; no marker on the power is needed.

## Rules for consumers
- Prerequisite checks and spell-option `granted` sets skip item rows (`source_inventory_id == null`). `applies_when.power_id` gates don't: the character has the power.
- Item rows appear in Poderes lists; the power modal hides Remover for them; Adicionar Poder hides `item_granted` powers.
- The creation draft has no equipped items, so it never has item rows; gear is equipped after creation (init-new-character), each equip runs the sync.
