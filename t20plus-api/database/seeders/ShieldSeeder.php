<?php

namespace Database\Seeders;

use App\Models\Shield;
use Illuminate\Database\Seeder;

class ShieldSeeder extends Seeder
{

    public function run(): void
    {

        Shield::create([
            'id' => 1,
            'name' => 'Escudo Leve',
            'description' => 'Tipicamente feito de madeira, este escudo é amarrado no antebraço, deixando a mão livre. Você pode carregar um objeto na mão que empunha o escudo, mas não manusear uma arma.',
            'type' => 'light',
            'mod_def' => 1,
            'armor_penalty' => 1,
            'cost' => 5,
            'slots' => 1,
            'icon_file_name' => 'escudo_leve_01.webp',
        ]);
    }
}
