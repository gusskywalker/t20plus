# Weapon Rules

Raw reference notes on T20 weapon mechanics, kept here so the future
`weapons` catalog table can be designed against real source text instead of
guessed. Not a schema proposal — just the rules, condensed but accurate.
Game terms stay in Portuguese (sourcebook terms), same convention as
`t20-rules-summary.md`.

## Classification

Every weapon is classified along three independent axes:

- **Proficiência** — `simples` / `marciais` / `exóticas` / `de fogo`.
  Everyone knows `simples`. `marciais` known by specific classes (bárbaro,
  bardo, bucaneiro, caçador, cavaleiro, guerreiro, nobre, paladino).
  `exóticas`/`de fogo` need specific training. Attacking with a weapon
  you're not proficient with: **-5 on the attack test**. Every creature is
  proficient with unarmed attacks and natural weapons.
- **Propósito** — `corpo a corpo` (melee, tests Luta, adds Força to damage)
  or `à distância` (ranged, tests Pontaria), the latter split further:
  - `arremesso` (thrown, e.g. adaga/azagaia) — drawing is an ação de
    movimento, adds Força to damage.
  - `disparo` (fired, e.g. bow) — drawing ammo is an ação livre, reloading
    needs both hands, **no attribute added to damage**.
- **Empunhadura** — `leve` (one hand, benefits from Acuidade com Arma) /
  `uma mão` (one hand, other hand free) / `duas mãos` (both hands; freeing
  one hand is ação livre, re-gripping is ação de movimento, or livre if the
  weapon allows drawing that way).

## Weapon characteristics

- **Preço** — includes basic accessories (sheaths, quivers).
- **Dano** — table value is for Pequenas/Médias creatures; roll it +
  modifiers on a hit, subtract from target's PV.
- **Crítico** — natural 20 = critical, ×2 damage dice (numeric bonuses and
  extra dice like Ataque Furtivo are NOT multiplied, e.g. 1d8+3 → 2d8+3).
  Some weapons have a wider threat margin or higher multiplier:
  - `19` = threat on 19-20. `18` = threat on 18-20.
  - `x2`/`x3`/`x4` = damage multiplier on crit.
  - `19/x3` = threat 19-20, triple damage.
  - Effects that widen the margin lower the number needed; effects that
    raise the multiplier add to it.
- **Alcance** — `curto` (9m/6 squares), `médio` (30m/20 squares), `longo`
  (90m/60 squares). Attacking within range: no penalty. Up to double range:
  -5. Weapons with no range can be thrown at curto range at -5.
- **Tipo** — damage type: `corte` (C) / `impacto` (I) / `perfuração` (P).
  Some creatures resist/are immune to specific types.
- **Espaço** — how many inventory slots the weapon takes (carry capacity).

## Weapon abilities (habilidades)

Weapons may carry one or more of these (shown in italics in sourcebook
text):

| Ability | Effect |
|---|---|
| Adaptável | a one-hand weapon with this can be used two-handed for +1 damage step |
| Ágil | usable with Acuidade com Arma even if not `leve` |
| Alongada | doubles the wielder's natural reach, but can't hit an adjacent target |
| Desbalanceada | -2 on attack tests |
| Dupla | usable with Estilo de Duas Armas as if it were a one-hand + light weapon; each "end" counts as a separate weapon for melhorias/encantos |
| Híbrida (AA) | multiple modes of use, only that mode's traits/effects apply while in use; switching mode is ação de movimento (or livre with Saque Rápido); melhorias/encantos cost double tibares |
| Ocultável (DH) | +5 Ladinagem to conceal it (e.g. adaga) |
| Surpreendente (DH) | once per scene, drawing as ação livre + attacking same turn makes the target desprevenido against that attack |
| Versátil | bonus to one or more maneuvers, cumulative with other item bonuses (varies per weapon) |

**Optional rule (Armas Leves e Ágeis)**: Destreza instead of Força for melee
attack tests and damage with `leve`/`ágil`/thrown weapons; ignores Acuidade
com Arma prerequisites if used.

## Damage steps

Some effects raise/lower weapon damage by one or more "steps" (e.g. Grande
creatures using ampliadas weapons deal +1 step). One single ascending
progression — alternates in the same step (comma/"ou") are interchangeable,
same step, both directions:

1 -> 1d2 -> 1d3 -> 1d4 -> 1d6 -> 1d8 ou 2d4 -> 1d10 -> 1d12, 2d6 ou 3d4 -> 3d6 -> 4d6 -> 4d8 -> 4d10 -> 4d12 (máximo)

## Unarmed & natural weapons

**Ataque desarmado** — treated as a light melee weapon, non-lethal impact
damage (1d3 for Pequenas/Médias), unaffected by effects targeting
"objects"/"wielded weapons" specifically. Each creature has exactly one
(but can choose which body part delivers it each time).

**Armas naturais** (chifres, garras, mordida, etc.) — also treated as light
melee weapons, same immunity to object/wielded-weapon-specific effects
(can't be disarmed/broken). Damage amount/type is per-creature, in its own
description.

## Not yet designed

This is source text only — no `weapons` table exists yet, and
`character_inventory.item_type` doesn't have a `weapon` value. See
`tag-system.md`'s "Parked" section.
