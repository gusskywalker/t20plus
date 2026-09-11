<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 17000 and 17999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class RaceOptionalPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 320,
            'name' => 'Arquearia Élfica',
            'description' => 'Para você, todos os arcos são armas simples. Além disso, você recebe +2 nas rolagens de dano com arcos.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => 'arquearia_elfica_01.webp',
            'applies_when' => ['weapon_ids' => [5, 6]],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [7, 22]],
            ],
            'effects' => [
                ['tag' => 'waive_weapon_proficiency', 'op' => 'grant', 'weapon_ids' => [5]],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);
    }
}
