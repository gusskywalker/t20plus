<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ClassGuerreiroPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 1,
            'name' => 'Ataque Especial +4',
            'description' => 'Você pode gastar 1PM para receber +4 no teste de ataque ou na rolagem de dano. Você pode dividir os bônus igualmente.',
            'source' => 'class_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'ataque_especial_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 1],
            ],
            'effects' => [

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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5],
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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 9],
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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 13],
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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 17],
            ],
            'effects' => [
                ['tag' => 'mod_hit_or_dmg', 'op' => 'add', 'value' => 20],
            ],
        ]);

        Power::create([
            'id' => 75,
            'name' => 'Durão',
            'description' => 'A partir do 3º nível, sua rijeza muscular permite que você absorva ferimentos. Sempre que sofre dano, você pode gastar 3 PM para reduzir esse dano à metade.',
            'source' => 'class_granted',
            'usability' => 'active',
            'icon_file_name' => 'durao_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 3],
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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 1],
            ],

        ]);

        Power::create([
            'id' => 80,
            'name' => 'Bater e Correr',
            'description' => 'Quando faz uma investida, você pode continuar se movendo após o ataque, até o limite de seu deslocamento. Se gastar 2 PM, pode fazer uma investida sobre terreno difícil e sem sofrer a penalidade de Defesa.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'bater_e_correr_01.webp',

            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]],
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
                ['type' => 'class', 'class_ids' => [1]],
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 1],
            ],

            'applies_when' => ['weapon_grip' => 'two_hand'],
            'effects' => [

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
                ['type' => 'class', 'class_ids' => [1]],
                ['type' => 'attribute', 'attribute' => 'int', 'min' => 1],
            ],

            'applies_when' => ['weapon_any' => [
                ['grip' => 'light'],
                ['ability' => 2],
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

            'usability' => 'roll_active',

            'default_checked' => true,
            'icon_file_name' => 'especializacao_em_arma_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]],
            ],
            'effects' => [

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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 12],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
            ],
            'effects' => [

                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => '100%'],
            ],
        ]);

        Power::create([
            'id' => 87,
            'name' => 'Mestre em Arma',
            'description' => 'Escolha uma arma. Com esta arma, seu dano aumenta em um passo e você pode gastar 2 PM para rolar novamente um teste de ataque recém realizado. <br><br>No APP, o dano será calculado automaticamente. <br><br>Para re-rollar, reduza manualmente o PM e role novamente o ataque.',
            'source' => 'class',
            'usability' => 'roll_active',

            'default_checked' => true,
            'icon_file_name' => 'mestre_em_armas_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [

                ['type' => 'power', 'power_id' => 83],
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 12],
            ],
            'effects' => [

                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],

        ]);

        Power::create([
            'id' => 88,
            'name' => 'Planejamento Marcial',
            'description' => 'Uma vez por dia, você pode gastar uma hora e 3 PM para escolher um poder de guerreiro ou de combate cujos pré-requisitos cumpra. Você recebe os benefícios desse poder até o próximo dia. <br><br>No APP, após ativar, use o botão "Adicionar Poder".',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'planejamento_marcial_01.webp',
            'action_cost' => 'none',
            'duration' => 'day',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12],
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 10],
            ],

        ]);

        Power::create([
            'id' => 89,
            'name' => 'Romper Resistências',
            'description' => 'Quando faz um Ataque Especial, você pode gastar 1 PM adicional para ignorar 10 pontos de redução de dano.',
            'source' => 'class',
            'usability' => 'roll_active',

            'default_checked' => true,
            'icon_file_name' => 'romper_resistencias_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [

                ['type' => 'class', 'class_ids' => [1]],
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

            'usability' => 'passive',
            'icon_file_name' => 'solidez_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]],
            ],
            'effects' => [

                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 'mod_def_from_shield'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 'mod_def_from_shield'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 'mod_def_from_shield'],
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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6],
            ],

        ]);

        Power::create([
            'id' => 92,
            'name' => 'Valentão',
            'description' => 'Você recebe +2 em testes de ataque e rolagens de dano contra oponentes caídos, desprevenidos, flanqueados ou indefesos.',
            'source' => 'class',

            'usability' => 'roll_active',
            'icon_file_name' => 'valentao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]],
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

            'source' => 'class_granted',

            'usability' => 'passive',
            'icon_file_name' => 'campeao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 20],
            ],
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],

        ]);

        Power::create([
            'id' => 94,
            'name' => 'Análise Tática',
            'description' => 'Você recebe +2 em Guerra e pode fazer testes dessa perícia para identificar criatura contra humanoides.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'analise_tatica_01.webp',
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12],
                ['type' => 'class', 'class_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 12, 'value' => 2],
            ],

        ]);

        Power::create([
            'id' => 95,
            'name' => 'Arremesso de Investida',
            'description' => 'Quando faz uma investida, você pode gastar 1 PM para realizar um ataque à distância adicional com uma arma de arremesso contra o alvo da investida.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'arremesso_de_investida_01.webp',
            'action_cost' => 'none',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 5],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
                ['type' => 'power', 'power_id' => 99],
            ],

        ]);

        Power::create([
            'id' => 99,
            'name' => 'Xadrez de Batalha',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para analisar um oponente em alcance curto. Se fizer isso, você recebe +2 na Defesa e em testes de Reflexos contra essa criatura até o fim da cena. Esse bônus aumenta em +1 para cada outro poder que você possua que tenha Xadrez de Batalha como pré-requisito.',
            'source' => 'class',

            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'xadrez_de_batalha_01.webp',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]],
                ['type' => 'skill_trained', 'skill_id' => 12],
            ],
            'effects' => [

                ['tag' => 'mod_def', 'op' => 'add', 'value' => '2+1*per_dependent_power[99]'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => '2+1*per_dependent_power[99]'],
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
                ['type' => 'class', 'class_ids' => [1]],
                ['type' => 'attribute', 'attribute' => 'int', 'min' => 1],
            ],
            'effects' => [

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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 11],
            ],
            'effects' => [

                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2, 'requires_hp_at_or_below' => '50%'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2, 'requires_hp_at_or_below' => '50%'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2, 'requires_hp_at_or_below' => '50%'],
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
                ['type' => 'skill_trained', 'skill_id' => 12],
                ['type' => 'class', 'class_ids' => [1]],
            ],

        ]);

        Power::create([
            'id' => 103,
            'name' => 'Executor',
            'description' => 'Você recebe +1d6 nas rolagens de dano contra criaturas que estejam com menos da metade dos pontos de vida. A cada quatro níveis além do 1º, esse dano extra aumenta em um passo.',
            'source' => 'class',

            'usability' => 'roll_active',
            'icon_file_name' => 'executor_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]],
            ],
            'effects' => [

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
                ['type' => 'class', 'class_ids' => [1]],
            ],

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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5],
            ],

            'applies_when' => ['weapon_grip' => 'two_hand'],

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
                ['type' => 'power', 'power_id' => 80],
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
            ],

            'applies_when' => ['weapon_ability' => 9],

        ]);

        Power::create([
            'id' => 108,
            'name' => 'Mente Disciplinada',
            'description' => 'Sempre que você é afetado por uma habilidade de um aliado que fornece um bônus numérico em testes de perícia, rolagens de dano ou na Defesa, para você esse bônus aumenta em +1. <br><br>No APP, adicione manualmente o bônus extra.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'mente_disciplinada_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6],
            ],

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
                ['type' => 'power', 'power_id' => 98],
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 11],
            ],

        ]);

        Power::create([
            'id' => 110,
            'name' => 'Operações Combinadas',
            'description' => 'Quando usa Ordens de Engajamento, você pode gastar +3 PM. Se fizer isso, pode atacar junto do aliado e, se um de vocês usar habilidades com custo em PM que forneçam bônus a esse ataque ou a seu dano, o outro também é afetado (apenas se isso for aplicável ao ataque).',
            'source' => 'class',

            'usability' => 'active',
            'icon_file_name' => 'operacoes_combinadas_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 109],
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 14],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
            ],
            'effects' => [

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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 4],
            ],

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
                ['type' => 'class', 'class_ids' => [1]],
            ],
            'effects' => [

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
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 17],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 115,
            'name' => 'Golpe Pessoal',
            'description' => 'Quando faz um ataque, você pode desferir seu Golpe Pessoal, uma técnica única, com efeitos determinados por você. Você constrói seu Golpe Pessoal escolhendo efeitos da lista a seguir. Cada efeito possui um custo; a soma deles será o custo do Golpe Pessoal (mínimo 1 PM). O Golpe Pessoal só pode ser usado com uma arma específica (por exemplo, apenas espadas longas). Quando sobe de nível, você pode reconstruir seu Golpe Pessoal e alterar a arma que ele usa. Você pode escolher este poder outras vezes para golpes diferentes e não pode gastar mais PM em golpes pessoais em uma mesma rodada do que seu limite de PM.',
            'source' => 'class',

            'usability' => 'roll_active',
            'icon_file_name' => 'golpe_pessoal_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5],
            ],

        ]);
    }
}
