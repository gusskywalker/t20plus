<?php

namespace Database\Seeders;

use App\Models\Weapon;
use Illuminate\Database\Seeder;

class NaturalWeaponsSeeder extends Seeder
{
    //This file must use IDs between 1000 and 1999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
    public function run(): void
    {
        Weapon::create([
            'id' => 1000,
            'name' => 'Chifres',
            'description' => 'Arma natural de chifres.',
            'cost' => -1,
            'purpose' => 'melee',
            'grip' => 'natural',
            'base_dmg' => '1d6',
            'base_margin' => 20,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'slots' => 0,
            'icon_file_name' => null,
        ]);
    }
}
