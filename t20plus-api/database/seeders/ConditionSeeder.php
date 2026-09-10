<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{

    public function run(): void
    {

        Condition::create([
            'id' => 1,
            'name' => 'Sangrando',
            'description' => 'Você está sangrando. Efeito de metabolismo. No início de seu turno, o personagem deve fazer um teste de Constituição (CD 15). Se falhar, perde 1d6 pontos de vida e continua sangrando. Se passar, remove essa condição.',
            'type' => 'metabolism',
        ]);

        Condition::create([
            'id' => 2,
            'name' => 'Atordoado',
            'description' => 'Você está atordoado. Efeito mental. O personagem fica desprevenido (sofre –5 na Defesa e em Reflexos, contra inimigos que não possa perceber) e não pode fazer ações.',
            'type' => 'mental',
        ]);

        Condition::create([
            'id' => 3,
            'name' => 'Desprevenido',
            'description' => 'Você está desprevenido. O personagem sofre –5 na Defesa e em Reflexos. Um personagem fica desprevenido contra inimigos que não possa perceber.',
            'type' => null,
        ]);

        Condition::create([
            'id' => 4,
            'name' => 'Ofuscado',
            'description' => 'Você está ofuscado. Efeito nos sentidos. O personagem sofre –2 em testes de ataque e de Percepção.',
            'type' => 'senses',
        ]);

        Condition::create([
            'id' => 5,
            'name' => 'Vulnerável',
            'description' => 'Você está vulnerável. O personagem sofre –2 na Defesa.',
            'type' => null,
        ]);
    }
}
