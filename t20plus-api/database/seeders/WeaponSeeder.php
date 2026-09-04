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
            'cost' => 10,
            // No proficiency_id — armas simples need no proficiency power,
            // everyone has them by default.
            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d6',
            'base_margin' => 19,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'slots' => 1,
            'icon_file_name' => 'items/weapons_01.webp', // placeholder, same for every weapon for now
        ]);

        Weapon::create([
            'id' => 2,
            'name' => 'Cimitarra',
            'description' => 'Uma espada de lâmina curva, mais leve que uma espada longa.',
            'cost' => 15,
            'proficiency_id' => 40, // Proficiência - Armas Marciais
            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d6',
            'base_margin' => 18,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'slots' => 1,
            'icon_file_name' => 'items/weapons_01.webp', // placeholder, same for every weapon for now
            'ability_ids' => [2], // Ágil
        ]);

        Weapon::create([
            'id' => 3,
            'name' => 'Machado de Guerra',
            'description' => 'Este imenso machado com lâmina dupla é uma das armas mais perigosas que existem.',
            'cost' => 20,
            'proficiency_id' => 40, // Proficiência - Armas Marciais
            'purpose' => 'melee',
            'grip' => 'two_hand',
            'base_dmg' => '1d12',
            'base_margin' => 20,
            'base_multiplier' => 3,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'slots' => 2,
            'icon_file_name' => 'items/weapons_01.webp', // placeholder, same for every weapon for now
        ]);

        // Synthetic — not a real owned item, never sits in character_inventory.
        // The attack-modal resolves an empty hand straight to this row (id 4)
        // instead of leaving weapon null, so Desarmado just reads like any
        // other weapon (base_dmg, grip, purpose) with no special-casing.
        Weapon::create([
            'id' => 4,
            'name' => 'Desarmado',
            'description' => 'Ataque desarmado — dano de impacto não letal, não afetado por efeitos que visam armas.',
            'cost' => 0,
            // No proficiency_id — everyone can fight unarmed.
            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d3',
            'base_margin' => 20,
            'base_reach' => 0,
            'damage_type' => 'bludgeoning',
            'slots' => 0, // not a carried item
            'icon_file_name' => 'items/weapons_01.webp', // placeholder, same for every weapon for now
        ]);

        //TODO remove this, its just for testing criticals
        Weapon::create([
            'id' => 5,
            'name' => 'Machado de Guerra CRITADOR',
            'description' => 'Este imenso machado com lâmina dupla é uma das armas mais perigosas que existem.',
            'cost' => 20,
            'proficiency_id' => 40, // Proficiência - Armas Marciais
            'purpose' => 'melee',
            'grip' => 'two_hand',
            'base_dmg' => '1d12',
            'base_margin' => 8,
            'base_multiplier' => 3,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'slots' => 2,
            'icon_file_name' => 'items/weapons_01.webp', // placeholder, same for every weapon for now
        ]);
    }
}
