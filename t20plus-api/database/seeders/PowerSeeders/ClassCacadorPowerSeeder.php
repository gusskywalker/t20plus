<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 3000 and 3999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class ClassCacadorPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 196,
            'name' => 'Marca da Presa (1d4)',
            'description' => 'Você pode gastar uma ação de movimento e 1PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +1d4 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'marca_da_presa_dice', 'value' => '1d4'],
            ],
        ]);

        Power::create([
            'id' => 197,
            'name' => 'Marca da Presa (1d8)',
            'description' => 'Você pode gastar uma ação de movimento e 2PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +1d8 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'marca_da_presa_dice', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 198,
            'name' => 'Marca da Presa (1d12)',
            'description' => 'Você pode gastar uma ação de movimento e 3PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +1d12 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 9],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'marca_da_presa_dice', 'value' => '1d12'],
            ],
        ]);

        Power::create([
            'id' => 199,
            'name' => 'Marca da Presa (2d8)',
            'description' => 'Você pode gastar uma ação de movimento e 4PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +2d8 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 4,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 13],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'marca_da_presa_dice', 'value' => '2d8'],
            ],
        ]);

        Power::create([
            'id' => 200,
            'name' => 'Marca da Presa (2d10)',
            'description' => 'Você pode gastar uma ação de movimento e 5PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +2d10 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 5,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 17],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'marca_da_presa_dice', 'value' => '2d10'],
            ],
        ]);

        Power::create([
            'id' => 201,
            'name' => 'Rastreador',
            'description' => 'Você recebe +2 em Sobrevivência. Além disso, pode se mover com seu deslocamento normal enquanto rastreia sem sofrer penalidades no teste de Sobrevivência.',
            'source' => 'class_granted',
            'usability' => 'passive',
            'icon_file_name' => 'rastreador_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 1],
            ],
            'effects' => [

                ['tag' => 'skill', 'skill_id' => 28, 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 202,
            'name' => 'Explorador',
            'description' => 'No 3º nível, escolha um tipo de terreno entre aquático, ártico, colina, deserto, floresta, montanha, pântano, planície, subterrâneo ou urbano. A partir do 11º nível, você também pode escolher área de Tormenta. Quando estiver no tipo de terreno escolhido, você soma sua Sabedoria (mínimo +1) na Defesa e nos testes de Acrobacia, Atletismo, Furtividade, Percepção e Sobrevivência. A cada quatro níveis, escolha outro tipo de terreno para receber o bônus ou aumente o bônus em um tipo de terreno já escolhido em +2. <br><br>No APP, ative o poder quando estiver no terreno escolhido.',
            'source' => 'class_granted',

            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'explorador_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 3],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 'knw'],
                ['tag' => 'skill', 'skill_id' => 1, 'op' => 'add', 'value' => 'knw'],
                ['tag' => 'skill', 'skill_id' => 3, 'op' => 'add', 'value' => 'knw'],
                ['tag' => 'skill', 'skill_id' => 11, 'op' => 'add', 'value' => 'knw'],
                ['tag' => 'skill', 'skill_id' => 23, 'op' => 'add', 'value' => 'knw'],
                ['tag' => 'skill', 'skill_id' => 28, 'op' => 'add', 'value' => 'knw'],
            ],
        ]);

        Power::create([
            'id' => 203,
            'name' => 'Mestre Caçador',
            'description' => 'No 20º nível, você pode usar a habilidade Marca da Presa como uma ação livre. Além disso, quando usa a habilidade, pode pagar 5 PM para aumentar sua margem de ameaça contra a criatura em +2. Se você reduz uma criatura contra a qual usou Marca da Presa a 0 pontos de vida, recupera 5 PM. <br><br>No APP, adicione os PMs manualmente quando reduzir um inimigo a 0 PV.',
            'source' => 'class_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'mestre_cacador_01.webp',
            'pm_cost' => 5,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 20],
            ],
            'effects' => [
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 204,
            'name' => 'Armadilha: Arataca',
            'description' => 'A vítima sofre 2d6 pontos de dano de perfuração e fica agarrada. Uma criatura agarrada pode escapar com uma ação padrão e um teste de Força ou Acrobacia (CD Sab). <br><br>No APP, ativando, você gastará os PMs. O resto é com você!',
            'source' => 'class',

            'usability' => 'active',
            'icon_file_name' => 'armadilha_arataca_01.webp',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
        ]);

        Power::create([
            'id' => 205,
            'name' => 'Armadilha: Espinhos',
            'description' => 'A vítima sofre 6d6 pontos de dano de perfuração. Um teste de Reflexos (CD Sab) reduz o dano à metade.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'armadilha_espinhos_01.webp',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
        ]);

        Power::create([
            'id' => 206,
            'name' => 'Armadilha: Laço',
            'description' => 'A vítima deve fazer um teste de Reflexos (CD Sab). Se passar, fica caída. Se falhar, fica agarrada. Uma criatura agarrada pode se soltar com uma ação padrão e um teste de Força ou Acrobacia (CD Sab).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'armadilha_laco_01.webp',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
        ]);

        Power::create([
            'id' => 207,
            'name' => 'Armadilha: Rede',
            'description' => 'Todas as criaturas na área ficam enredadas e não podem sair da área. Uma vítima pode se libertar com uma ação padrão e um teste de Força ou Acrobacia (CD 25). Além disso, a área ocupada pela rede é considerada terreno difícil. Nesta armadilha você escolhe quantas criaturas precisam estar na área para ativá-la.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'armadilha_rede_01.webp',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
        ]);

        Power::create([
            'id' => 208,
            'name' => 'Armadilheiro',
            'description' => 'Você soma sua Sabedoria no dano e na CD de suas armadilhas (cumulativo).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'armadilheiro_01.webp',
            'prerequisites' => [

                ['type' => 'power', 'power_ids_any' => [204, 205, 206, 207]],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5],
            ],

        ]);

        Power::create([
            'id' => 209,
            'name' => 'Bote',
            'description' => 'Se estiver empunhando duas armas e fizer uma investida, você pode pagar 1 PM para fazer um ataque adicional com sua arma secundária.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'bote_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 77],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 6],
            ],

        ]);

        Power::create([
            'id' => 210,
            'name' => 'Camuflagem',
            'description' => 'Você pode gastar 2 PM para se esconder mesmo sem camuflagem ou cobertura disponível.',
            'source' => 'class',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'camuflagem_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 6],
            ],
            // TODO implement condition
        ]);

        Power::create([
            'id' => 211,
            'name' => 'Chuva de Lâminas',
            'description' => 'Uma vez por rodada, quando usa Ambidestria, você pode pagar 2 PM para fazer um ataque adicional com sua arma primária.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'chuva_de_laminas_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 4],
                ['type' => 'power', 'power_id' => 77],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 12],
            ],

        ]);

        Power::create([
            'id' => 212,
            'name' => 'Companheiro Animal',
            'description' => 'Você recebe um companheiro animal.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'companheiro_animal_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'car', 'min' => 1],
                ['type' => 'skill_trained', 'skill_id' => 2],
                ['type' => 'class', 'class_ids' => [2]],
            ],
            // TODO implement companions
        ]);

        Power::create([
            'id' => 213,
            'name' => 'Elo com a Natureza',
            'description' => 'Você soma sua Sabedoria em seu total de pontos de mana e aprende e pode lançar Caminhos da Natureza (atributo-chave Sabedoria).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'elo_com_a_natureza_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 3],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 'knw'],
            ],
            // TODO implement spells
        ]);

        Power::create([
            'id' => 214,
            'name' => 'Emboscar',
            'description' => 'Você pode gastar 2 PM para realizar uma ação padrão adicional em seu turno. Você só pode usar este poder na primeira rodada de um combate.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'emboscar_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'skill_trained', 'skill_id' => 11],
            ],

        ]);

        Power::create([
            'id' => 215,
            'name' => 'Empatia Selvagem',
            'description' => 'Você pode se comunicar com animais por meio de linguagem corporal e vocalizações. Você pode usar Adestramento com animais para mudar atitude e persuasão.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'empatia_selvagem_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],

        ]);

        Power::create([
            'id' => 216,
            'name' => 'Escaramuça',
            'description' => 'Quando se move 6m ou mais, você recebe +2 na Defesa e Reflexos e +1d8 nas rolagens de dano de ataques corpo a corpo e à distância em alcance curto até o início de seu próximo turno. Você não pode usar esta habilidade se estiver vestindo armadura pesada. <br><br>No APP, ative o poder quando se mover 6m ou mais! Os bônus serão adicionados.',
            'source' => 'class',

            'usability' => 'vessel',
            'icon_file_name' => 'escaramuca_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 2],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 6],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 228],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 229],
            ],
        ]);

        Power::create([
            'id' => 228,
            'name' => 'Escaramuça (Dano)',
            'description' => 'Bônus de dano de Escaramuça — quando se moveu 6m ou mais neste turno.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'escaramuca_01.webp',
            'effects' => [

                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d8', 'stack_group' => 'escaramuca_dmg'],
            ],
        ]);

        Power::create([
            'id' => 229,
            'name' => 'Escaramuça (Defesa)',
            'description' => 'Bônus de Defesa e Reflexos de Escaramuça — quando se moveu 6m ou mais, até o início do seu próximo turno.',
            'source' => 'power_granted',

            'usability' => 'active',
            'duration' => 'turn',
            'icon_file_name' => 'escaramuca_01.webp',
            'effects' => [

                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2, 'stack_group' => 'escaramuca_def'],
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2, 'stack_group' => 'escaramuca_reflexos'],
            ],
        ]);

        Power::create([
            'id' => 217,
            'name' => 'Escaramuça Superior',
            'description' => 'Quando usa Escaramuça, seus bônus aumentam para +5 na Defesa e Reflexos e +1d12 em rolagens de dano.',
            'source' => 'class',
            'usability' => 'vessel',
            'icon_file_name' => 'escaramuca_superior_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 216],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 12],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 230],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 231],
            ],
        ]);

        Power::create([
            'id' => 230,
            'name' => 'Escaramuça Superior (Dano)',
            'description' => 'Bônus de dano de Escaramuça Superior — quando se moveu 6m ou mais neste turno.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'escaramuca_superior_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d12', 'stack_group' => 'escaramuca_dmg'],
            ],
        ]);

        Power::create([
            'id' => 231,
            'name' => 'Escaramuça Superior (Defesa)',
            'description' => 'Bônus de Defesa e Reflexos de Escaramuça Superior — quando se moveu 6m ou mais, até o início do seu próximo turno.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'turn',
            'icon_file_name' => 'escaramuca_superior_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 5, 'stack_group' => 'escaramuca_def'],
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 5, 'stack_group' => 'escaramuca_reflexos'],
            ],
        ]);

        Power::create([
            'id' => 218,
            'name' => 'Ervas Curativas',
            'description' => 'Você pode gastar uma ação completa e uma quantidade de PM a sua escolha (limitado por sua Sabedoria) para aplicar ervas que curam ou desintoxicam em você ou num aliado adjacente. Para cada PM que gastar, cura 2d6 PV ou remove uma condição envenenado afetando o alvo. <br><br>No APP, remova os PMs manualmente. O alvo deve adicionar manualmente os PMs.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'ervas_curativas_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],

        ]);

        Power::create([
            'id' => 219,
            'name' => 'Inimigo de Animais',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Animal, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_animais_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'doubles_marca_da_presa_dice', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 220,
            'name' => 'Inimigo de Construtos',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Construto, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_construto_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'doubles_marca_da_presa_dice', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 221,
            'name' => 'Inimigo de Espíritos',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Espírito, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_espiritos_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'doubles_marca_da_presa_dice', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 222,
            'name' => 'Inimigo de Monstros',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Monstro, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_monstros_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'doubles_marca_da_presa_dice', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 223,
            'name' => 'Inimigo de Mortos-Vivos',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Morto-Vivo, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_mortos_vivos_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'doubles_marca_da_presa_dice', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 224,
            'name' => 'Inimigo de Humanoides',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Humanoide, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_humanoides_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'doubles_marca_da_presa_dice', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 225,
            'name' => 'Espreitar',
            'description' => 'Quando usa a habilidade Marca da Presa, você recebe um bônus de +1 em testes de perícia contra a criatura marcada. Esse bônus aumenta em +1 para cada PM adicional gasto na habilidade e também dobra com a habilidade Inimigo.',
            'source' => 'class',

            'usability' => 'vessel',
            'icon_file_name' => 'espreitar_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 226],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 227],
            ],
        ]);

        Power::create([
            'id' => 226,
            'name' => 'Espreitar (Combate)',
            'description' => 'Bônus de Espreitar aplicado automaticamente na rolagem de ataque (Luta/Pontaria) quando Marca da Presa (e/ou Inimigo) estão marcadas.',
            'source' => 'power_granted',

            'usability' => 'passive',
            'icon_file_name' => 'espreitar_01.webp',
        ]);

        Power::create([
            'id' => 227,
            'name' => 'Espreitar (Perícias)',
            'description' => 'Bônus de Espreitar em testes de perícia contra a criatura marcada por Marca da Presa (fora da rolagem de ataque). <br><br>No APP, adicione o bônus manualmente na rolagem das perícias. Luta e Pontaria são contabilizadas na tela de rolagem; Para as outras perícias a adição é manual.',
            'source' => 'power_granted',

            'usability' => 'roleplay',
            'icon_file_name' => 'espreitar_01.webp',
        ]);

        Power::create([
            'id' => 232,
            'name' => 'Olho do Falcão',
            'description' => 'Você pode usar a habilidade Marca da Presa em criaturas em alcance longo.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'olho_do_falcao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],

        ]);

        Power::create([
            'id' => 233,
            'name' => 'Ponto Fraco',
            'description' => 'Quando usa a habilidade Marca da Presa, seus ataques contra a criatura marcada recebem +2 na margem de ameaça. Esse bônus dobra com a habilidade Inimigo.',
            'source' => 'class',

            'usability' => 'passive',
            'icon_file_name' => 'ponto_fraco_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
        ]);

        Power::create([
            'id' => 234,
            'name' => 'Armadilha Alquímica',
            'description' => 'Quando prepara uma armadilha, você pode gastar uma dose de um preparado alquímico. Se fizer isso, as criaturas afetadas pela armadilha também sofrem os efeitos desse preparado automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'armadilha_alquimica_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'power', 'power_id' => 208],
            ],

        ]);

        Power::create([
            'id' => 235,
            'name' => 'Avanço do Predador',
            'description' => 'Uma vez por rodada, quando uma criatura marcada por sua Marca da Presa se afasta voluntariamente de você, você pode gastar 1 PM para se mover na direção dela (até o limite do seu deslocamento).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'avanco_do_predador_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 45],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 11],
            ],

        ]);

        Power::create([
            'id' => 236,
            'name' => 'Batedor Marcial',
            'description' => 'Você pode usar testes de Sobrevivência no lugar de testes de Guerra. Além disso, se passar em um teste para analisar terreno, além de quaisquer benefícios encontrados, na próxima vez que usar Marca da Presa nessa cena você recupera 1 PM. <br><br>No APP, adicione o 1PM manualmente quando passar no teste.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'batedor_marcial_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],

        ]);

        Power::create([
            'id' => 237,
            'name' => 'Curandeiro dos Ermos',
            'description' => 'Você pode usar Ervas Curativas como uma ação de movimento e os dados de cura dessa habilidade aumentam para d8.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'curandeiro_dos_ermos_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'power', 'power_id' => 218],
            ],

        ]);

        Power::create([
            'id' => 238,
            'name' => 'Elo com a Natureza Maior',
            'description' => 'Escolha uma magia entre Abençoar Alimentos, Acalmar Animal, Alarme, Aviso, Conjurar Armadilhas, Detectar Ameaças, Orientação ou Suporte Ambiental. Você aprende e pode lançar as magias escolhidas (atributo-chave Sabedoria) e pode usar seus aprimoramentos como se tivesse acesso aos mesmos círculos de magia que um druida do seu nível. Você pode escolher este poder mais vezes para magias diferentes.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'elo_com_a_natureza_maior_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'power', 'power_id' => 213],
            ],

        ]);

        Power::create([
            'id' => 239,
            'name' => 'Flecheiro',
            'description' => 'Você pode usar Sobrevivência no lugar de Ofício para fabricar munições e pode fabricar munições com uma melhoria.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'flecheiro_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 3],
            ],
            'effects' => [

                ['tag' => 'allow_improve_ammo', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 240,
            'name' => 'Golpe do Predador',
            'description' => 'Se você causar dano em uma criatura analisada por sua Marca da Presa, ela fica sangrando. Se ela já estiver sangrando, a perda de vida por sangramento aumenta em um passo (cumulativo até um máximo de d12) e ela falha automaticamente em seu próximo teste de Constituição para remover essa condição.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'golpe_do_predador_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],
            'effects' => [

                ['tag' => 'on_marca_da_presa_hit', 'op' => 'inflict', 'condition_id' => 1],
            ],
        ]);

        Power::create([
            'id' => 241,
            'name' => 'Herói do Povo',
            'description' => 'Você recebe +2 na Defesa e em testes de resistência. Além disso, sempre que acertar um ataque em um vilão que esteja ameaçando pessoas comuns (um bandido assolando camponeses, um nobre tirano, um monstro devorando viajantes...), você recebe 2 PM temporários. Você pode receber um número máximo de PM temporários por cena igual ao seu nível e eles desaparecem no fim da cena.',
            'source' => 'class',

            'usability' => 'vessel',
            'icon_file_name' => 'heroi_do_povo_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 242],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 243],
            ],
        ]);

        Power::create([
            'id' => 242,
            'name' => 'Herói do Povo (Passiva)',
            'description' => 'Bônus de Defesa e testes de resistência de Herói do Povo.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'heroi_do_povo_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 10, 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 29, 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 243,
            'name' => 'Herói do Povo (PM Temporário)',
            'description' => 'PM temporário de Herói do Povo — ao acertar um ataque em um vilão ameaçando pessoas comuns. <br><br>No APP, adicione manualmente os PMs temporários, até o limite definido pelo poder, seguindo as regras.',
            'source' => 'power_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'heroi_do_povo_01.webp',
            'effects' => [
                ['tag' => 'temp_pm', 'op' => 'add', 'value' => 2, 'limit' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 244,
            'name' => 'Identificar Presas',
            'description' => 'Você pode identificar criaturas como uma ação de movimento. Além disso, se passar nesse teste, para cada informação obtida você recebe +1 em rolagens de dano contra criaturas dessa espécie até o fim da cena.<br><br>No APP, some manualmente o bônus ao dano rolado e declare ao mestre.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'identificar_presas_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],

        ]);

        Power::create([
            'id' => 245,
            'name' => 'Lâminas Guardiãs',
            'description' => 'Enquanto você estiver empunhando duas armas corpo a corpo, recebe +2 na Defesa e em testes de resistência contra inimigos em seu alcance natural. <br><br>No APP, ative o poder quando estiver empunhando duas armas corpo a corpo.',
            'source' => 'class',

            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'laminas_guardias_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 77],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 10, 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 29, 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 246,
            'name' => 'Caminho do Explorador',
            'description' => 'No 5º nível, você pode atravessar terrenos difíceis sem sofrer redução em seu deslocamento e a CD para rastrear você aumenta em +10. Esta habilidade só funciona em terrenos nos quais você tenha a habilidade Explorador.',
            'source' => 'class_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'caminhos_do_explorador_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5],
            ],

        ]);

        Power::create([
            'id' => 247,
            'name' => 'Lanceiro',
            'description' => 'Você recebe +2 em testes de ataque e rolagens de dano com lanças (exceto lanças montadas e de justa). Além disso, se estiver empunhando uma dessas armas com as duas mãos, seu dano com ela aumenta em um passo e ela é considerada uma arma alongada.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'lanceiro_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],

            // TODO implement all lanças then add the effect
        ]);

        Power::create([
            'id' => 248,
            'name' => 'Pega!',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para fazer a manobra agarrar contra uma criatura em alcance curto usando Adestramento em vez de Luta, usando seu companheiro animal para isso. Se o alvo estiver sob efeito de sua Marca da Presa, você soma os dados dessa habilidade como um bônus no teste. A criatura permanece agarrada até vencer um teste de manobra contra seu companheiro animal (como acima) ou até você mandar seu animal soltá-la (uma ação livre). <br><br>No APP, faça a rolagem da perícia e as adições de dados manualmente.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'pega_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'power', 'power_id' => 212],
            ],

        ]);

        Power::create([
            'id' => 249,
            'name' => 'Primeiro Sangue',
            'description' => 'Na primeira rodada de cada combate, você recebe +2 em testes de ataque e todos os seus dados de dano aumentam em dois passos.',
            'source' => 'class',

            'usability' => 'roll_active',
            'icon_file_name' => 'primeiro_sangue_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'power', 'power_id' => 214],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'all_die_step_increase', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 250,
            'name' => 'Sequência Dilaceradora',
            'description' => 'Quando usa Ambidestria, se acertar ambos os ataques, você pode gastar 1 PM para causar +2d8 pontos de dano no segundo ataque.',
            'source' => 'class',

            'usability' => 'roll_active',
            'icon_file_name' => 'sequencia_dilaceradora_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 77],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 11],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d8'],
            ],
        ]);

        Power::create([
            'id' => 251,
            'name' => 'Sequência do Predador',
            'description' => 'Quando usa Ambidestria com armas que causam tipos de dano diferentes, se acertar ambos os ataques, você pode gastar 1 PM para fazer uma manobra entre desarmar, derrubar ou quebrar contra o mesmo alvo. <br><br>No APP, role a manobra manualmente. Ativando o poder aqui gastará seu 1PM.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'sequencia_do_predador_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 77],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 8],
            ],

        ]);

        Power::create([
            'id' => 252,
            'name' => 'Sombra dos Ermos',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para receber camuflagem leve com duração sustentada.',
            'source' => 'class',

            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'sombra_dos_ermos_01.webp',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'power', 'power_id' => 210],
            ],
            // TODO implement condition camuflagem
        ]);

        Power::create([
            'id' => 254,
            'name' => 'Tiro de Abate',
            'description' => 'Quando usa a ação mirar, até o fim do turno você recebe +2 em testes de ataque e na margem de ameaça com ataques à distância, e os dados extras de sua habilidade Marca da Presa também são multiplicados em caso de acerto crítico.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'tiro_de_abate_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
                ['type' => 'power', 'power_id' => 225],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 255,
            'name' => 'Tiro Trespassante',
            'description' => 'Quando você faz um ataque à distância com uma arma de disparo e reduz os pontos de vida do alvo a 0 ou menos, pode gastar 1 PM para fazer um ataque adicional contra outra criatura que esteja adiante na mesma linha, usando a mesma arma e munição do ataque original.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'tiro_trespassante_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
                ['type' => 'power', 'power_id' => 78],
            ],

        ]);

        Power::create([
            'id' => 256,
            'name' => 'Tempestade de Lâminas',
            'description' => 'Quando usa Chuva de Lâminas, você pode fazer um ataque adicional com sua arma secundária (para um total de quatro ataques na ação).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'tempestade_de_laminas_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 211],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 17],
            ],

        ]);

        Power::create([
            'id' => 257,
            'name' => 'Tocaia Habilidosa',
            'description' => 'Sua Marca da Presa também fornece +1 na CD de suas habilidades contra a criatura marcada para cada PM gasto. Esse bônus dobra com a habilidade Inimigo.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'tocaia_habilidosa_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]],
            ],

        ]);

        Power::create([
            'id' => 258,
            'name' => 'Último Sangue',
            'description' => 'Seus ataques contra criaturas sangrando causam um dado extra de dano do mesmo tipo. <br><br>No APP, confirme o dado de sangramento manualmente com o mestre.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'ultimo_sangue_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5],
            ],

        ]);
    }
}
