# Weapon Abilities

Modeling decisions for the 9 seeded `weapon_abilities` (`WeaponAbilitySeeder.php`, ids 1-9).

## Adaptável (id 1)

- Text: "Uma arma de uma mão com esta habilidade pode ser usada com as duas mãos para aumentar seu dano em um passo."
- Single shared `item_granted` power, id 14023 (`usability: roll_active`, effect `weapon_step_increase add 1`). Every weapon with this ability grants the same power id via its own `effects`; the row's `source_inventory_id` scopes it to that exact weapon.

## Ágil (id 2)

- Text: "Pode ser usada com Acuidade com Arma, mesmo não sendo uma arma leve."
- Single shared `item_granted` power, id 14024, carrying the same effects as the general power Acuidade com Arma (id 11033): `skill_attribute override` Luta (19) → `attribute_dex`, `mod_dmg_attribute set` → `attribute_dex`. `usability: roll_active`, no `applies_when` — the granting weapon's `source_inventory_id` already scopes it, so it needs no `weapon_any` grip/purpose gate the way the general power does.

## Alongada (id 3)

- Text: "Dobra o alcance natural do atacante, mas não permite atacar um adversário adjacente."
- No effect — `ability_ids` only.

## Desbalanceada (id 4)

- Text: "Impõe uma penalidade de -2 em testes de ataque."
- Single shared `item_granted` power, id 14025 (`usability: passive`, effect `mod_hit add -2`).

## Dupla (id 5)

- Text: "Pode ser usada com Estilo de Duas Armas (e poderes similares) para fazer ataques adicionais, como se fosse uma arma de uma mão e uma arma leve. Cada 'ponta' conta como uma arma separada para efeitos de melhorias e encantos."
- Seeded as TWO separate `weapons` catalog rows, not one — `base_dmg` only ever holds a single die (`calculateWeaponDice` looks it up directly against the damage-step table), and melhorias/encantos already attach per `character_inventory` row, so two rows is what naturally gives each ponta its own die and its own independent improvement/enchantment slots.
- Ponta 1: `grip: one_hand` always (a `two_hand` Ponta 1 would leave no hand free for Ponta 2, defeating the whole point of the split — regardless of which grip category the weapon's own table row lists), holds the full `cost` and `slots`, name suffixed `(Ponta 1)`.
- Ponta 2: `grip: light`, `cost: 0`, `slots: 0` (Ponta 1 already carries the real slot cost — duplicating it would double-count carry capacity for one physical weapon), name suffixed `(Ponta 2)`.
- Both rows share the same icon and carry `ability_ids: [5]` (plus any other ability the weapon has, e.g. Ágil).
- Wielding both ends (one per hand) is self-reported by the player buying/equipping both rows — no special dual-wield wiring needed, Estilo de Duas Armas already works off two occupied hands.

## Híbrida (id 6)

- Text: "Uma arma híbrida possui dois ou mais modos de uso. Quando usa a arma, você considera apenas as características do modo que está usando, e aplica apenas habilidades e efeitos que afetem este modo. Trocar de modo é uma ação de movimento (ou livre, se tiver Saque Rápido). Aplicar melhorias e encantos em uma arma híbrida custa o dobro do preço em tibares."
- Same two-row shape as Dupla: one `weapons` catalog row per mode, each with its own `purpose`/`grip`/`base_dmg`/etc (e.g. Lança de Fogo: Modo 1 melee, Modo 2 fired). Modo 1 holds the full `cost`, Modo 2 is `cost: 0`, both named with a `(Modo N)` suffix and share the same icon.
- The "melhorias/encantos custam o dobro" clause falls out for free: the player buys/applies improvements on each mode row independently, so improving the weapon in both modes naturally costs twice the tibares — no multiplier field needed.
- Switching mode (movement action, or free with Saque Rápido) is self-reported, same as Dupla's dual-wielding.

## Ocultável (id 7)

- Text: "Ela fornece +5 em testes de Ladinagem para ocultá-la."
- Single shared `item_granted` power, id 14026 (`usability: roll_active`, effect `skill add` Ladinagem (18) `+5`). Player checks it only when the roll is specifically to hide the weapon.

## Surpreendente (id 8)

- Text: "Uma vez por cena, se você sacar a arma como ação livre e usá-la para atacar no mesmo turno, o oponente fica desprevenido contra esse ataque."
- No effect — `ability_ids` only.

## Versátil (id 9)

- Text: "Fornece bônus em uma ou mais manobras (cumulativo com outros bônus de itens), conforme a arma."
- Single shared `item_granted` power, id 14027 (`usability: roll_active`, effect `skill add` Luta (19) `+2`).
