<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skillIds = Skill::pluck('id', 'name');

        Accessory::create([
            'name' => 'Símbolo Sagrado',
            'description' => 'Um medalhão de madeira ou metal com o símbolo de uma divindade. Se você estiver vestindo (normalmente com uma corrente ao redor do pescoço) ou empunhando o símbolo sagrado de um deus do qual é devoto, recebe +1 em testes de resistência.',
            'slots' => 1,
            'effects' => [
                ['tag' => 'skill', 'skill_id' => $skillIds['Reflexos'], 'op' => 'add', 'value' => 1],
                ['tag' => 'skill', 'skill_id' => $skillIds['Fortitude'], 'op' => 'add', 'value' => 1],
                ['tag' => 'skill', 'skill_id' => $skillIds['Vontade'], 'op' => 'add', 'value' => 1],
            ],
            'mp_cost' => 0,
        ]);
    }
}
