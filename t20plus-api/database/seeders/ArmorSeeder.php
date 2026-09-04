<?php

namespace Database\Seeders;

use App\Models\Armor;
use Illuminate\Database\Seeder;

class ArmorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded so other seeders/files can reference it directly
        // instead of looking it up.
        Armor::create([
            'id' => 1,
            'name' => 'Traje de Sacerdote',
            'description' => 'O traje de sacerdote em é um item inicial de interpretação (roleplay) recebido por personagens com a origem Acólito.',
            'type' => 'light',
            'mod_def' => 0,
            'armor_penalty' => 0,
            'cost' => -1, // not purchasable — roleplay item from the Acólito origin
            'slots' => 1,
            'icon_file_name' => 'items/armors_01.webp', // placeholder, same for every armor for now
        ]);

        Armor::create([
            'id' => 2,
            'name' => 'Armadura de Couro',
            'description' => 'O peitoral desta armadura é feito de couro curtido em óleo fervente, para ficar mais rígido, enquanto as demais partes são feitas de couro flexível.',
            'type' => 'light',
            'mod_def' => 2,
            'armor_penalty' => 0,
            'cost' => 20,
            'slots' => 2,
            'icon_file_name' => 'items/armors_01.webp',
        ]);

        Armor::create([
            'id' => 3,
            'name' => 'Couro Batido',
            'description' => '', // TODO: no flavor text given yet
            'type' => 'light',
            'mod_def' => 3,
            'armor_penalty' => 1,
            'cost' => 35,
            'slots' => 2,
            'icon_file_name' => 'items/armors_01.webp',
        ]);

        Armor::create([
            'id' => 4,
            'name' => 'Gibão de Peles',
            'description' => '', // TODO: no flavor text given yet
            'type' => 'light',
            'mod_def' => 4,
            'armor_penalty' => 3,
            'cost' => 25,
            'slots' => 2,
            'icon_file_name' => 'items/armors_01.webp',
        ]);

        Armor::create([
            'id' => 5,
            'name' => 'Brunea',
            'description' => '', // TODO: no flavor text given yet
            'type' => 'heavy',
            'mod_def' => 5,
            'armor_penalty' => 2,
            'cost' => 50,
            'slots' => 5,
            'icon_file_name' => 'items/armors_01.webp',
        ]);
    }
}
