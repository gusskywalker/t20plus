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
    }
}
