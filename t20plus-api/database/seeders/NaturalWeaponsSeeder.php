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
            'icon_file_name' => 'chifre_01.webp',
        ]);

        Weapon::create([
            'id' => 1001,
            'name' => 'Mordida',
            'description' => 'Arma natural de mordida.',
            'cost' => -1,
            'purpose' => 'melee',
            'grip' => 'natural',
            'base_dmg' => '1d6',
            'base_margin' => 20,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'slots' => 0,
            'icon_file_name' => 'mordida_01.webp',
        ]);

        Weapon::create([
            'id' => 1002,
            'name' => 'Cascos',
            'description' => 'Arma natural de cascos.',
            'cost' => -1,
            'purpose' => 'melee',
            'grip' => 'natural',
            'base_dmg' => '1d8',
            'base_margin' => 20,
            'base_reach' => 0,
            'damage_type' => 'bludgeoning',
            'slots' => 0,
            'icon_file_name' => 'cascos_01.webp',
        ]);

        Weapon::create([
            'id' => 1003,
            'name' => 'Garras',
            'description' => 'Arma natural de garras.',
            'cost' => -1,
            'purpose' => 'melee',
            'grip' => 'natural',
            'base_dmg' => '1d6',
            'base_margin' => 20,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'slots' => 0,
            'icon_file_name' => 'garra_01.webp',
        ]);

        Weapon::create([
            'id' => 1004,
            'name' => 'Cauda',
            'description' => 'Arma natural de cauda.',
            'cost' => -1,
            'purpose' => 'melee',
            'grip' => 'natural',
            'base_dmg' => '1d6',
            'base_margin' => 20,
            'base_reach' => 0,
            'damage_type' => 'bludgeoning',
            'slots' => 0,
            'icon_file_name' => 'cauda_01.webp',
        ]);

        Weapon::create([
            'id' => 1005,
            'name' => 'Língua',
            'description' => 'Arma natural de língua. Ela é uma arma versátil, fornecendo +2 em testes para desarmar e derrubar.',
            'cost' => -1,
            'purpose' => 'melee',
            'grip' => 'natural',
            'base_dmg' => '1d4',
            'base_margin' => 20,
            'base_reach' => 3,
            'damage_type' => 'bludgeoning',
            'slots' => 0,
            'ability_ids' => [9],
            'icon_file_name' => 'lingua_01.webp',
        ]);
    }
}
