<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ClassGuerreiroPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.

        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up. Unrolled from a $tiers/foreach loop 2026-09-08 — one
        // explicit Power::create per tier, same convention as everywhere
        // else, so this file can be split by class without a templated
        // block spanning multiple output files.
        Power::create([
            'id' => 1,
            'name' => 'Ataque Especial +4',
            'description' => 'Você pode gastar 1PM para receber +4 no teste de ataque ou na rolagem de dano. Você pode dividir os bônus igualmente.',
            'source' => 'class_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'ataque_especial_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 1], // Guerreiro
            ],
            'effects' => [
                // Odd one out — the player splits this bonus between the
                // attack roll and the damage roll however they like
                // (equally, or all into one), not a fixed split like
                // every other mod_hit/mod_dmg power. Tagged separately
                // from those two so a resolver can single out "needs a
                // player choice at roll time" instead of just summing it
                // blindly into one bucket. Not resolved yet — parked
                // until the attack roll UI actually asks for the split.
                ['tag' => 'mod_hit_or_dmg', 'op' => 'add', 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 2,
            'name' => 'Ataque Especial +8',
            'description' => 'Você pode gastar 2PM para receber +8 no teste de ataque ou na rolagem de dano. Você pode dividir os bônus igualmente.',
            'source' => 'class_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'ataque_especial_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'mod_hit_or_dmg', 'op' => 'add', 'value' => 8],
            ],
        ]);

        Power::create([
            'id' => 3,
            'name' => 'Ataque Especial +12',
            'description' => 'Você pode gastar 3PM para receber +12 no teste de ataque ou na rolagem de dano. Você pode dividir os bônus igualmente.',
            'source' => 'class_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'ataque_especial_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 9], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'mod_hit_or_dmg', 'op' => 'add', 'value' => 12],
            ],
        ]);

        Power::create([
            'id' => 4,
            'name' => 'Ataque Especial +16',
            'description' => 'Você pode gastar 4PM para receber +16 no teste de ataque ou na rolagem de dano. Você pode dividir os bônus igualmente.',
            'source' => 'class_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'ataque_especial_01.webp',
            'pm_cost' => 4,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 13], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'mod_hit_or_dmg', 'op' => 'add', 'value' => 16],
            ],
        ]);

        Power::create([
            'id' => 5,
            'name' => 'Ataque Especial +20',
            'description' => 'Você pode gastar 5PM para receber +20 no teste de ataque ou na rolagem de dano. Você pode dividir os bônus igualmente.',
            'source' => 'class_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'ataque_especial_01.webp',
            'pm_cost' => 5,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 17], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'mod_hit_or_dmg', 'op' => 'add', 'value' => 20],
            ],
        ]);

        // Lenda

        Power::create([
            'id' => 75,
            'name' => 'Durão',
            'description' => 'A partir do 3º nível, sua rijeza muscular permite que você absorva ferimentos. Sempre que sofre dano, você pode gastar 3 PM para reduzir esse dano à metade.',
            'source' => 'class_granted',
            'usability' => 'active',
            'icon_file_name' => 'durao_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 3], // Guerreiro 3
            ]
        ]);

        Power::create([
            'id' => 76,
            'name' => 'Ataque Extra',
            'description' => 'A partir do 6º nível de Guerreiro, quando usa a ação agredir, você pode gastar 2 PM para realizar um ataque adicional uma vez por rodada.',
            'source' => 'class_granted',
            'usability' => 'active',
            'icon_file_name' => 'ataque_extra_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6], // Guerreiro 6
            ],
            // extra_attack removed 2026-09-04 — no combat engine tracks a
            // second attack anyway (no per-attack sequencing, no shared
            // to-hit/damage roll count), so modeling "grants +1 attack"
            // bought nothing. Self-reported like Sequencial: PM cost is
            // tracked (Ativar), the extra attack itself isn't.
        ]);

        Power::create([
            'id' => 79,
            'name' => 'Ataque Reflexo',
            'description' => 'Se um alvo em alcance de seus ataques corpo a corpo ficar desprevenido ou se mover voluntariamente para fora do seu alcance, você pode gastar 1 PM para fazer um ataque corpo a corpo contra esse alvo (apenas uma vez por alvo a cada rodada).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'ataque_reflexo_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 1],
            ],
            // No effects — this depends on melee range/engagement (who's
            // in range of whom) and reacting mid another creature's turn,
            // neither of which the app tracks at all (no grid/positioning
            // system, deliberately out of scope). Structurally
            // unresolvable without a whole separate positional layer, not
            // just "not modeled yet" — pure self-report, permanently.
        ]);

        Power::create([
            'id' => 80,
            'name' => 'Bater e Correr',
            'description' => 'Quando faz uma investida, você pode continuar se movendo após o ataque, até o limite de seu deslocamento. Se gastar 2 PM, pode fazer uma investida sobre terreno difícil e sem sofrer a penalidade de Defesa.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'bater_e_correr_01.webp',
            // pm_cost is only for the upgraded version (terreno
            // difícil + no Defesa penalty) — the base "keep moving after
            // an investida" part is free. One pm_cost field can't
            // represent "free base effect, paid upgrade" — same unmodeled
            // shape as Corromper Equipamento's Armamento Aberrante
            // discount. Both halves are movement-conditional anyway (see
            // combat-engine-plans.md's "No board, no grid" — permanently
            // self-reported, no positions tracked), so no effects either
            // way.
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
        ]);

        Power::create([
            'id' => 81,
            'name' => 'Destruidor',
            'description' => 'Quando causa dano com uma arma corpo a corpo de duas mãos, você pode rolar novamente qualquer resultado 1 ou 2 da rolagem de dano da arma.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'golpe_destruidor_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 1],
            ],
            // weapon_grip is real, checkable data (weapons.grip)
            // — gates whether this power is even relevant to surface in a
            // self-report checklist, separate from the granted capability
            // below.
            'visibility_reqs' => ['weapon_grip' => 'two_hand'],
            'effects' => [
                // New tag: reroll_dice_below — generic threshold value
                // instead of baking "1 or 2" into the tag name, so a
                // future power with a different threshold (e.g. reroll
                // any 1) reuses this same tag. op: grant, not add/set —
                // this hands you a capability, not a number to sum.
                ['tag' => 'reroll_dice_below', 'op' => 'grant', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 82,
            'name' => 'Esgrimista',
            'description' => 'Quando usa uma arma corpo a corpo leve ou ágil, você soma sua Inteligência em rolagens de dano (limitado pelo seu nível).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'esgrimista_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'int', 'min' => 1],
            ],
            // "leve ou ágil" is an OR across two different data sources —
            // grip (weapons.grip) vs. the Ágil weapon ability
            // (weapon_abilities id 2, weapons.ability_ids) — an array of
            // small condition objects, satisfied if any one matches.
            // Gates whether this power is even relevant to surface in a
            // self-report checklist, separate from the effect's own
            // numeric value below.
            'visibility_reqs' => ['weapon_any' => [
                ['grip' => 'light'],
                ['ability' => 2], // Ágil
            ]],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 'int', 'limit' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 83,
            'name' => 'Especialização em Arma',
            'description' => 'Escolha uma arma. Você recebe +2 em rolagens de dano com essa arma. Você pode escolher este poder outras vezes para armas diferentes. <br><br>No APP, virá automaticamente marcado na tela de rolagens de dano. Você pode escolher não utilizar o poder.',
            'source' => 'class',
            // Unlike Arqueiro/Destruidor, this can never be auto-resolved
            // — there's no chosen-weapon tracking (deliberately not
            // added; see tag-system.md's Parked section for the
            // discussion). No data exists for "is this the weapon I
            // specialized in," so a human declares it fresh every roll —
            // roll_active, not passive. No pm_cost, so roll_active fits
            'usability' => 'roll_active',
            // No downside to checking this — flat +2, no tradeoff.
            'default_checked' => true,
            'icon_file_name' => 'especializacao_em_arma_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // "Escolha uma arma... você pode escolher este poder
                // outras vezes para armas diferentes" isn't modeled — no
                // per-instance chosen-weapon reference exists (would need
                // a new column + loosened unique constraint on
                // character_active_effects, rejected as not worth the
                // schema growth for a flavor-only distinction the app
                // can't otherwise use). One copy of this power covers
                // every "espada que você especializou" the player has,
                // self-reported same as everything else — see
                // tag-system.md's Parked section.
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 84,
            'name' => 'Especialização em Armadura',
            'description' => 'Você recebe redução de dano 5 se estiver usando uma armadura pesada.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especializacao_em_armadura_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 12], // Guerreiro 12
            ],
            // No effects — same reasoning just applied to Durão/Júbilo na
            // Dor above: damage_reduction reduces damage RECEIVED, but
            // there's no incoming-damage calculation anywhere in the app
            // for it to plug into. Self-reported, prose only.
            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 85,
            'name' => 'Golpe de Raspão',
            'description' => 'Uma vez por rodada, quando erra um ataque, você pode gastar 2 PM. Se fizer isso, causa metade do dano que causaria (ignorando efeitos que se aplicariam caso o ataque acertasse). Role seu ataque e dano novamente, então reduza pela metade.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'golpe_de_raspao_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — "metade do dano que causaria" needs the app to
            // actually know what a missed attack's damage would have been
            // (and to selectively ignore on-hit effects while computing
            // it), which no damage calculation the app has does. Same
            // "self-reported, no mechanism to plug into" treatment as the
            // damage_reduction powers above. "Uma vez por rodada" isn't
            // enforced either — no per-round usage tracking exists.
        ]);

        Power::create([
            'id' => 86,
            'name' => 'Golpe Demolidor',
            'description' => 'Quando usa a manobra quebrar ou ataca um objeto, você pode gastar 2 PM para ignorar a redução de dano dele.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'demolidor_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // New tag: ignore_dr — same flat-number-or-percent-string
                // convention as damage_reduction, so a flat "ignore N
                // points" power (Romper Resistências) and a full-bypass
                // power like this one share one tag/scale instead of
                // needing a separate boolean capability. "100%" here
                // means ignore all of it. Meant for the future damage
                // roll screen's checklist, same self-report-via-checkbox
                // pattern as mod_hit in the attack modal.
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => '100%'],
            ],
        ]);

        Power::create([
            'id' => 87,
            'name' => 'Mestre em Arma',
            'description' => 'Escolha uma arma. Com esta arma, seu dano aumenta em um passo e você pode gastar 2 PM para rolar novamente um teste de ataque recém realizado. <br><br>No APP, o dano será calculado automaticamente. <br><br>Para re-rollar, reduza manualmente o PM e role novamente o ataque.',
            'source' => 'class',
            'usability' => 'roll_active',
            // No downside to checking this — start it checked, same
            // reasoning as any no-cost bonus.
            'default_checked' => true,
            'icon_file_name' => 'mestre_em_armas_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                // "com a arma escolhida" isn't checkable — Especialização
                // em Arma (power 83) doesn't track which weapon either
                // (see tag-system.md's Parked section), so this just
                // requires having that power at all.
                ['type' => 'power', 'power_id' => 83], // Especialização em Arma
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 12], // Guerreiro 12
            ],
            'effects' => [
                // weapon_step_increase — bumps the weapon's damage die up
                // one step (1d6->1d8->1d10...), op: add sums across
                // sources. Resolved by calculate-weapon-dice.ts.
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
            // The post-roll reroll option isn't tagged — it's not a
            // pre-roll checklist modifier at all (you already rolled by
            // the time you decide to use it), a fundamentally different
            // interaction shape than everything else here. The 2 PM cost
            // for the reroll isn't enforced either; the player tracks
            // their own PM if they use it.
        ]);

        Power::create([
            'id' => 88,
            'name' => 'Planejamento Marcial',
            'description' => 'Uma vez por dia, você pode gastar uma hora e 3 PM para escolher um poder de guerreiro ou de combate cujos pré-requisitos cumpra. Você recebe os benefícios desse poder até o próximo dia. <br><br>No APP, após ativar, use o botão "Adicionar Poder".',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'planejamento_marcial_01.webp',
            'action_cost' => 'none', // "uma hora" isn't a combat action-economy concept
            'duration' => 'day', // "até o próximo dia"
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12], // treinado em Guerra
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 10], // Guerreiro 10
            ],
            // No effects — the actual chosen power varies every use, so
            // there's nothing fixed to tag. Player self-manages via the
            // existing Adicionar Poder button: add whichever qualifying
            // power they picked for the day, remove it once it expires.
            // "Uma vez por dia" and the prerequisite check against the
            // chosen power aren't enforced — both self-reported.
        ]);

        Power::create([
            'id' => 89,
            'name' => 'Romper Resistências',
            'description' => 'Quando faz um Ataque Especial, você pode gastar 1 PM adicional para ignorar 10 pontos de redução de dano.',
            'source' => 'class',
            'usability' => 'roll_active',
            // No downside to checking this — start it checked.
            'default_checked' => true,
            'icon_file_name' => 'romper_resistencias_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                // "quando faz um Ataque Especial" is a per-use condition,
                // not a pick-time gate — self-reported, not a
                // prerequisite entry.
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 90,
            'name' => 'Solidez',
            'description' => 'Se estiver usando um escudo, você aplica o bônus na Defesa recebido pelo escudo em testes de resistência.',
            'source' => 'class',
            // Self-contained gear condition, no decision — same treatment
            // as Arqueiro/Destruidor.
            'usability' => 'passive',
            'icon_file_name' => 'solidez_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // value: 'mod_def_from_shield' — new sentinel alongside
                // the existing flat-number/attribute-code conventions:
                // "use whatever mod_def the character's currently
                // equipped shield grants" (character_hands ->
                // character_inventory -> shields.mod_def). Self-contained
                // — no shield worn means nothing to grab, so no separate
                // requires_shield_equipped condition needed. Two shields
                // worn at once: frontend just takes whichever comes up
                // first, no tie-break logic. Not resolved yet — parked
                // for whenever Defesa/skill resolution reads this value
                // kind. Same 3-skill shape as Afinidade com a Tormenta/
                // Rejeição Divina/Protegido dos Deuses.
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 'mod_def_from_shield'], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 'mod_def_from_shield'], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 'mod_def_from_shield'], // Vontade
            ],
        ]);

        Power::create([
            'id' => 91,
            'name' => 'Tornado de Dor',
            'description' => 'Você pode gastar uma ação padrão e 2 PM para desferir uma série de golpes giratórios. Faça um ataque corpo a corpo e compare-o com a Defesa de cada inimigo em seu alcance natural. Então faça uma rolagem de dano com um bônus cumulativo de +2 para cada acerto e aplique-a em cada inimigo atingido. <br><br>No APP, adicione manualmente o bônus de dano.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'tornado_de_dor_01.webp',
            'action_cost' => 'standard',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6], // Guerreiro 6
            ],
            // No effects — a real multi-target AoE resolution (one attack
            // roll compared against every enemy in reach, a damage roll
            // that scales with hit count, applied per target) is well
            // beyond a single-target mod_hit/mod_dmg tag, and there's no
            // multi-target attack/damage screen to plug it into anyway.
            // Fully self-reported.
        ]);

        Power::create([
            'id' => 92,
            'name' => 'Valentão',
            'description' => 'Você recebe +2 em testes de ataque e rolagens de dano contra oponentes caídos, desprevenidos, flanqueados ou indefesos.',
            'source' => 'class',
            // Condition is the target's status, not something the app
            // tracks — self-reported, same treatment as every other
            // enemy-state condition. default_checked stays false (the
            // column default): unlike a gear-based bonus, this one's
            // genuinely situational, not usually true.
            'usability' => 'roll_active',
            'icon_file_name' => 'valentao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 93,
            'name' => 'Campeão',
            'description' => 'No 20º nível, o dano de todos os seus ataques aumenta em um passo. Além disso, sempre que você faz um Ataque Especial ou um Golpe Pessoal e acerta o ataque, recupera metade dos PM gastos nele. <br><br>No APP, o dano será calculado automaticamente. Para recuperar os PM, adicione-os manualmente.',
            // Auto-granted at level 20, no pick involved — same as every
            // Ataque Especial tier, Durão, and Ataque Extra, not a
            // choosable "class" power like Especialização em Arma.
            'source' => 'class_granted',
            // Unconditional standing fact, not even gear-conditional like
            // Mestre em Arma — always true once granted.
            'usability' => 'passive',
            'icon_file_name' => 'campeao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 20], // Guerreiro 20
            ],
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
            // The PM-recovery clause isn't tagged — no PM is auto-
            // deducted/restored anywhere in the app for any power (see
            // Ataque Especial etc.), so nothing to plug an automatic
            // "recupera metade dos PM gastos" into. Player tracks and
            // restores it manually.
        ]);

        Power::create([
            'id' => 94,
            'name' => 'Análise Tática',
            'description' => 'Você recebe +2 em Guerra e pode fazer testes dessa perícia para identificar criatura contra humanoides.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'analise_tatica_01.webp',
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12], // treinado em Guerra
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 12, 'value' => 2], // Guerra
            ],
            // "Identificar criatura contra humanoides" isn't modeled —
            // no identify-creature mechanic exists anywhere in the app.
            // Self-reported, prose only.
        ]);

        Power::create([
            'id' => 95,
            'name' => 'Arremesso de Investida',
            'description' => 'Quando faz uma investida, você pode gastar 1 PM para realizar um ataque à distância adicional com uma arma de arremesso contra o alvo da investida.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'arremesso_de_investida_01.webp',
            'action_cost' => 'none', // rides the investida action, not a separate action of its own
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — a real extra ranged attack against a specific
            // target needs the same multi-target/second-attack resolution
            // Tornado de Dor and Ataque Extra would need, nothing to plug
            // into. Fully self-reported.
        ]);

        Power::create([
            'id' => 96,
            'name' => 'Bloqueio Brutal',
            'description' => 'Uma vez por rodada, quando é atingido por um ataque, você pode gastar 2 PM para fazer uma rolagem de dano corpo a corpo e subtrair o resultado dessa rolagem do dano causado pelo ataque.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'bloqueio_brutal_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 5],
            ],
            // No effects — same incoming-damage-calculation gap as
            // damage_reduction (Durão/Júbilo na Dor/Especialização em
            // Armadura): nothing in the app computes incoming damage for
            // a "roll and subtract" reduction to plug into. "Uma vez por
            // rodada" isn't enforced either. Fully self-reported.
            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 97,
            'name' => 'Corte Ágil',
            'description' => 'Uma vez por rodada, quando faz um ataque com uma arma ágil ou leve, você pode gastar 1 PM para se mover até metade do seu deslocamento antes ou depois de fazer o ataque. Esse movimento não ativa reações dos inimigos (como de Ataque Reflexo).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'corte_agil_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — movement and reaction-avoidance are both
            // permanently self-reported categories (no board/grid, no
            // positioning system — see combat-engine-plans.md's "No
            // board, no grid"). "Uma vez por rodada" and the weapon
            // condition (ágil ou leve, same data shape as Esgrimista)
            // aren't enforced either. Fully self-reported.
        ]);

        Power::create([
            'id' => 98,
            'name' => 'Criar Oportunidade',
            'description' => 'Quando você ou um aliado em alcance curto atacar uma criatura sob efeito do seu Xadrez de Batalha, você pode gastar 1 PM para que esse ataque cause +1d10 pontos de dano. <br><br>No APP, adicione manualmente o dano extra.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'criar_oportunidade_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'power', 'power_id' => 99], // Xadrez de Batalha — confirmed against the book, the one dependent power its per_dependent_power scaling actually counts
            ],
            // No effects — depends on Xadrez de Batalha's active state
            // and an ally-targeted bonus damage roll, same
            // multi-participant resolution gap as Tornado de Dor/
            // Arremesso de Investida. Fully self-reported.
        ]);

        Power::create([
            'id' => 99,
            'name' => 'Xadrez de Batalha',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para analisar um oponente em alcance curto. Se fizer isso, você recebe +2 na Defesa e em testes de Reflexos contra essa criatura até o fim da cena. Esse bônus aumenta em +1 para cada outro poder que você possua que tenha Xadrez de Batalha como pré-requisito.',
            'source' => 'class',
            // Real Ativar/Desativar toggle, same as Percepção Temporal —
            // is_active on/off, effects fold into standing Defesa/
            // Reflexos totals while on. The "contra essa criatura"
            // scoping isn't separately tracked (no per-enemy state) — the
            // toggle itself is the self-report: the player turns it on
            // while fighting the analyzed target and off once that fight
            // ends, same trust model as every other self-reported
            // condition.
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'xadrez_de_batalha_01.webp',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'skill_trained', 'skill_id' => 12], // treinado em Guerra
            ],
            'effects' => [
                // value is a parseable formula string — same idea as a
                // percent string like "50%" or an attribute code like
                // "knw", not a separate sibling field. Shape:
                // "<base>+<per-match>*per_dependent_power[<id,id,...>]" —
                // here: base 2, +1 for every OTHER power the character
                // has whose prerequisites contain
                // {type: 'power', power_id: X} for any X in the bracketed
                // list (just [99], this power's own id, for now — a
                // comma-separated list lets a future power scale off
                // multiple dependency roots at once). Not resolved yet —
                // parked. Criar Oportunidade (power 98) formally lists
                // this power as a prerequisite (confirmed against the
                // book — the only one that does), so once a resolver
                // exists this would correctly evaluate to 3, not 2, for
                // a character who has both.
                ['tag' => 'mod_def', 'op' => 'add', 'value' => '2+1*per_dependent_power[99]'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => '2+1*per_dependent_power[99]'], // Reflexos
            ],
        ]);

        Power::create([
            'id' => 100,
            'name' => 'Defesa Estratégica',
            'description' => 'Você soma sua Inteligência na Defesa, limitada pelo seu nível.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'defesa_estrategica_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'int', 'min' => 1],
            ],
            'effects' => [
                // Same "attribute-code value + level cap" shape as
                // Percepção Temporal/Arqueiro, no stack_group — no "não
                // cumulativo" clause in the text.
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 'int', 'limit' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 101,
            'name' => 'Determinação Inabalável',
            'description' => 'Enquanto estiver com metade dos seus pontos de vida ou menos, você recebe +2 em testes de resistência e o custo de sua habilidade Durão diminui em –1 PM. <br><br>No APP, depois que usar Durão, adicione 1 PM de volta.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'determinacao_inabalavel_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 11], // Guerreiro 11
            ],
            'effects' => [
                // New field: requires_hp_at_or_below — unlike range/
                // position, this is real checkable data (current_pv vs
                // maxPv, already live on the sheet), so a real reusable
                // condition instead of permanent self-report. Percent
                // string, same convention as damage_reduction/ignore_dr.
                // Durão's PM discount isn't tagged — see the description's
                // <br><br>No APP note instead.
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2, 'requires_hp_at_or_below' => '50%'], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2, 'requires_hp_at_or_below' => '50%'], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2, 'requires_hp_at_or_below' => '50%'], // Vontade
            ],
        ]);

        Power::create([
            'id' => 102,
            'name' => 'Estrategista Inspirador',
            'description' => 'Em seu primeiro turno de um combate, você pode gastar uma ação padrão e fazer um teste de Guerra. Se fizer isso, para cada 10 pontos no resultado do teste, você e seus aliados em alcance curto recebem 1 PM temporário. Esses PM temporários desaparecem no fim da cena. <br><br>No APP, adicione manualmente os PM temporários.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'estrategista_inspirador_01.webp',
            'action_cost' => 'standard',
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12], // treinado em Guerra
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — ally-targeted, scales with a skill test result
            // (not a fixed value), and "primeiro turno de um combate"
            // needs turn tracking that doesn't exist. Same
            // multi-participant resolution gap as Tornado de Dor/Criar
            // Oportunidade/Estrategista's own Xadrez de Batalha-family
            // powers. Fully self-reported.
        ]);

        Power::create([
            'id' => 103,
            'name' => 'Executor',
            'description' => 'Você recebe +1d6 nas rolagens de dano contra criaturas que estejam com menos da metade dos pontos de vida. A cada quatro níveis além do 1º, esse dano extra aumenta em um passo.',
            'source' => 'class',
            // Target HP isn't tracked anywhere (unlike the character's own
            // current_pv) — no condition field to check against, so this
            // is a plain roll_active checkbox: the player judges "is the
            // target below half HP?" themselves and checks the box if so.
            'usability' => 'roll_active',
            'icon_file_name' => 'executor_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // op: 'extra_die' — an added die rolled separately from the
                // weapon's own base_dmg (own breakdown line, never scaled by
                // a future crit multiplier — only the weapon's die is,
                // see claude-stuff/tag-system.md). die_steps_per_levels
                // steps the die size up by one (1d6->1d8->1d10->...) for
                // every N levels past level 1 — not resolved yet.
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'die_steps_per_levels' => 4],
            ],
        ]);

        Power::create([
            'id' => 104,
            'name' => 'Fender Defesas',
            'description' => 'Quando você acerta um ataque usando Ataque Especial, a criatura sofre uma penalidade na Defesa igual ao total de PM gastos nessa habilidade por 1 rodada.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'fender_defesas_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — a debuff applied to the TARGET (not the
            // character), dynamically scaled by however much PM was
            // spent on Ataque Especial in that same instance, for a
            // fixed duration. No enemy-state tracking exists to apply a
            // Defesa penalty to. Fully self-reported.
        ]);

        Power::create([
            'id' => 105,
            'name' => 'Inércia do Aço',
            'description' => 'Quando acerta um ataque com uma arma de duas mãos em uma criatura, você pode gastar 3 PM para causar metade do dano desse ataque a cada inimigo adjacente a essa criatura.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inercia_do_aco_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5], // Guerreiro 5
            ],
            // weapon_grip gates whether this power is even
            // relevant to surface in a self-report checklist (e.g. the
            // planned attack-mode picker) — legitimate, real, checkable
            // data, independent of the mechanic below being unmodeled.
            'visibility_reqs' => ['weapon_grip' => 'two_hand'],
            // No effects — splash damage to every adjacent enemy is a
            // multi-target mechanic, same resolution gap as Tornado de
            // Dor. No combat engine planned, so this isn't "not resolved
            // yet" — there's no realistic consumer for the splash math
            // ever, tag or no tag. Fully self-reported; the checklist
            // entry (once built) is just a reminder to spend 3 PM and
            // apply it manually.
        ]);

        Power::create([
            'id' => 106,
            'name' => 'Investida Ricochete',
            'description' => 'Uma vez por rodada, quando faz uma investida e acerta o ataque, você pode gastar 2 PM para atacar outra criatura que você consiga alcançar como parte dessa investida.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'investida_ricochete_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 80], // Bater e Correr
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5], // Guerreiro 5
            ],
            // No effects — a second attack against a different target is
            // multi-target, same resolution gap as Tornado de Dor/
            // Inércia do Aço. "Uma vez por rodada" isn't enforced either.
            // Fully self-reported.
        ]);

        Power::create([
            'id' => 107,
            'name' => 'Manobra Dupla',
            'description' => 'Uma vez por rodada, quando faz uma manobra de combate usando uma arma versátil, você pode pagar 1 PM para executar uma manobra diferente extra.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'manobra_dupla_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // "arma versátil" is real, checkable data (weapon_abilities id
            // 9), so it's worth gating visibility even though the
            // maneuver mechanic itself stays unmodeled.
            'visibility_reqs' => ['weapon_ability' => 9], // Versátil
            // No effects — an extra combat maneuver has no mechanical
            // resolution built (no maneuver system at all). "Uma vez por
            // rodada" isn't enforced either. Fully self-reported.
        ]);

        Power::create([
            'id' => 108,
            'name' => 'Mente Disciplinada',
            'description' => 'Sempre que você é afetado por uma habilidade de um aliado que fornece um bônus numérico em testes de perícia, rolagens de dano ou na Defesa, para você esse bônus aumenta em +1. <br><br>No APP, adicione manualmente o bônus extra.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'mente_disciplinada_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6], // Guerreiro 6
            ],
            // No effects
        ]);

        Power::create([
            'id' => 109,
            'name' => 'Ordens de Engajamento',
            'description' => 'Uma vez por rodada, quando acerta um ataque em uma criatura sob efeito do seu Xadrez de Batalha, você pode gastar 2 PM para que um aliado em alcance curto possa fazer um ataque contra essa criatura.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'ordens_de_engajamento_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 98], // Criar Oportunidade
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 11], // Guerreiro 11
            ],
            // No effects — ally-targeted extra attack, same multi-
            // participant resolution gap as Criar Oportunidade/Tornado de
            // Dor. Note: doesn't count toward Xadrez de Batalha's
            // per_dependent_power scaling — its own prerequisite is Criar
            // Oportunidade (power 98), not Xadrez de Batalha (power 99)
            // directly, and that scaling is a direct-dependent count, not
            // transitive. "Uma vez por rodada" isn't enforced either.
            // Fully self-reported.
        ]);

        Power::create([
            'id' => 110,
            'name' => 'Operações Combinadas',
            'description' => 'Quando usa Ordens de Engajamento, você pode gastar +3 PM. Se fizer isso, pode atacar junto do aliado e, se um de vocês usar habilidades com custo em PM que forneçam bônus a esse ataque ou a seu dano, o outro também é afetado (apenas se isso for aplicável ao ataque).',
            'source' => 'class',
            // PM-costed add-on tied to another specific power's
            // activation moment, same shape as Bater e Correr's upgrade
            // half — active, not roll_active (doesn't ride your own
            // roll).
            'usability' => 'active',
            'icon_file_name' => 'operacoes_combinadas_01.webp',
            'pm_cost' => 3, // additional, on top of Ordens de Engajamento's own 2 PM
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 109], // Ordens de Engajamento
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 14], // Guerreiro 14
            ],
            // No effects — shared-attack participation plus conditionally
            // sharing whichever OTHER PM-costed bonuses either combatant
            // uses is well beyond a single-target mod_hit/mod_dmg tag,
            // same multi-participant gap as Ordens de Engajamento/Criar
            // Oportunidade. Fully self-reported.
        ]);

        Power::create([
            'id' => 111,
            'name' => 'Recuperar Fôlego',
            'description' => 'Uma vez por cena, se estiver com 0 PM, você pode gastar uma ação de movimento para recuperar 1d8 PM.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'recuperar_folego_01.webp',
            'action_cost' => 'movement',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // Reuses the existing restore_pm tag (same shape as
                // Essência de Mana). "Se estiver com 0 PM" and "uma vez
                // por cena" aren't enforced — self-reported, same as
                // every other once-per/condition clause on active powers.
                ['tag' => 'restore_pm', 'op' => 'roll', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 112,
            'name' => 'Resiliência Marcial',
            'description' => 'Sempre que sofrer dano letal, você recebe redução de dano 1 cumulativa (limitada pelo seu nível). Esse efeito dura até o fim da cena ou até você recuperar pontos de vida de qualquer forma.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'resiliencia_marcial_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 4], // Guerreiro 4
            ],
            // No effects — same damage_reduction gap as Durão/Júbilo na
            // Dor/Especialização em Armadura, no incoming-damage
            // calculation to plug into.
            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 113,
            'name' => 'Soldado de Infantaria',
            'description' => 'Você recebe +3m em seu deslocamento e seu limite de carga aumenta em 6 espaços.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'soldado_da_infantaria_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // New tags: mod_movement, mod_inventory_space. Neither is
                // resolved yet — calculateMaxSlots (max-slots.ts) only
                // factors in base_str today, no powers/effects lookup at
                // all; there's no Movimento stat displayed on the sheet
                // yet either. Both are real near-term additions (a
                // planned Movimento row, and threading powers into
                // calculateMaxSlots the same way calculateDefense/
                // calculateMaxPv/calculateMaxPm already do), not
                // speculative — parked for real, not just "cheap to tag."
                ['tag' => 'mod_movement', 'op' => 'add', 'value' => 3],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 6],
            ],
        ]);

        Power::create([
            'id' => 114,
            'name' => 'Velho de Guerra',
            'description' => 'Seus olhos já viram muito e você não se abala facilmente. Você recebe +5 em Intimidação e imunidade a medo. Além disso, uma vez por cena pode gastar 5 PM para evitar completamente um efeito qualquer (ataque, magia etc.) usado contra você por outra criatura. Se o efeito for de área ou tiver outros alvos, continua funcionando normalmente contra eles. <br><br>No APP, use os PM manualmente quando ativar o poder.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'velho_de_guerra_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 17], // Guerreiro 17
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 5], // Intimidação
            ],
        ]);

        Power::create([
            'id' => 115,
            'name' => 'Golpe Pessoal',
            'description' => 'Quando faz um ataque, você pode desferir seu Golpe Pessoal, uma técnica única, com efeitos determinados por você. Você constrói seu Golpe Pessoal escolhendo efeitos da lista a seguir. Cada efeito possui um custo; a soma deles será o custo do Golpe Pessoal (mínimo 1 PM). O Golpe Pessoal só pode ser usado com uma arma específica (por exemplo, apenas espadas longas). Quando sobe de nível, você pode reconstruir seu Golpe Pessoal e alterar a arma que ele usa. Você pode escolher este poder outras vezes para golpes diferentes e não pode gastar mais PM em golpes pessoais em uma mesma rodada do que seu limite de PM.',
            'source' => 'class',
            // Rides the attack roll like any other roll_active power, but
            // the actual golpe selection/build lives entirely in
            // character_golpes_pessoais — this row is only what makes the
            // "Golpe Pessoal" card exist on the sheet at all (one row per
            // character.active_effects' own unique(character_id, power_id)
            // constraint), never itself queried for the mechanic. Picking
            // this power again for another golpe just adds another
            // character_golpes_pessoais row, not another active_effects
            // row — see claude-stuff/tag-system.md.
            'usability' => 'roll_active',
            'icon_file_name' => 'golpe_pessoal_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5], // Guerreiro 5
            ],
            // No effects — every menu item (Elemental, Brutal, Letal, etc.)
            // is its own 'specific'-source power referenced by id from
            // character_golpes_pessoais.power_ids, resolved live by
            // whichever bespoke UI/resolver handles Golpe Pessoal, never
            // generically via this power's own effects. Weapon restriction
            // is self-reported (see Especialização em Arma), not modeled.
            // "não pode gastar mais PM em golpes pessoais em uma mesma
            // rodada do que seu limite de PM" isn't enforced — self-
            // reported like every other PM-spend limit in the app.
        ]);
    }
}
