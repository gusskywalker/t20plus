<?php

namespace Database\Seeders;

use App\Models\CharacterClass;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{

    public function run(): void
    {

        // TODO when Arcanista gets seeded here: step 8's free-armor rule

        CharacterClass::create([
            'id' => 1,
            'name' => 'Guerreiro',
            'initial_pv' => 20,
            'initial_pm' => 3,
            'level_pv' => 5,
            'level_pm' => 3,
            'divine_power_picks' => 1,
            'proficiency_ids' => [40, 42, 43],
            'skills' => [
                ['picks' => 1, 'options' => [19, 25]],
                ['picks' => 1, 'options' => [10]],
                ['picks' => 2, 'options' => [
                    2, 3, 5, 12, 13, 14, 19, 22, 23, 25, 26,

                ]],
            ],
        ]);

        CharacterClass::create([
            'id' => 2,
            'name' => 'Caçador',
            'initial_pv' => 16,
            'initial_pm' => 4,
            'level_pv' => 4,
            'level_pm' => 4,
            'divine_power_picks' => 1,
            'proficiency_ids' => [40, 43],
            'skills' => [
                ['picks' => 1, 'options' => [19, 25]],
                ['picks' => 1, 'options' => [28]],
                ['picks' => 6, 'options' => [
                    2, 3, 5, 7, 10, 11, 13, 15, 19, 22, 23, 25, 26,

                ]],
            ],
        ]);
    }
}
