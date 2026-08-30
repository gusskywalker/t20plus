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
        Armor::create([
            'name' => 'Traje de Sacerdote',
            'description' => 'O traje de sacerdote em é um item inicial de interpretação (roleplay) recebido por personagens com a origem Acólito.',
            'type' => 'light',
            'mod_def' => 0,
            'armor_penalty' => 0,
            'cost' => 0,
            'slots' => 1,
        ]);
    }
}
