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

## Weapon Sizes

Weapon sizes differ from character sizes.
While character sizes are Minusculo(-2), Pequeno(-1), Médio(0), Grande(+1), Enorme (+2) e Colossal (+3); 
Weapon sizes are: Reduzida(-1), Normal(0), Aumentada(+1), Gigante(+2).

This is the table for the natural grips:
Minusculo -> Reduzida
Pequeno -> Normal
Medio -> Normal
Grande -> Aumentada
Enorme -> Aumentada
Colossal -> Gigante

So,
-2 -> -1
-1 -> 0
0 -> 0
+1 -> +1
+2 -> +1
+3 -> +2

Each weapon growth step increases/decreases the damage_step for that weapon.
Character can wield a weapon one size larger than their natural grip, wielding a -5 hit penalty.
Theres a power, Empunhadura Poderosa that makes this penalty just -2.
This is relevant in scenarios like: 
User has a Grande (+1) character. They can't wield a Gigante (+2) Weapon. They can only wield a Aumentada weapon, since Gigante weapon is two steps further down the chain.

## Directly from the collab wiki, for any ambiguities:

Directly from the collab wiki:
Armas
Armas são classificadas de acordo com a proficiência necessária para usá-la (simples, marciais, exóticas ou de fogo), propósito (ataque corpo a corpo ou à distância) e empunhadura (leve, uma mão ou duas mãos).

Proficiência
Armas Simples. Armas de manejo fácil, como adagas, clavas e lanças. Todos os personagens sabem usar armas simples.
Armas Marciais. Espadas, machados e outras armas de uso específico de combatentes. Bárbaros, bardos, bucaneiros, caçadores, cavaleiros, guerreiros, nobres e paladinos sabem usar armas marciais.
Armas Exóticas. Armas difíceis de dominar, como a corrente de espinhos e a espada bastarda. Exigem treinamento específico.
Armas de Fogo. Armas de pólvora são raras em Arton, por isso exigem treinamento específico.
Penalidade por Não Proficiência: Se você atacar com uma arma com a qual não seja proficiente, sofre -5 nos testes de ataque.
Todas as criaturas são proficientes em ataques desarmados e em suas armas naturais.
Propósito
Corpo a Corpo. Podem ser usadas para atacar alvos adjacentes. Para atacar com uma arma de combate corpo a corpo, faça um teste de Luta. Quando você ataca com uma arma corpo a corpo, soma sua Força às rolagens de dano.
Ataque à Distância. Podem ser usadas para atacar alvos adjacentes ou à distância. Para atacar com uma arma de combate à distância, faça um teste de Pontaria. São subdivididas em de arremesso e de disparo.
Arremesso: A própria arma é atirada, como uma adaga ou azagaia. Sacar uma arma de arremesso é uma ação de movimento. Quando você ataca com uma arma de arremesso, soma sua Força às rolagens de dano.
Disparo: A arma dispara um projétil, como um arco atira flechas. Sacar a munição de uma arma de disparo é uma ação livre. Recarregar uma arma de disparo exige as duas mãos. Quando ataca com uma arma de disparo, não soma nenhum valor de atributo às rolagens de dano.
Empunhadura
Leve. Esta arma é usada com uma mão e se beneficia do poder Acuidade com Arma.
Uma Mão. Esta arma é usada com uma mão, deixando a outra mão livre para outros fins.
Duas Mãos. Esta arma é usada com as duas mãos. Livrar uma mão é uma ação livre. Reempunhá-la é uma ação de movimento (ou livre, se você puder sacá-la dessa forma).
Características das Armas
Preço. Inclui acessórios básicos, como bainhas para lâminas e aljavas para flechas.
Dano. Quando você acerta um ataque, rola o dano indicado (acrescente modificadores, se houver). O resultado é subtraído dos pontos de vida do alvo. O dano na tabela se refere a armas normais, para criaturas Pequenas e Médias.
Crítico. Quando você acerta um ataque rolando um 20 natural (ou seja, o dado mostra um 20), faz um acerto crítico. Neste caso, multiplique os dados de dano por 2. Bônus numéricos e dados extras (como pela habilidade Ataque Furtivo) não são multiplicados. Por exemplo, um dano de 1d8+3 torna-se 2d8+3 com um acerto crítico. Certas armas fazem críticos em margem maior que 20 ou multiplicam o dano por um valor maior que 2. Em geral, armas mais precisas (bestas, espadas...) têm margem maior, enquanto armas mais penetrantes (arcos, machados...) têm multiplicador maior. Efeitos que aumentam a margem de ameaça diminuem o número necessário para conseguir um crítico. Já efeitos que aumentam o multiplicador de crítico são acrescentados ao número do multiplicador.
19: A arma tem margem de ameaça 19 ou 20.
18: A arma tem margem de ameaça 18, 19 ou 20.
x2, x3, x4: A arma causa dano dobrado, triplicado ou quadruplicado em caso de acerto crítico.
19/x3: A arma tem margem de ameaça 19 ou 20 e causa dano triplicado em caso de acerto crítico.
Alcance. Armas com alcance podem ser usadas para ataques à distância. As categorias de alcance são curto (9m, ou 6 quadrados em um mapa), médio (30m ou 20 quadrados) e longo (90m ou 60 quadrados). Você pode atacar dentro do alcance sem sofrer penalidades. Você pode atacar até o dobro do alcance, mas sofre -5 no teste de ataque. Armas sem alcance podem ser arremessadas em alcance curto com -5 no teste de ataque.
Tipo. Armas tipicamente causam dano por corte (C), impacto (I) ou perfuração (P). Certas criaturas são resistentes ou imunes a certos tipos de dano.

Espaço. Quantos espaços a arma ocupa, importante para a capacidade de carga do personagem.

Habilidades de Armas
Algumas armas possuem uma ou mais das habilidades a seguir. Habilidades de armas aparecem em itálico no texto, para facilitar sua identificação.

Adaptável. Uma arma de uma mão com esta habilidade pode ser usada com as duas mãos para aumentar seu dano em um passo.
Ágil. Pode ser usada com Acuidade com Arma, mesmo não sendo uma arma leve.
Alongada. Dobra o alcance natural do atacante, mas não permite atacar um adversário adjacente.
Desbalanceada. Impõe uma penalidade de -2 em testes de ataque.
Dupla. Pode ser usada com Estilo de Duas Armas (e poderes similares) para fazer ataques adicionais, como se fosse uma arma de uma mão e uma arma leve. Cada “ponta” conta como uma arma separada para efeitos de melhorias e encantos.
Híbrida (AA). Uma arma híbrida possui dois ou mais modos de uso. Quando usa a arma, você considera apenas as características do modo que está usando, e aplica apenas habilidades e efeitos que afetem este modo. Trocar de modo é uma ação de movimento (ou livre, se tiver Saque Rápido). Aplicar melhorias e encantos em uma arma híbrida custa o dobro do preço em tibares.
Ocultável (DH). O tamanho e/ou formato da arma tornam mais fácil escondê-la. Ela fornece +5 em testes de Ladinagem para ocultá-la. A adaga é uma arma ocultável.
Surpreendente (DH). Uma vez por cena, se você sacar a arma como ação livre e usá-la para atacar no mesmo turno, o oponente fica desprevenido contra esse ataque.
Versátil. Fornece bônus em uma ou mais manobras (cumulativo com outros bônus de itens), conforme a arma.
Regras Opcionais: Armas Leves e Ágeis
Com esta regra, você pode usar Destreza em vez de Força para testes de ataque corpo a corpo e na rolagem de dano com armas leves, ágeis e de arremesso. Se usar esta regra, ignore o pré-requisito de Acuidade com Arma em qualquer habilidade ou item.

Passos de Dano
Alguns efeitos podem aumentar ou diminuir o dano da arma em um ou mais "passos". Por exemplo, armas aumentadas, usadas por criaturas Grandes, causam um passo a mais de dano. Sempre que precisar aumentar ou diminuir o dano de uma arma em um ou mais passos.

-2 passos	-1 passo	Normal	+1 Passo	+2 Passos	+3 Passos
1	1d2	1d3	1d4	1d6	1d8
1d2	1d3	1d4	1d6	1d8	1d10
1d3	1d4	1d6	1d8	1d10	1d12
1d4	1d6	1d8 ou 2d4	1d10	1d12	3d6
1d6	1d8	1d10	1d12	3d6	4d6
1d8	1d10	1d12 2d6 ou 3d4	3d6	4d6	4d8
1d10	2d6	2d8	3d8	4d8	4d10
2d6	2d8	2d10	3d10	4d10	4d12(máximo)
Ataques Desarmados & Armas Naturais
Um ataque desarmado é um soco, chute ou qualquer outro golpe que use seu próprio corpo. Um ataque desarmado é considerado uma arma leve corpo a corpo que causa dano de impacto não letal (1d3 pontos de dano para criaturas Pequenas e Médias) e não é afetado por efeitos que mencionem especificamente objetos ou armas empunhadas. Uma criatura só possui um único ataque desarmado (mas pode escolher qual parte do corpo utiliza cada vez que o desfere).

Armas naturais representam partes específicas do corpo de uma criatura que podem ser usadas para desferir ataques, como chifres, garras ou uma poderosa mordida. Armas naturais são consideradas armas leves corpo a corpo e, assim como ataques desarmados, não são afetadas por efeitos que afetem especificamente objetos (uma arma natural não pode ser desarmada ou quebrada, por exemplo) ou que afetem armas que precisam ser empunhadas. A quantidade e tipo de dano de cada arma natural são apresentados em sua descrição.
