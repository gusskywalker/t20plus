<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 9000 and 9999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class DivineGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 9,
            'name' => 'Afinidade com a Tormenta',
            'description' => 'Você recebe +10 em testes de resistência contra efeitos da Tormenta, de suas criaturas e de devotos de Aharadak. Além disso, seu primeiro poder da Tormenta não conta para perda de Carisma.',
            'source' => 'divine_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'afinidade_com_a_tormenta_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [1]],
            ],
            'effects' => [

                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 10],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 10],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 10],

                ['tag' => 'tormenta_power_carisma_loss', 'op' => 'waive', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 10,
            'name' => 'Êxtase da Loucura',
            'description' => 'Toda vez que uma ou mais criaturas falham em um teste de Vontade contra uma de suas habilidades mágicas, você recebe 1 PM temporário cumulativo. Você pode ganhar um máximo de PM temporários por cena desta forma igual a sua Sabedoria.',
            'source' => 'divine_granted',
            'usability' => 'active',
            'icon_file_name' => 'extase_na_loucura_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'temp_pm', 'op' => 'add', 'value' => 1, 'limit' => 'knw'],
            ],
        ]);

        Power::create([
            'id' => 11,
            'name' => 'Percepção Temporal',
            'description' => 'Você pode gastar 3 PM para somar sua Sabedoria (limitado por seu nível e não cumulativo com efeitos que somam este atributo) a seus ataques, Defesa e testes de Reflexos até o fim da cena.',
            'source' => 'divine_granted',
            'usability' => 'active',
            'icon_file_name' => 'percepcao_temporal_01.webp',
            'duration' => 'scene',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 'knw', 'limit' => 'character_level', 'stack_group' => 'bonus_hit_knw'],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 'knw', 'limit' => 'character_level', 'stack_group' => 'bonus_def_knw'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 'knw', 'limit' => 'character_level', 'stack_group' => 'bonus_reflexos_knw'],
            ],
        ]);

        Power::create([
            'id' => 12,
            'name' => 'Rejeição Divina',
            'description' => 'Você recebe resistência a magia divina +5.',
            'source' => 'divine_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'rejeicao_divina_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 21,
            'name' => 'Corromper Equipamento',
            'description' => 'Você pode gastar 2 PM para cobrir uma arma, um escudo ou um esotérico que esteja empunhando com carapaça quitinosa. Até o fim da cena, o item recebe os benefícios de matéria vermelha, cumulativo com outros materiais especiais. Se usar este poder em uma arma produzida com Armamento Aberrante, seu custo é reduzido em –1 PM.',
            'source' => 'divine_granted',
            'usability' => 'active',
            'icon_file_name' => 'corromper_equipamento_01.webp',
            'action_cost' => 'none',
            'duration' => 'scene',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [1]],
            ],

        ]);

        Power::create([
            'id' => 22,
            'name' => 'Espalhar a Corrupção',
            'description' => 'Quando chega em uma comunidade, você pode gastar um dia e fazer um teste de Religião (CD 20). Se passar, você planta a semente da corrupção no coração das pessoas em uma área equivalente a uma aldeia, um castelo ou um bairro de uma cidade grande. Por uma semana, ou até você partir do lugar, a categoria de atitude dessas pessoas em relação umas às outras piora em um passo, à medida que o senso moral delas se deteriora e seus piores desejos vêm à tona. Isso pode ser útil para gerar conflitos entre elas, embora caiba a você descobrir exatamente como se aproveitar deles.',
            'source' => 'divine_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'espalhar_corrupcao_01.webp',
            'action_cost' => 'none',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [1]],
            ],

        ]);

        Power::create([
            'id' => 23,
            'name' => 'Júbilo na Dor',
            'description' => 'Quando causa ou sofre dano, você recebe redução de dano 1. Esse efeito é cumulativo e limitado por sua Sabedoria e termina se você passar 1 rodada sem causar ou sofrer dano.',
            'source' => 'divine_granted',
            'usability' => 'passive',
            'icon_file_name' => 'jubilo_na_dor_01.webp',
            'decay_after' => 1,
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [1]],
            ]
        ]);

        Power::create([
            'id' => 24,
            'name' => 'Mediador da Tempestade',
            'description' => 'Você pode se comunicar com lefeu inteligentes (Int –3 ou maior) livremente e recebe +5 em testes de Diplomacia e Intuição com criaturas da Tormenta e devotos de Aharadak.',
            'source' => 'divine_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'mediador_da_tempestade_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 276,
            'name' => 'Almejar o Impossível',
            'description' => 'Quando faz um teste de perícia, um resultado de 19 ou mais no dado sempre é um sucesso, não importando o valor a ser alcançado. <br><br>No APP, cheque sua rolagem de perícia, se for 19 ou mais, você passou!',
            'source' => 'divine_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'almejar_o_impossivel_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [17, 19]],
            ],
        ]);

        Power::create([
            'id' => 277,
            'name' => 'Armas da Ambição',
            'description' => 'Você recebe +1 em testes de ataque e na margem de ameaça com armas nas quais é proficiente.',
            'source' => 'divine_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armas_da_ambicao_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [19]],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 278,
            'name' => 'Coragem Total',
            //TODO add the immune to fear granting tag and change usability to passive
            'description' => 'Você é imune a efeitos de medo, mágicos ou não. Este poder não elimina fobias raciais (como o medo de altura dos minotauros).',
            'source' => 'divine_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'coragem_total_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [3, 7, 9, 19, 22, 24]],
            ],
        ]);

        Power::create([
            'id' => 279,
            'name' => 'Liberdade Divina',
            'description' => 'Você pode gastar 2 PM para receber imunidade a efeitos de movimento por uma rodada.',
            'source' => 'divine_granted',
            'usability' => 'active',
            'icon_file_name' => 'liberdade_divina_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [19]],
            ],
        ]);

        Power::create([
            'id' => 280,
            'name' => 'Alma de Mudança',
            'description' => 'No início de cada aventura, você pode trocar uma quantidade de poderes (limitada por sua Sabedoria) por poderes diferentes cujos pré-requisitos cumpra. Você não pode trocar este poder. <br><br>No APP, crie um novo personagem com os novos poderes. Envie o código do novo personagem para seu mestre para ser adicionado na nova campanha.',
            'source' => 'divine_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'alma_de_mudanca_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [17, 19]],
            ],
        ]);

        Power::create([
            //TODO add the effects and str -> knw swap if we ever add wearable bags and shit
            'id' => 281,
            'name' => 'Andarilho Carregado',
            'description' => 'Sua mochila de aventureiro não conta no seu limite de itens vestidos e, se estiver vestindo uma dessas mochilas, você pode usar Sabedoria para estabelecer seu limite de carga (em vez de Força). A critério do mestre, este poder pode ser aplicado a outro item equivalente (como uma mochila de carga).',
            'source' => 'divine_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'andarilho_carregado_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [19]],
            ],
        ]);

        Power::create([
            'id' => 282,
            'name' => 'Aventureiro Inquieto',
            'description' => 'Uma vez por busca (Tormenta20, p. 278), você pode rolar novamente um teste recém-realizado (mas deve aceitar o novo resultado) e, quando recebe uma recompensa ou um castigo aleatório por uma busca (incluindo rolagens na Tabela 8-1; Tormenta20, p. 328), rola dois dados e escolhe entre os dois resultados.',
            'source' => 'divine_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'aventureiro_inquieto_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [19]],
            ],
        ]);

        Power::create([
            'id' => 283,
            'name' => 'Convicção Ambiciosa',
            'description' => 'Quando luta em desvantagem (um encontro contra o dobro de inimigos que seu grupo, ou com ND maior que o do grupo), você recebe +2 em testes de perícia até o fim da cena. Além disso, se houver um ou mais inimigos de ND igual ou maior que seu nível, você recebe uma ação padrão extra em seu primeiro turno de combate. <br><br> No APP, ative o poder para receber o bônus nas perícias.',
            'source' => 'divine_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'conviccao_ambiciosa_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [19]],
            ],
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 2],
            ],
        ]);
        
        Power::create([
            'id' => 319,
            'name' => 'Disparo Sublime',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para fazer um teste de Percepção (CD 15 + ND da criatura) contra uma criatura em alcance médio. Se passar no teste e acertar um ataque com arco contra o alvo na mesma rodada, esse ataque é um acerto crítico automático. Se for o paladino de Cette, você pode usar Golpe Divino com ataques com arco à distância. <br><br>No APP, role percepção. Se passar, ative o poder. No final de seu turno, desative-o.',
            'source' => 'divine_granted',
            'usability' => 'active',
            'duration' => 'turn',
            'action_cost' => 'movement',
            'pm_cost' => 2,
            'icon_file_name' => 'disparo_sublime_01.webp',
            'applies_when' => ['weapon_purpose' => ['fired']],
            'prerequisites' => [
                ['type' => 'god', 'god_ids' => [43]],
            ],
            'effects' => [
                ['tag' => 'mod_margin', 'op' => 'set', 'value' => 1],
            ],
        ]);
    }
}
