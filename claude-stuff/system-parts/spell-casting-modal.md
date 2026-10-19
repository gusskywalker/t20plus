# Spell Casting Modal

`spell-casting-modal.ts`, plus `calculate-spell-cd.ts` for the CD.

## applies_when matching
- Every passive power's `applies_when` is checked with `matchesSpellAppliesWhen` against a context built from the cast spell: `school`, `extraSchools`, `type`, `damageType`, `circle`, `spellId`, plus `resistance`, `doubleKnown`, `grantedByPowerId` and `casterMaxCircle` where the consumer needs them.
- A key absent on the power never restricts; a key present must match.
- `spell_ids` matches `spellId`; `spell_circles` matches the spell's own círculo.

## PM cost
- The base cost is the spell's own `pm_cost` when it has one (nullable column on `spells`), otherwise the círculo table (1, 3, 6, 10, 15 PM for círculos 1 to 5).
- `spellPmCostAdjustment` collects the character's granted passive `mod_spell_pm_cost` effects, skipping replaced powers (`replaces_power`).
- Ordinary reductions pool with Pakk's double-known −1 and Herança Aprimorada; only the biggest applies.
- An effect flagged `cumulative_with_other_pm_cost_reductions` applies on top of the pool.
- Positive values always sum.
- The final cost is floored at 1.

## CD
- `calculateSpellCd` = 10 + half the character level + the key attribute bonus + the sum of matching granted passive `mod_cd` effects.
- Replaced powers are skipped; `applies_when` matches on school, resistance, damage type, double-known, granting power, `spellId` and `circle`.

## Damage
- A passive `mod_spell_dmg` `extra_die` with value `spell_die` rolls dice of the spell's base die size, one per effect, and shows as a line named after the power, e.g. `Dado Extra (1d6) +3`.
- Replaced powers are skipped.

## Companions
- A `companion` `grant` on a checked `spell_enhancement` creates its rows at cast time: `count` plus `count_bonus` (`spell_circle` adds the spell's círculo), posted in one request.
- A `companion` `grant` on a power the character merely owns creates nothing from this modal.

## Pages
- Page 1 is the description; page 2 is the enhancement picks plus Lançar Magia; page 3 is either the rolling wait or the ally picker; page 4 is the result.
- Cancelar on page 1 closes the modal; on page 2 it becomes Voltar to page 1; page 4's bottom button is Fechar.
- Pressing Lançar Magia on page 2 spends the PM and then branches on the effective usability (a checked `change_usability` enhancement overrides the spell's own).
- `buff` with `allies` in its affects goes to page 3's picker; `utility`, `summon` and any other `buff` resolve straight to page 4.
- `damage` and `debuff` swap the button row to Passou/Falhou. Passou means the target failed its resistance (`on_spell_success`); Falhou means it resisted (`on_spell_fail`).

## PM limit
- The limit is the lower of the taught class's level and current PM plus temp PM, plus the Sangue Mágico bonus when that enhancement is checked.
- `ignore_pm_limit` on the spell's effects drops the class cap and leaves only current PM.
- Lançar Magia is disabled on page 2 while the cost exceeds the limit.

## Enhancements
- `castEnhancements` is the spell's own enhancements followed by every matching `spell_enhancement` general power translated into the same shape, so both share one index space.
- A power matches by `applies_when` (`spell_action_costs`, `spell_schools`, `spell_ids` and the rest); Magia Ampliada and Raio Dividido have their own resolvers.
- A repeatable enhancement shows its checked rows plus one empty row.
- A row is disabled when it would exceed the PM limit, shares a checked `unique_change_group`, misses its `requires_enhancement_index`, passes `max_stacks_by_max_circle`, needs another god, or a truque is checked. Everything is locked once cast.
- Checking a truque clears every other pick; unchecking an enhancement clears the ones that require it.
- A double-known spell adds an informational, disabled −1 PM row naming the power that granted it.

## Result
- Passive `restore_pm` on `on_spell_success` with value `spell_circle` restores the spell's círculo in PM, capped by the PM spent on the cast.
- The result breakdown is a list of text lines with an optional color, built in `resolveCast`.
