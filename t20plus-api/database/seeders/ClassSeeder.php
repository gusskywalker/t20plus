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
        CharacterClass::create([
            'name' => 'Guerreiro',
            'initial_pv' => 20,
            'initial_pm' => 3,
            'level_pv' => 5,
            'level_pm' => 3,
        ]);
    }
}
