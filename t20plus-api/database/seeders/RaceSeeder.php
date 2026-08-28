<?php

namespace Database\Seeders;

use App\Models\Race;
use Illuminate\Database\Seeder;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Race::create([
            'name' => 'Elfo',
            'mod_str' => 0,
            'mod_dex' => 1,
            'mod_con' => -1,
            'mod_int' => 2,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 12,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Hynne',
            'mod_str' => -1,
            'mod_dex' => 2,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 1,
            'mod_other' => 0,
            'base_movement' => 6,
            'base_size' => -1,
        ]);

        Race::create([
            'name' => 'Medusa',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Sílfide',
            'mod_str' => -2,
            'mod_dex' => 1,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 2,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => -2,
        ]);

        Race::create([
            'name' => 'Trog',
            'mod_str' => 1,
            'mod_dex' => 0,
            'mod_con' => 2,
            'mod_int' => -1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Humano',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 3,
            'base_movement' => 9,
            'base_size' => 0,
        ]);
    }
}
