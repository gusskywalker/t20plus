<?php

namespace Database\Seeders;

use App\Models\GeneralItem;
use Illuminate\Database\Seeder;

class GeneralItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        GeneralItem::create([
            'id' => 1,
            'name' => 'Essência de Mana',
            'description' => 'Esta poção feita de ervas raras e compostos alquímicos recupera energia pessoal. Beber a essência de mana é uma ação padrão e recupera 1d4 pontos de mana.',
            'type' => 'potion',
            'cost' => 50,
            'slots' => 0.5,
            'icon_id' => 4, // items/potions_01.webp
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 73], // Essência de Mana (consumable_granted)
            ],
            'consumable' => true,
        ]);
    }
}
