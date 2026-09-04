<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded so other seeders/files can reference it directly
        // instead of looking it up. Same for the skill ids below (see
        // SkillSeeder.php for what each id is).
        Accessory::create([
            'id' => 1,
            'name' => 'Símbolo Sagrado',
            'description' => 'Um medalhão de madeira ou metal com o símbolo de uma divindade. Se você estiver vestindo (normalmente com uma corrente ao redor do pescoço) ou empunhando o símbolo sagrado de um deus do qual é devoto, recebe +1 em testes de resistência.',
            'cost' => -1, // not purchasable — devotion item from a specific source
            'slots' => 1,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 1], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 1], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 1], // Vontade
            ],
            'mp_cost' => 0,
            'icon_file_name' => 'items/accessories_01.webp', // placeholder, same for every accessory for now
        ]);
    }
}
