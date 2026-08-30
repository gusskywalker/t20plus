<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        $skills = [
            [
                'id' => 1,
                'name' => 'Acrobacia',
                'key_attribute' => 'dex',
                'trained_only' => false,
                'armor_penalty' => true,
            ],
            [
                'id' => 2,
                'name' => 'Adestramento',
                'key_attribute' => 'car',
                'trained_only' => true,
                'armor_penalty' => false,
            ],
            [
                'id' => 3,
                'name' => 'Atletismo',
                'key_attribute' => 'str',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 4,
                'name' => 'Atuação',
                'key_attribute' => 'car',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 5,
                'name' => 'Cavalgar',
                'key_attribute' => 'dex',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 6,
                'name' => 'Conhecimento',
                'key_attribute' => 'int',
                'trained_only' => true,
                'armor_penalty' => false,
            ],
            [
                'id' => 7,
                'name' => 'Cura',
                'key_attribute' => 'knw',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 8,
                'name' => 'Diplomacia',
                'key_attribute' => 'car',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 9,
                'name' => 'Enganação',
                'key_attribute' => 'car',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 10,
                'name' => 'Fortitude',
                'key_attribute' => 'con',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 11,
                'name' => 'Furtividade',
                'key_attribute' => 'dex',
                'trained_only' => false,
                'armor_penalty' => true,
            ],
            [
                'id' => 12,
                'name' => 'Guerra',
                'key_attribute' => 'int',
                'trained_only' => true,
                'armor_penalty' => false,
            ],
            [
                'id' => 13,
                'name' => 'Iniciativa',
                'key_attribute' => 'dex',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 14,
                'name' => 'Intimidação',
                'key_attribute' => 'car',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 15,
                'name' => 'Investigação',
                'key_attribute' => 'int',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 16,
                'name' => 'Intuição',
                'key_attribute' => 'knw',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 17,
                'name' => 'Jogatina',
                'key_attribute' => 'car',
                'trained_only' => true,
                'armor_penalty' => false,
            ],
            [
                'id' => 18,
                'name' => 'Ladinagem',
                'key_attribute' => 'dex',
                'trained_only' => true,
                'armor_penalty' => true,
            ],
            [
                'id' => 19,
                'name' => 'Luta',
                'key_attribute' => 'str',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 20,
                'name' => 'Misticismo',
                'key_attribute' => 'int',
                'trained_only' => true,
                'armor_penalty' => false,
            ],
            [
                'id' => 21,
                'name' => 'Nobreza',
                'key_attribute' => 'int',
                'trained_only' => true,
                'armor_penalty' => false,
            ],
            [
                'id' => 22,
                'name' => 'Ofício',
                'key_attribute' => 'int',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 23,
                'name' => 'Percepção',
                'key_attribute' => 'knw',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 24,
                'name' => 'Pilotagem',
                'key_attribute' => 'dex',
                'trained_only' => true,
                'armor_penalty' => false,
            ],
            [
                'id' => 25,
                'name' => 'Pontaria',
                'key_attribute' => 'dex',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 26,
                'name' => 'Reflexos',
                'key_attribute' => 'dex',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 27,
                'name' => 'Religião',
                'key_attribute' => 'knw',
                'trained_only' => true,
                'armor_penalty' => false,
            ],
            [
                'id' => 28,
                'name' => 'Sobrevivência',
                'key_attribute' => 'knw',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
            [
                'id' => 29,
                'name' => 'Vontade',
                'key_attribute' => 'knw',
                'trained_only' => false,
                'armor_penalty' => false,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
