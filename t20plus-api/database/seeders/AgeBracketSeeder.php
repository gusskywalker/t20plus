<?php

namespace Database\Seeders;

use App\Models\AgeBracket;
use Illuminate\Database\Seeder;

class AgeBracketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row, same convention as every other
        // seeder.
        AgeBracket::create([
            'id' => 1,
            'name' => 'Criança',
            'description' => '9 a 12 anos.',
            'power_ids' => [28, 29, 31, 30], // Criança, Tamanho Menor, Protegido dos Deuses, Sem Origem
        ]);

        AgeBracket::create([
            'id' => 2,
            'name' => 'Adolescente',
            'description' => '13 a 17 anos.',
            'power_ids' => [32, 33, 34], // Adolescente, Ímpeto Juvenil, Origem em Construção
        ]);

        AgeBracket::create([
            'id' => 3,
            'name' => 'Jovem',
            'description' => '18 a 24 anos.',
            'power_ids' => [35], // Jovem
        ]);

        AgeBracket::create([
            'id' => 4,
            'name' => 'Adulto',
            'description' => '25 a 39 anos.',
            'power_ids' => [36], // Adulto
        ]);

        AgeBracket::create([
            'id' => 5,
            'name' => 'Maduro',
            'description' => '40 a 59 anos.',
            'power_ids' => [37], // Maduro
        ]);

        AgeBracket::create([
            'id' => 6,
            'name' => 'Velho',
            'description' => '60 a 79 anos.',
            'power_ids' => [38], // Velho
        ]);

        AgeBracket::create([
            'id' => 7,
            'name' => 'Ancião',
            'description' => '80+ anos.',
            'power_ids' => [39], // Ancião
        ]);
    }
}
