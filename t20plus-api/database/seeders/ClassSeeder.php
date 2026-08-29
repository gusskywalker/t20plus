<?php

namespace Database\Seeders;

use App\Models\CharacterClass;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skillIds = Skill::pluck('id', 'name');

        $ids = fn (array $names) => array_map(fn ($name) => $skillIds[$name], $names);

        CharacterClass::create([
            'name' => 'Guerreiro',
            'initial_pv' => 20,
            'initial_pm' => 3,
            'level_pv' => 5,
            'level_pm' => 3,
            'skills' => [
                ['count' => 1, 'options' => $ids(['Luta', 'Pontaria'])],
                ['count' => 1, 'options' => $ids(['Fortitude'])],
                ['count' => 2, 'options' => $ids([
                    'Adestramento', 'Atletismo', 'Cavalgar', 'Guerra', 'Iniciativa',
                    'Intimidação', 'Luta', 'Ofício', 'Percepção', 'Pontaria', 'Reflexos',
                ])],
            ],
        ]);
    }
}
