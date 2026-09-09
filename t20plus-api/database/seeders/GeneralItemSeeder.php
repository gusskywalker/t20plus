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
            'icon_file_name' => 'flechas_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 3,
            'name' => 'Munição (20)',
            'description' => 'Um cartucho com 20 balas para uso com armas de fogo.',
            'type' => 'ammo',
            'cost' => 20,
            'slots' => 1,
            'icon_file_name' => 'municao_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 4,
            'name' => 'Instrumentos de Coureiro',
            'description' => 'Com essas ferramentas, você pode realizar o seu Ofício (Coureiro) sem penalidades.',
            'type' => 'tools',
            'cost' => 50,
            'slots' => 0.5,
            'icon_file_name' => null,
            'effects' => null,
            'consumable' => false,
        ]);
    }
}
