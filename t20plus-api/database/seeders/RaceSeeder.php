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
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        Race::create([
            'id' => 1,
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
            'id' => 2,
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
            'id' => 3,
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
            'id' => 4,
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
            'id' => 5,
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
            'id' => 6,
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
            'id' => 7,
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
            'id' => 8,
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
            'id' => 9,
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
            'id' => 10,
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
            'id' => 11,
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
            'id' => 12,
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
            'id' => 13,
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
            'id' => 14,
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
            'id' => 15,
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
            'id' => 16,
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
            'id' => 17,
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
            'id' => 18,
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
            'id' => 19,
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
            'id' => 20,
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
            'id' => 21,
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
            'id' => 22,
            'name' => 'Meio-Elfo',
            'mod_str' => 0,
            'mod_dex' => 0,
            'mod_con' => 0,
            'mod_int' => 1,
            'mod_knw' => 0,
            'mod_car' => 0,
            'mod_other' => 2,
            'mod_other_excluded_attributes' => ['con'], // "+1 em dois atributos, exceto Constituição"
            'base_movement' => 9,
            'base_size' => 0,
        ]);

        Race::create([
            'id' => 23,
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
            'id' => 24,
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
            'id' => 25,
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
            'id' => 26,
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
            'id' => 27,
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
            'id' => 28,
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
            'id' => 29,
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
            'id' => 30,
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
            'id' => 31,
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
            'id' => 32,
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
            'id' => 33,
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
            'id' => 34,
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
            'id' => 35,
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
            'id' => 36,
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
            'id' => 37,
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
            'id' => 38,
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
            'id' => 39,
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
            'id' => 40,
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
            'id' => 41,
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
            'id' => 42,
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
            'id' => 43,
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
            'id' => 44,
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
            'id' => 45,
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
            'id' => 46,
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
            'id' => 47,
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
            'id' => 48,
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
            'id' => 49,
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
            'id' => 50,
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
            'id' => 51,
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
            'id' => 52,
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
            'id' => 53,
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
            'id' => 54,
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
            'id' => 55,
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
