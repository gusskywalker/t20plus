# Ammo

## Weapon compatibility

- `AMMO_COMPATIBLE_WEAPON_IDS` (`attack-modal/attack-power-resolvers/weapon-ammo-solver.ts`) is a hardcoded map of ammo `general_items.id` -> the fired weapon ids it can be drawn for.
- Hardcoded per ammo id because a fired weapon's own fields (`purpose`, `is_firearm`) can't disambiguate its ammo kind — bows and bestas are both `purpose: 'fired'`, `is_firearm: false`, but take different ammo.
- Every new ammo `general_item` needs an entry here listing the weapon ids it serves; every new fired weapon needs its id added to the entry(ies) of whichever ammo it takes.
- `isAmmoCompatibleWithWeapon(ammoGeneralItemId, weaponId)` is the only export, checked when the attack modal offers ammo choices for the selected weapon.

## Slot bundling

- `calculateAmmoSlots` (`shared/helpers/calculators/calculate-ammo-slots`) is a hardcoded per-ammo-id, quantity-bucketed slot rule — NOT the generic `catalog slots × quantity` multiply every other stacked `general_item` uses.
- Same 20/10/1 → 1/0.5/0 bucket table for every ammo id seeded so far, regardless of that ammo's own per-package sale count (e.g. Dardos sells in packs of 10 but still uses the 20/10/1 buckets, same as Flechas/Munição's packs of 20) — the buckets describe the TOTAL owned stack size, not the purchase unit.
- The `general_items` catalog row's own `slots` field is always `1` for ammo (matching the `min: 20` bucket) — a fallback/default, superseded once `calculateAmmoSlots` runs against the actual owned quantity.
- Returns `null` for a `generalItemId` with no table entry — caller falls back to the generic multiply. Returns `0` for `quantity <= 0`.
- Every new bundled ammo `general_item` needs its own entry in `ammoSlotThresholds`, even though every entry so far is identical — no shared/default table, each id is its own explicit list per the file's own convention.
- Ammo sold one by one (Bomba, id 69) has an entry in `ammoSlotsPerUnit` instead: slots = quantity × the per-unit value (0.5), no bucketing. Its catalog `slots` is the same per-unit value.

## Ammo damage
- An ammo `general_item` can carry `effects` with `power grant` entries; the attack modal reads them as `ownEffects` for the picked ammo. A passive granted power's `mod_dmg extra_die` always rolls (Bomba: `Bomba (Explosão 6d6)`, id 14044) and its `ignore_dr` always shows (Flechas/Virotes Pesados: `Munição Pesada`, id 14045, also `mod_hit -2`); its other passive effects fold into `checkedEffects`.

## Purchase
- `AMMO_BUNDLE_SIZES` (`shared/helpers/buy-item/buy-item.ts`) maps a bundled ammo id to the quantity one purchase creates (Flechas 20, Munição 20, Virotes 20, Dardos 10, Pedras 20). The buy modal never shows Quantidade for ammo.
- An ammo id with no entry (Bomba) is bought like an ordinary item: one purchase = one inventory row of quantity 1 at the catalog `cost`. Only potions stack into one row (backend `CharacterInventoryController`).
- Every new bundled ammo needs an entry in `AMMO_BUNDLE_SIZES`.
