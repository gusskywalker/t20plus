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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
                ['type' => 'class', 'class_ids' => [1, 2]],
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
    }
}
