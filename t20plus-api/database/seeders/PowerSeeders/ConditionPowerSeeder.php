<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ConditionPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 331,
            'name' => 'Sangrando',
            'description' => 'Você está sangrando. Efeito de metabolismo. No início de seu turno, o personagem deve fazer um teste de Constituição (CD 15). Se falhar, perde 1d6 pontos de vida e continua sangrando. Se passar, remove essa condição.',
            'source' => 'condition_granted',
            'usability' => 'roleplay',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 332,
            'name' => 'Atordoado',
            'description' => 'Você está atordoado. Efeito mental. O personagem fica desprevenido (sofre –5 na Defesa e em Reflexos, contra inimigos que não possa perceber) e não pode fazer ações.',
            'source' => 'condition_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => -5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 333,
            'name' => 'Desprevenido',
            'description' => 'Você está desprevenido. O personagem sofre –5 na Defesa e em Reflexos. Um personagem fica desprevenido contra inimigos que não possa perceber.',
            'source' => 'condition_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => -5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 334,
            'name' => 'Ofuscado',
            'description' => 'Você está ofuscado. Efeito nos sentidos. O personagem sofre –2 em testes de ataque e de Percepção.',
            'source' => 'condition_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 335,
            'name' => 'Vulnerável',
            'description' => 'Você está vulnerável. O personagem sofre –2 na Defesa.',
            'source' => 'condition_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => -2],
            ],
        ]);
    }
}
