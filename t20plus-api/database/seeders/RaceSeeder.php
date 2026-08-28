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
            'name' => 'Anão',
            'mod_str' => 0,
            'mod_dex' => -1,
            'mod_con' => 2,
            'mod_int' => 0,
            'mod_knw' => 1,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 6,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Bugbear',
            'mod_str' => 2,
            'mod_dex' => 1,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => -1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Centauro',
            'mod_str' => 1,
            'mod_dex' => -1,
            'mod_con' => 0,
            'mod_int' => -1,
            'mod_knw' => 2,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 12,
            'base_size' => 1,
        ]);

        Race::create([
            'name' => 'Ceratops',
            'mod_str' => 1,
            'mod_dex' => -1,
            'mod_con' => 2,
            'mod_int' => -1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 1,
        ]);

        Race::create([
            'name' => 'Dahllan',
            'mod_str' => 0,
            'mod_dex' => 1,
            'mod_con' => 0,
            'mod_int' => -1,
            'mod_knw' => 2,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Eiradaan',
            'mod_str' => -1,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 2,
            'mod_car' => 1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

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
            'name' => 'Elfo-do-Mar',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 1,
            'mod_int' => -1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Finntroll',
            'mod_str' => -1,
            'mod_dex' => 0,
            'mod_con' => 1,
            'mod_int' => 2,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Galokk',
            'mod_str' => 1,
            'mod_dex' => 0,
            'mod_con' => 1,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => -1,
            'mod_other' => 1,
            'base_movement' => 9,
            'base_size' => 1,
        ]);

        Race::create([
            'name' => 'Gnoll',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 2,
            'mod_int' => -1,
            'mod_knw' => 1,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Goblin',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => 0,
            'mod_car' => -1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => -1,
        ]);

        Race::create([
            'name' => 'Harpia',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 0,
            'mod_int' => -1,
            'mod_knw' => 0,
            'mod_car' => 1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Hobgoblin',
            'mod_str' => 0,
            'mod_dex' => 1,
            'mod_con' => 2,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => -1,
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
            'name' => 'Kaijin',
            'mod_str' => 2,
            'mod_dex' => 0,
            'mod_con' => 1,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => -2,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Kappa',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 1,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => -1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Kliren',
            'mod_str' => -1,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 2,
            'mod_knw' => 0,
            'mod_car' => 1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Lefou',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => -1,
            'mod_other' => 3,
            'base_movement' => 9,
            'base_size' => 0,
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
            'name' => 'Meio-Elfo',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Meio-Orc',
            'mod_str' => 2,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 1,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Minauro',
            'mod_str' => 1,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Minotauro',
            'mod_str' => 2,
            'mod_dex' => 0,
            'mod_con' => 1,
            'mod_int' => 0,
            'mod_knw' => -1,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Búfalo)',
            'mod_str' => 1,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Coelho)',
            'mod_str' => 0,
            'mod_dex' => 1,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 12,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Coruja)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 1,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Crocodilo)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 1,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Gato)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 1,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Hiena)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 1,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Leão)',
            'mod_str' => 1,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Lobo)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 1,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Raposa)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 12,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Serpente)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Moreau (Urso)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 1,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'base_movement' => 9,
            'base_size' => 1,
        ]);

        Race::create([
            'name' => 'Nagah (F)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => 1,
            'mod_car' => 1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Nagah (M)',
            'mod_str' => 1,
            'mod_dex' => 1,
            'mod_con' => 1,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Nezumi',
            'mod_str' => 0,
            'mod_dex' => 1,
            'mod_con' => 2,
            'mod_int' => -1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => -1,
        ]);

        Race::create([
            'name' => 'Ogro',
            'mod_str' => 3,
            'mod_dex' => 0,
            'mod_con' => 2,
            'mod_int' => -1,
            'mod_knw' => 0,
            'mod_car' => -1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 1,
        ]);

        Race::create([
            'name' => 'Orc',
            'mod_str' => 2,
            'mod_dex' => 0,
            'mod_con' => 1,
            'mod_int' => -1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Osteon',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => -1,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 3,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Pteros',
            'mod_str' => 0,
            'mod_dex' => 1,
            'mod_con' => 0,
            'mod_int' => -1,
            'mod_knw' => 2,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Qareen',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => -1,
            'mod_car' => 2,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Sátiro',
            'mod_str' => 0,
            'mod_dex' => 1,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => -1,
            'mod_car' => 2,
            'mod_other' => 0,
            'base_movement' => 12,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Sereia/Tritão',
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
            'name' => 'Suraggel (Aggelus)',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 2,
            'mod_car' => 1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Suraggel (Sulfure)',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Tabrachi',
            'mod_str' => 1,
            'mod_dex' => 0,
            'mod_con' => 2,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => -1,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Tengu',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
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
            'name' => 'Velocis',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 0,
            'mod_int' => -1,
            'mod_knw' => 1,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 12,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Voracis',
            'mod_str' => 0,
            'mod_dex' => 2,
            'mod_con' => 1,
            'mod_int' => -1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 0,
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'name' => 'Yidishan',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 0,
            'mod_knw' => 0,
            'mod_car' => -2,
            'mod_other' => 3,
            'base_movement' => 9,
            'base_size' => 0,
        ]);
    }
}
