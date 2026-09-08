<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ConsumableGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 73,
            'name' => 'Essência de Mana',
            'description' => 'Beber a essência de mana é uma ação padrão e recupera 1d4 pontos de mana.',
            'source' => 'consumable_granted',
            'usability' => 'active',
            'icon_file_name' => 'essencia_de_mana_01.webp',
            'action_cost' => 'standard',
            'effects' => [
                ['tag' => 'restore_pm', 'op' => 'roll', 'value' => '1d4'],
                ['tag' => 'reduce_qty', 'op' => 'add', 'value' => -1],
            ],
        ]);    }
}
