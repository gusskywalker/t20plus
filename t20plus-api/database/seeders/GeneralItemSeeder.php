<?php

namespace Database\Seeders;

use App\Models\GeneralItem;
use Illuminate\Database\Seeder;

class GeneralItemSeeder extends Seeder
{

    public function run(): void
    {

        GeneralItem::create([
            'id' => 1,
            'name' => 'Essência de Mana',
            'description' => 'Esta poção feita de ervas raras e compostos alquímicos recupera energia pessoal. Beber a essência de mana é uma ação padrão e recupera 1d4 pontos de mana.',
            'type' => 'potion',
            'cost' => 50,
            'slots' => 0.5,
            'icon_file_name' => 'essencia_de_mana_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 73],
            ],
            'consumable' => true,
        ]);

        GeneralItem::create([
            'id' => 2,
            'name' => 'Flechas (20)',
            'description' => 'Um feixe de 20 flechas para uso com arcos.',
            'type' => 'ammo',
            'cost' => 1,

            'slots' => 1,
            'icon_file_name' => 'items/ammo_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);
    }
}
