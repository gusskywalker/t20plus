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
    }
}
