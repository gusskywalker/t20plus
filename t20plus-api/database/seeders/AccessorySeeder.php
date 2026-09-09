<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{

    public function run(): void
    {

        Accessory::create([
            'id' => 1,
            'name' => 'Símbolo Sagrado',
            'description' => 'Um medalhão de madeira ou metal com o símbolo de uma divindade. Se você estiver vestindo (normalmente com uma corrente ao redor do pescoço) ou empunhando o símbolo sagrado de um deus do qual é devoto, recebe +1 em testes de resistência.',
            'cost' => 5,
            'slots' => 1,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 1],
            ],
            'mp_cost' => 0,
            'icon_file_name' => 'simbolo_sagrado_01.webp',
        ]);
    }
}
