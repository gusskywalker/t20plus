<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 16000 and 16999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class RaceGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 271,
            'name' => 'Entre Dois Mundos',
            'description' => 'Você recebe +1 em perícias baseadas em Carisma.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'entre_dois_mundos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [22]],
            ],
            'effects' => [
                ['tag' => 'skill_group', 'op' => 'add', 'attribute' => 'car', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 272,
            'name' => 'Sangue Élfico',
            'description' => 'Você recebe visão na penumbra e +1 ponto de mana a cada nível ímpar (incluindo o 1º). Além disso, é considerado um elfo para efeitos relacionados a raça.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sangue_elfico_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [22]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add_per_level', 'value' => 1, 'per_levels' => 2],
            ],
        ]);

        Power::create([
            'id' => 273,
            'name' => 'Ambição Herdada',
            'description' => 'Você recebe um poder geral ou poder único de origem a sua escolha.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'ambicao_herdada_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [22]],
            ],
        ]);

        Power::create([
            'id' => 345,
            'name' => 'Multiplicidade',
            'description' => 'Você é capaz de assumir diferentes formas e alterar a sua própria aparência. Isso inclui altura, peso, tom de pele, cor de cabelo, timbre de voz etc. Você não recebe novas habilidades (você pode ficar parecido com outra raça, mas não ganhará as habilidades dela), nem é capaz de alterar a aparência dos equipamentos que esteja vestindo ou carregando. Você recebe +2 em Diplomacia e Enganação.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'multiplicidade_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [56]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2, 'stack_group' => 'duplo_social'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 2, 'stack_group' => 'duplo_social'],
            ],
        ]);

        Power::create([
            'id' => 346,
            'name' => 'Arte do Disfarce',
            'description' => 'Você pode alterar-se para formas específicas a fim de conseguir vantagens pontuais, como impressionar alguém, assumir outra identidade ou se mesclar na multidão. Você pode gastar 2 PM para receber +10 em testes de Diplomacia para mudar atitude, em testes de Enganação para disfarce ou testes de Furtividade para esconder-se até o final da cena (esse bônus não se acumula com o bônus oferecido pelo poder Multiplicidade).',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'pm_cost' => 2,
            'icon_file_name' => 'arte_do_disfarce_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [56]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 10, 'stack_group' => 'duplo_social'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 10, 'stack_group' => 'duplo_social'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 347,
            'name' => 'Natureza Flexível',
            'description' => 'Você recebe +5 em testes de resistência contra efeitos de paralisia.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'natureza_flexivel_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [56]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5],
            ],
        ]);
    }
}
