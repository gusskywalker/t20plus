<?php

namespace Database\Seeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class PowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $description = 'Quando faz um ataque, você pode gastar 1 PM para receber +4 no teste de ataque ou na rolagem de dano. A cada quatro níveis, pode gastar +1 PM para aumentar o bônus em +4. Você pode dividir os bônus igualmente. Por exemplo, no 17º nível, pode gastar 5 PM para receber +20 no ataque, +20 no dano ou +10 no ataque e +10 no dano.';

        $tiers = [
            ['bonus' => 4, 'pm_cost' => 1, 'min_level' => 1],
            ['bonus' => 8, 'pm_cost' => 2, 'min_level' => 5],
            ['bonus' => 12, 'pm_cost' => 3, 'min_level' => 9],
            ['bonus' => 16, 'pm_cost' => 4, 'min_level' => 13],
            ['bonus' => 20, 'pm_cost' => 5, 'min_level' => 17],
        ];

        foreach ($tiers as $tier) {
            Power::create([
                'name' => "Ataque Especial +{$tier['bonus']}",
                'description' => $description,
                'usability' => 'active',
                'pm_cost' => $tier['pm_cost'],
                'prerequisites' => [
                    [
                        'type' => 'class',
                        'classes' => ['guerreiro'],
                        'min_level' => $tier['min_level'],
                    ],
                ],
            ]);
        }
    }
}
