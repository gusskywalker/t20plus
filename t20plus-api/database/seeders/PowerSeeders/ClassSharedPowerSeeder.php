<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 5000 and 5999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class ClassSharedPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 45,
            'name' => 'Ímpeto',
            'description' => 'Você pode gastar 1 PM para aumentar seu deslocamento em +6m por uma rodada.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'impeto_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]],
            ],
        ]);

        Power::create([
            'id' => 46,
            'name' => 'Aumentar Atributo (Força)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Força em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
            ],
        ]);

        Power::create([
            'id' => 47,
            'name' => 'Aumentar Atributo (Força)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Força em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 46],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        Power::create([
            'id' => 48,
            'name' => 'Aumentar Atributo (Força)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Força em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 47],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        Power::create([
            'id' => 49,
            'name' => 'Aumentar Atributo (Força)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Força em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 48],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        Power::create([
            'id' => 50,
            'name' => 'Aumentar Atributo (Destreza)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Destreza em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_destreza_01.webp',
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
            ],
        ]);

        Power::create([
            'id' => 51,
            'name' => 'Aumentar Atributo (Destreza)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Destreza em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_destreza_01.webp',
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 50],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        Power::create([
            'id' => 52,
            'name' => 'Aumentar Atributo (Destreza)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Destreza em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_destreza_01.webp',
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 51],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        Power::create([
            'id' => 53,
            'name' => 'Aumentar Atributo (Destreza)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Destreza em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_destreza_01.webp',
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 52],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        Power::create([
            'id' => 54,
            'name' => 'Aumentar Atributo (Constituição)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Constituição em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_con_01.webp',
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
            ],
        ]);

        Power::create([
            'id' => 55,
            'name' => 'Aumentar Atributo (Constituição)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Constituição em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_con_01.webp',
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 54],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        Power::create([
            'id' => 56,
            'name' => 'Aumentar Atributo (Constituição)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Constituição em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_con_01.webp',
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 55],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        Power::create([
            'id' => 57,
            'name' => 'Aumentar Atributo (Constituição)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Constituição em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_con_01.webp',
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 56],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        Power::create([
            'id' => 58,
            'name' => 'Aumentar Atributo (Inteligência)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Inteligência em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_int_01.webp',
            'effects' => [
                ['tag' => 'mod_base_int', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
            ],
        ]);

        Power::create([
            'id' => 59,
            'name' => 'Aumentar Atributo (Inteligência)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Inteligência em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_int_01.webp',
            'effects' => [
                ['tag' => 'mod_base_int', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 58],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        Power::create([
            'id' => 60,
            'name' => 'Aumentar Atributo (Inteligência)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Inteligência em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_int_01.webp',
            'effects' => [
                ['tag' => 'mod_base_int', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 59],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        Power::create([
            'id' => 61,
            'name' => 'Aumentar Atributo (Inteligência)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Inteligência em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_int_01.webp',
            'effects' => [
                ['tag' => 'mod_base_int', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 60],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        Power::create([
            'id' => 62,
            'name' => 'Aumentar Atributo (Sabedoria)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Sabedoria em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_sabedoria_01.webp',
            'effects' => [
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
            ],
        ]);

        Power::create([
            'id' => 63,
            'name' => 'Aumentar Atributo (Sabedoria)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Sabedoria em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_sabedoria_01.webp',
            'effects' => [
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 62],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        Power::create([
            'id' => 64,
            'name' => 'Aumentar Atributo (Sabedoria)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Sabedoria em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_sabedoria_01.webp',
            'effects' => [
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 63],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        Power::create([
            'id' => 65,
            'name' => 'Aumentar Atributo (Sabedoria)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Sabedoria em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_sabedoria_01.webp',
            'effects' => [
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 64],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        Power::create([
            'id' => 66,
            'name' => 'Aumentar Atributo (Carisma)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar seu Carisma em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_carisma_01.webp',
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
            ],
        ]);

        Power::create([
            'id' => 67,
            'name' => 'Aumentar Atributo (Carisma)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar seu Carisma em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_carisma_01.webp',
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 66],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        Power::create([
            'id' => 68,
            'name' => 'Aumentar Atributo (Carisma)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar seu Carisma em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_carisma_01.webp',
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 67],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        Power::create([
            'id' => 69,
            'name' => 'Aumentar Atributo (Carisma)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar seu Carisma em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_carisma_01.webp',
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 68],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        Power::create([
            'id' => 77,
            'name' => 'Ambidestria',
            'description' => 'Se estiver empunhando duas armas e fizer a ação agredir, você pode fazer dois ataques, um com cada arma. Se fizer isso, sofre –2 em todos os testes de ataque até o seu próximo turno.',
            'source' => 'class',
            'usability' => 'roll_active',
            'default_checked' => true,
            'icon_file_name' => 'ambidestria_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]],
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 2],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 78,
            'name' => 'Arqueiro',
            'description' => 'Se estiver usando uma arma de ataque à distância, você soma sua Sabedoria em rolagens de dano (limitado pelo seu nível).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'arqueiro_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]],
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
            ],

            'applies_when' => ['weapon_purpose' => ['thrown', 'fired']],
            'effects' => [

                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 'knw', 'limit' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 5000,
            'name' => 'Adaptação Elemental',
            'description' => 'Quando sofre dano de ácido, eletricidade, fogo, frio, luz ou trevas, você pode gastar 2 PM para receber redução 10 contra esse tipo de dano até o fim da cena. <br><br>Reduza manualmente o dano recebido pelo elemento escolhido.',
            'source' => 'class',
            'usability' => 'active',
            'pm_cost' => 2,
            'icon_file_name' => 'adaptacao_elemental_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
            ],
        ]);

        Power::create([
            'id' => 5001,
            'name' => 'Arma Acoplada',
            'description' => 'Você possui uma arma acoplada ao seu corpo. Ela fica recolhida em um compartimento, pode ser empunhada ou guardada com uma ação livre e não pode ser desarmada. Um personagem treinado em Ofício (artesão) pode substituir essa arma com uma hora de trabalho e o gasto de T$ 100.',
            'source' => 'class',
            'usability' => 'roleplay',
            'icon_file_name' => 'arma_acoplada_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
            ],
        ]);

        Power::create([
            'id' => 5002,
            'name' => 'Arma Elemental (Água)',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para fazer uma arma que esteja empunhando causar +1d6 pontos de dano de frio até o fim da cena. Pré-requisito: Fonte de Energia (Elemental - Água).',
            'source' => 'class',
            'usability' => 'item_enhancer',
            'action_cost' => 'movement',
            'pm_cost' => 2,
            'icon_file_name' => 'arma_elemental_agua_01.webp',
            'applies_when' => ['categories' => ['weapon']],
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'power', 'power_id' => 16236],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'cold'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 5003,
            'name' => 'Auxílio de Mira',
            'description' => 'Quando faz um ataque à distância, você pode pagar 1 PM para aumentar em +2 a margem de ameaça desse ataque.',
            'source' => 'class',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => 'auxilio_de_mira_01.webp',
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
            ],
            'effects' => [
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);
        Power::create([
            'id' => 5004,
            'name' => 'Caminho de Perfeição (Acrobacia)',
            'description' => 'Você recebe +2 em Acrobacia.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 1],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 1, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5005,
            'name' => 'Caminho de Perfeição (Adestramento)',
            'description' => 'Você recebe +2 em Adestramento.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 2],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 2, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5006,
            'name' => 'Caminho de Perfeição (Atletismo)',
            'description' => 'Você recebe +2 em Atletismo.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 3],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5007,
            'name' => 'Caminho de Perfeição (Atuação)',
            'description' => 'Você recebe +2 em Atuação.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 4],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 4, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5008,
            'name' => 'Caminho de Perfeição (Cavalgar)',
            'description' => 'Você recebe +2 em Cavalgar.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 5],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 5, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5009,
            'name' => 'Caminho de Perfeição (Conhecimento)',
            'description' => 'Você recebe +2 em Conhecimento.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 6],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 6, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5010,
            'name' => 'Caminho de Perfeição (Cura)',
            'description' => 'Você recebe +2 em Cura.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 7],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 7, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5011,
            'name' => 'Caminho de Perfeição (Diplomacia)',
            'description' => 'Você recebe +2 em Diplomacia.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 8],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5012,
            'name' => 'Caminho de Perfeição (Enganação)',
            'description' => 'Você recebe +2 em Enganação.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 9],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5013,
            'name' => 'Caminho de Perfeição (Fortitude)',
            'description' => 'Você recebe +2 em Fortitude.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 10],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5014,
            'name' => 'Caminho de Perfeição (Furtividade)',
            'description' => 'Você recebe +2 em Furtividade.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 11],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5015,
            'name' => 'Caminho de Perfeição (Guerra)',
            'description' => 'Você recebe +2 em Guerra.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 12],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 12, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5016,
            'name' => 'Caminho de Perfeição (Iniciativa)',
            'description' => 'Você recebe +2 em Iniciativa.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 13],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5017,
            'name' => 'Caminho de Perfeição (Intimidação)',
            'description' => 'Você recebe +2 em Intimidação.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 14],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5018,
            'name' => 'Caminho de Perfeição (Investigação)',
            'description' => 'Você recebe +2 em Investigação.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 15],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5019,
            'name' => 'Caminho de Perfeição (Intuição)',
            'description' => 'Você recebe +2 em Intuição.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 16],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5020,
            'name' => 'Caminho de Perfeição (Jogatina)',
            'description' => 'Você recebe +2 em Jogatina.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 17],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 17, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5021,
            'name' => 'Caminho de Perfeição (Ladinagem)',
            'description' => 'Você recebe +2 em Ladinagem.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 18],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 18, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5022,
            'name' => 'Caminho de Perfeição (Luta)',
            'description' => 'Você recebe +2 em Luta.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 19],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5023,
            'name' => 'Caminho de Perfeição (Misticismo)',
            'description' => 'Você recebe +2 em Misticismo.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 20],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5024,
            'name' => 'Caminho de Perfeição (Nobreza)',
            'description' => 'Você recebe +2 em Nobreza.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 21],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5025,
            'name' => 'Caminho de Perfeição (Ofício)',
            'description' => 'Você recebe +2 em Ofício.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 22],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 22, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5026,
            'name' => 'Caminho de Perfeição (Percepção)',
            'description' => 'Você recebe +2 em Percepção.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 23],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5027,
            'name' => 'Caminho de Perfeição (Pilotagem)',
            'description' => 'Você recebe +2 em Pilotagem.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 24],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 24, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5028,
            'name' => 'Caminho de Perfeição (Pontaria)',
            'description' => 'Você recebe +2 em Pontaria.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 25],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 25, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5029,
            'name' => 'Caminho de Perfeição (Reflexos)',
            'description' => 'Você recebe +2 em Reflexos.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 26],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5030,
            'name' => 'Caminho de Perfeição (Religião)',
            'description' => 'Você recebe +2 em Religião.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 27],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 27, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5031,
            'name' => 'Caminho de Perfeição (Sobrevivência)',
            'description' => 'Você recebe +2 em Sobrevivência.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 28],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5032,
            'name' => 'Caminho de Perfeição (Vontade)',
            'description' => 'Você recebe +2 em Vontade.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caminho_de_perfeicao_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'skill_trained', 'skill_id' => 29],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 5033,
            'name' => 'Canalizar Reparos',
            'description' => 'Como uma ação completa, você pode gastar pontos de mana para recuperar pontos de vida, à taxa de 5 PV por PM.',
            'source' => 'class',
            'usability' => 'active',
            'action_cost' => 'complete',
            'pm_cost' => 1,
            'icon_file_name' => 'canalizar_reparos_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
            ],
            'effects' => [
                ['tag' => 'restore_pv', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 5034,
            'name' => 'Canhão Energético',
            'description' => 'Se sua arma acoplada for uma arma de fogo, você pode gastar uma ação de movimento e 1 PM para energizá-la. Até o final da cena, seu próximo ataque com ela causa +1 dado de dano. Múltiplos usos desse poder são cumulativos (limitado por sua Constituição). <br><br>No APP, adicione manualmente os dados extras ao seu dano informado na tela de rolagens de dano.',
            'source' => 'class',
            'usability' => 'active',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => 'canhao_energetico_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'power', 'power_id' => 5001],
            ],
        ]);

        Power::create([
            'id' => 5035,
            'name' => 'Dínamo de Mana',
            'description' => 'Escolha uma de suas habilidades com um custo em PM. Você pode gastar uma ação de movimento para canalizar seu mana. Quando faz isso, até o final do seu turno, o custo do próximo uso dessa habilidade escolhida é reduzido em –1 PM. Um personagem treinado em Ofício (artesão) pode substituir essa habilidade com uma hora de trabalho e o gasto de T$ 100. <br><br>No APP, adicione o seu PM de volta manualmente.',
            'source' => 'class',
            'usability' => 'roleplay',
            'icon_file_name' => 'dinamo_de_mana_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
            ],
        ]);

        Power::create([
            'id' => 5036,
            'name' => 'Pernas Aprimoradas',
            'description' => 'Você pode gastar 2 PM para receber +6m em seu deslocamento e +5 em Atletismo até o fim da cena.',
            'source' => 'class',
            'usability' => 'active',
            'duration' => 'scene',
            'pm_cost' => 2,
            'icon_file_name' => 'pernas_aprimoradas_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'add', 'value' => 6],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 5037,
            'name' => 'Reservatório Alquímico',
            'description' => 'Você possui um reservatório em seu corpo que pode armazenar até duas doses de preparados alquímicos. Uma vez por rodada, você pode usar um desses preparados ou pode consumí-lo para sua fonte de energia. Carregar seu reservatório exige uma ação completa e o gasto dos itens com os quais você quiser carregá-lo. Pré-requisito: Fonte de Energia (alquímica).',
            'source' => 'class',
            'usability' => 'roleplay',
            'icon_file_name' => 'reservatorio_alquimico_01.webp',
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'power', 'power_id' => 16235],
            ],
        ]);

        Power::create([
            'id' => 5038,
            'name' => 'Arma Elemental (Ar)',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para fazer uma arma que esteja empunhando causar +1d6 pontos de dano de eletricidade até o fim da cena. Pré-requisito: Fonte de Energia (Elemental - Ar).',
            'source' => 'class',
            'usability' => 'item_enhancer',
            'action_cost' => 'movement',
            'pm_cost' => 2,
            'icon_file_name' => 'arma_elemental_ar_01.webp',
            'applies_when' => ['categories' => ['weapon']],
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'power', 'power_id' => 16237],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'electricity'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 5039,
            'name' => 'Arma Elemental (Fogo)',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para fazer uma arma que esteja empunhando causar +1d6 pontos de dano de fogo até o fim da cena. Pré-requisito: Fonte de Energia (Elemental - Fogo).',
            'source' => 'class',
            'usability' => 'item_enhancer',
            'action_cost' => 'movement',
            'pm_cost' => 2,
            'icon_file_name' => 'arma_elemental_fogo_01.webp',
            'applies_when' => ['categories' => ['weapon']],
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'power', 'power_id' => 16238],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'fire'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 5040,
            'name' => 'Arma Elemental (Terra)',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para fazer uma arma que esteja empunhando causar +1d6 pontos de dano de ácido até o fim da cena. Pré-requisito: Fonte de Energia (Elemental - Terra).',
            'source' => 'class',
            'usability' => 'item_enhancer',
            'action_cost' => 'movement',
            'pm_cost' => 2,
            'icon_file_name' => 'arma_elemental_terra_01.webp',
            'applies_when' => ['categories' => ['weapon']],
            //TODO add all class ids once they are seeded
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2, 3]],
                ['type' => 'power', 'power_id' => 16231],
                ['type' => 'power', 'power_id' => 16239],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'acid'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);
    }
}
