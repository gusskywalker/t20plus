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

        // 'id' is hardcoded so other seeders/files can reference it directly
        // instead of looking it up.
        Accessory::create([
            'id' => 1,
            'name' => 'Símbolo Sagrado',
            'description' => 'Um medalhão de madeira ou metal com o símbolo de uma divindade. Se você estiver vestindo (normalmente com uma corrente ao redor do pescoço) ou empunhando o símbolo sagrado de um deus do qual é devoto, recebe +1 em testes de resistência.',
            'slots' => 1,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => $skillIds['Reflexos'], 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => $skillIds['Fortitude'], 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => $skillIds['Vontade'], 'value' => 1],
            ],
            'mp_cost' => 0,
        ]);
    }
}
