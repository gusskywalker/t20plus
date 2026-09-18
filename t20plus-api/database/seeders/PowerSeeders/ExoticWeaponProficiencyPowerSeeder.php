<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 19000 and 19999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class ExoticWeaponProficiencyPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 44,
            'name' => 'Proficiência - Arco de Guerra',
            'description' => 'Você recebe proficiência em arcos de guerra.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_arco_de_guerra_01.webp',
        ]);

        Power::create([
            'id' => 268,
            'name' => 'Proficiência - Pistola-Tambor',
            'description' => 'Você recebe proficiência em pistolas-tambor.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'pistola_tambor_01.webp',
        ]);

        Power::create([
            'id' => 19000,
            'name' => 'Proficiência - Arpão',
            'description' => 'Você recebe proficiência em arpões.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19001,
            'name' => 'Proficiência - Rede',
            'description' => 'Você recebe proficiência em redes.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);
    }
}
