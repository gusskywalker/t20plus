<?php

namespace Database\Seeders;

use App\Models\Shield;
use Illuminate\Database\Seeder;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        Shield::create([
            'id' => 1,
            'name' => 'Escudo Leve',
            'description' => 'Tipicamente feito de madeira, este escudo é amarrado no antebraço, deixando a mão livre. Você pode carregar um objeto na mão que empunha o escudo, mas não manusear uma arma.',
            'type' => 'light',
            'mod_def' => 1,
            'armor_penalty' => 1,
            'cost' => 5,
            'slots' => 1,
            'icon_id' => 6, // shields_01.webp — placeholder, same for every shield for now
        ]);
    }
}
