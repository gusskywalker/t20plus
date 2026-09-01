<?php

namespace Database\Seeders;

use App\Models\Weapon;
use Illuminate\Database\Seeder;

class WeaponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        Weapon::create([
            'id' => 1,
            'name' => 'Espada Curta',
            'description' => 'O tipo mais comum de espada, usada por guardas ou como arma secundária de guerreiros mais capazes. Mede entre 40 e 50cm.',
            'price' => 10,
            // No proficiency_id — armas simples need no proficiency power,
            // everyone has them by default.
            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d6',
            'base_margin' => 19,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'space' => 1,
        ]);

        Weapon::create([
            'id' => 2,
            'name' => 'Cimitarra',
            'description' => 'Uma espada de lâmina curva, mais leve que uma espada longa.',
            'price' => 15,
            'proficiency_id' => 40, // Proficiência - Armas Marciais
            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d6',
            'base_margin' => 18,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'space' => 1,
            'ability_ids' => [2], // Ágil
        ]);
    }
}
