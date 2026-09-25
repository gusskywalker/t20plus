<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 8000 and 8999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class ConsumableGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 349,
            'name' => 'Cosmético',
            'description' => 'Aplicar um cosmético é uma ação completa e fornece +2 em testes de perícias baseadas em Carisma até o fim da cena.',
            'source' => 'consumable_granted',
            'usability' => 'active',
            'icon_file_name' => 'cosmetico_01.webp',
            'action_cost' => 'complete',
            'duration' => 'scene',
            'effects' => [
                ['tag' => 'skill_group', 'op' => 'add', 'attribute' => 'car', 'value' => 2],
            ],
        ]);
    }
}
