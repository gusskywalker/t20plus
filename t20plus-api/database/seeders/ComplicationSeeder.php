<?php

namespace Database\Seeders;

use App\Models\Complication;
use Illuminate\Database\Seeder;

class ComplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row, same convention as every other
        // seeder.
        Complication::create([
            'id' => 1,
            'name' => 'Chato',
            'description' => 'Sempre que você sai de uma aldeia, uma festa acontece. Você sofre –5 em Diplomacia e a atitude inicial de NPCs em relação a você é uma categoria pior.',
            'type' => 'general',
            'power_ids' => [25], // Chato (complication_granted)
        ]);

        Complication::create([
            'id' => 2,
            'name' => 'Abatido',
            'description' => 'Seu vigor se foi. Você recebe –2 PV por nível.',
            'type' => 'age',
            'power_ids' => [26], // Abatido (complication_granted)
        ]);

        Complication::create([
            'id' => 3,
            'name' => 'Catarata',
            'description' => 'Seus olhos já não são os mesmos. Você sofre –5 em Percepção e Pontaria.',
            'type' => 'age',
            'power_ids' => [27], // Catarata (complication_granted)
        ]);
    }
}
