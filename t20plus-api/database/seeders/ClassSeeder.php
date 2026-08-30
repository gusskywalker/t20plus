<?php

namespace Database\Seeders;

use App\Models\CharacterClass;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded so other seeders/files can reference it directly
        // instead of looking it up. Same for the skill ids below (see
        // SkillSeeder.php for what each id is).
        CharacterClass::create([
            'id' => 1,
            'name' => 'Guerreiro',
            'initial_pv' => 20,
            'initial_pm' => 3,
            'level_pv' => 5,
            'level_pm' => 3,
            'skills' => [
                ['picks' => 1, 'options' => [19, 25]], // Luta, Pontaria
                ['picks' => 1, 'options' => [10]], // Fortitude
                ['picks' => 2, 'options' => [
                    2, 3, 5, 12, 13, 14, 19, 22, 23, 25, 26,
                    // Adestramento, Atletismo, Cavalgar, Guerra, Iniciativa,
                    // Intimidação, Luta, Ofício, Percepção, Pontaria, Reflexos
                ]],
            ],
        ]);
    }
}
