# Attack modal

`t20plus-frontend/src/app/shared/modals/attack-modal/attack-modal.ts` (+ `attack-power-resolvers/`). Self-reported combat: the app rolls the d20 and the damage, the player declares which powers count, nothing is applied to a target.

## Steps
1. Pick a hand (`selectHand`) — a worn weapon, or unarmed (`Desarmado`, id 4) when empty. Resets checked powers, crit flag, Ataque Especial, dual-wield checkbox.
2. Fired weapons only: pick an ammo stack (`ammoRows` — general_items of type `ammo` whose id maps to this weapon in `weapon-ammo-solver.ts`). Melee/thrown skip to step 3.
3. Power checklist (`attackPowerRows`), Ataque Especial dropdown, dual-wield checkbox, then Rolar.
4. `roll()`: spends PM (`checkedPmCost`) and ammo (`spendAmmo`) up front, rolls d20 (two carousels + best-of when `hasAdvantage()`), builds the hit breakdown. Falhou = Cancelar; Passou = `markPassed()`.
5. `markPassed()`: rolls damage, builds the damage breakdown, decrements item-enhancer uses.

## Skill and hit total
- Skill is Luta (19) for `purpose: melee`, Pontaria (25) for thrown/fired (`meleeSkillId`/`rangedSkillId`). Nothing swaps it to another skill.
- `skillBonus` = `calculateSkillBonus` — reads `getActiveEffects` (passive + toggled-on rows only), so `skill`/`all_skills`/`skill_group` effects on passives and conditions reach the hit automatically.
- `calculateHit` = d20 + skillBonus + `resolveTag(checkedEffects, 'mod_hit')`. Plus `espreitarBonus` and `attributeSwapBonus` added on top in `roll()`.
- `attributeSwapBonus`: a checked roll_active `skill_attribute override` matching Luta/Pontaria adds (new attribute bonus − the skill's current key attribute bonus). Same math as skill-roll-modal.
- A power carrying both a Luta/Pontaria/`all_skills` bonus and a `mod_hit` double counts.

## checkedEffects pools
Built separately in three places, each `[...checked power rows' effects, ataqueEspecial, weapon-granted, ammo-granted, bespoke resolvers]`:
- margin (`currentMargin`), hit (`roll`), damage (`markPassed`). Keep them in sync when adding a source.
- `checkedPowerRows` = checked `attackPowerRows()` + `currentlyActivePowerRows()` (+ item-enhancer `other_effects_power_ids` rows in `markPassed`).
- `hasAdvantage()` has its own pool and also reads every `activeEffects()` since advantage is a state, not a bonus.

## Where powers come from
- `attackPowerRows()`: every `roll_active` row from `getRollActivePowers`, no per-roll persistence of the checked state (`checkedPowerIds`).
  - A row whose `source_inventory_id` is a weapon shows only for the selected weapon, with no tag or `applies_when` filter.
  - Every other row must contain an `attackTags` tag (`mod_hit`, `mod_dmg`, `mod_margin`, `doubles_marca_da_presa_dice`, `reroll_dice_below`, `extra_die_on_max`, `mod_natural_weapon_pm_cost`) and pass `matchesReqs` (`applies_when` incl. `power_id`, `weapon_any`, grip/purpose/ability/ids). `weapon_step_increase` alone is not an attack tag, so a non-weapon roll_active power carrying only it never shows.
  - Also appends `golpePessoalRows()` and `ammoRollActiveRows()`.
- `currentlyActivePowerRows()`: every `is_active` effect group (passive powers, toggled 'active' powers, spell buffs) that contains an attack tag, with no checkbox and no `usability` gate. This is how a passive condition's `mod_hit` reaches the hit roll.
- `selectedWeaponGrantedEffects()`: effects of the selected weapon's active powers (`activePowersOfItem`) + item-enhancer powers. `activeEffects()` drops weapon-sourced effects so a weapon in the other hand never leaks.
- `selectedAmmoGrantedEffects()`: the picked ammo's non-roll_active powers — its own `effects` grants (`general_items.effects`) plus its melhorias'/encantos'. Its roll_active ones are checklist rows (`ammoRollActiveRows`).
- A passive ammo power's `extra_die` is rolled too, and its `ignore_dr` shows in the Ignorar RD lines: `ammoPassiveRows()` is merged into `damageRows` (used by the extra-die pipeline and the `ignore_dr` lines only), not into `checkedPowerRows`, so its other effects aren't counted twice. Its `mod_hit`/`mod_dmg` reach the totals through `selectedAmmoGrantedEffects()` and show as one "<ammo name> ±N" line via `itemGrantedLines`.
- Bespoke resolvers in `attack-power-resolvers/` (Mira Apurada 266, Tiro de Abate 254, Armas da Ambição 277, Ponto Fraco, Espreitar, Marca da Presa, dual-wield 77/259, ranged-melee penalty 262, Tradição de Ayrelynn) are merged in separately; `bespokeResolvedPowerIds` keeps them out of the generic pass.

## Damage
- Weapon die: `calculateWeaponDice(weapon, checkedEffects, size)` (steps from `weapon_step_increase`, `all_die_step_increase`, size), then on a crit `multipliedDiceNotation` × `calculateMultiplier` — the multiplier scales the weapon's own dice only.
- `mod_dmg` flat/`add` values are never multiplied. `extra_die` entries (fixed notation, `weapon_die`, `die_steps_per_levels`) are never multiplied either; entries sharing a `stack_group` keep the biggest step.
- `damageRows` = `checkedPowerRows` plus `ammoPassiveRows()` (the picked ammo's passive powers, e.g. Bomba's 6d6, Munição Pesada's Ignorar 5 RD). Passive weapon-granted powers are not in that list, so a weapon's `extra_die`/`ignore_dr` must be on a roll_active power.
- Damage attribute: `mod_dmg_attribute` (default str for melee/thrown, none for fired).
- Breakdown extras (display only): `ignore_dr` lines, push distance, `condition`+`inflict` "Causou X" lines, `remove_all_damage` (Rede) hides damage lines.

## Triggers
- `on_hit_success`: `condition` + `inflict` reads to a "Causou X" line after a hit.
- `on_critical_strike`: `critGateOpen(effect, critical)` drops the effect unless the roll is a crit. Always dropped from the margin and hit pools (no circularity with the crit check); kept in the damage pool and the `extra_die` reader only when `isCriticalStrike()`.

## Ammo
- `AMMO_COMPATIBLE_WEAPON_IDS` in `weapon-ammo-solver.ts`: ammo `general_items.id` → compatible weapon ids, hardcoded. Every new fired weapon using an existing ammo gets added here.
- Slot bundling lives in `calculate-ammo-slots.ts` (`ammoSlotThresholds`, per ammo id, 20→1 / 10→0.5 / 1→0); see `ammo.md`.

## Item-granted powers
See `item-granted-powers.md` for how weapon-sourced rows are scoped to the selected weapon.
