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

        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        $tiers = [
            ['id' => 1, 'bonus' => 4, 'pm_cost' => 1, 'min_level' => 1],
            ['id' => 2, 'bonus' => 8, 'pm_cost' => 2, 'min_level' => 5],
            ['id' => 3, 'bonus' => 12, 'pm_cost' => 3, 'min_level' => 9],
            ['id' => 4, 'bonus' => 16, 'pm_cost' => 4, 'min_level' => 13],
            ['id' => 5, 'bonus' => 20, 'pm_cost' => 5, 'min_level' => 17],
        ];

        foreach ($tiers as $tier) {
            Power::create([
                'id' => $tier['id'],
                'name' => "Ataque Especial +{$tier['bonus']}",
                'description' => $description,
                'type' => 'class',
                'usability' => 'toggle',
                'action_cost' => 'none',
                'pm_cost' => $tier['pm_cost'],
                'prerequisites' => [
                    [
                        'type' => 'class',
                        'class_ids' => [1], // Guerreiro
                        'min_level' => $tier['min_level'],
                    ],
                ],
            ]);
        }

        Power::create([
            'id' => 6,
            'name' => 'Medicina',
            'description' => 'Você pode gastar uma ação completa para fazer um teste de Cura (CD 15) em uma criatura. Se você passar, ela recupera 1d6 PV, mais 1d6 para cada 5 pontos pelos quais o resultado do teste exceder a CD (2d6 com um resultado 20, 3d6 com um resultado 25 e assim por diante). Você só pode usar este poder uma vez por dia numa mesma criatura.',
            'type' => 'general',
            'usability' => 'action',
            'action_cost' => 'complete',
            'pm_cost' => 0,
        ]);

        Power::create([
            'id' => 7,
            'name' => 'Vontade de Ferro',
            'description' => 'Você recebe +1 PM para cada dois níveis de personagem e +2 em Vontade.',
            'type' => 'general',
            'usability' => 'passive',
            'action_cost' => 'none',
            'pm_cost' => 0,
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_pm', 'op' => 'add_per_level', 'value' => 1, 'per_levels' => 2],
                ['tag' => 'skill', 'skill_id' => 29, 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 8,
            'name' => 'Membro da Igreja',
            'description' => 'Você consegue hospedagem confortável e informação em qualquer templo de sua divindade, para você e seus aliados.',
            'type' => 'resting',
            'usability' => 'passive',
            'action_cost' => 'none',
            'pm_cost' => 0,
            'effects' => [
                ['tag' => 'resting', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 9,
            'name' => 'Afinidade com a Tormenta',
            'description' => 'Você recebe +10 em testes de resistência contra efeitos da Tormenta, de suas criaturas e de devotos de Aharadak. Além disso, seu primeiro poder da Tormenta não conta para perda de Carisma.',
            'type' => 'divine_granted',
            'usability' => 'trigger',
            'action_cost' => 'none',
            'trigger_on' => 'targets_you_tormenta',
            'pm_cost' => 0,
            'effects' => [
                // +10 em testes de resistência (Fortitude, Reflexos, Vontade)
                // contra efeitos/criaturas da Tormenta e devotos de Aharadak.
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 10],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 10],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 10],
                // Waives Carisma loss for the first Tormenta-type power the
                // character takes. See claude-stuff/t20-rules-summary.md,
                // "Tormenta Powers & Carisma Loss" — a future Carisma-loss
                // resolver checks this tag when granting a power with
                // powers.type === 'tormenta'.
                ['tag' => 'tormenta_power_carisma_loss', 'op' => 'waive', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 10,
            'name' => 'Êxtase da Loucura',
            'description' => 'Toda vez que uma ou mais criaturas falham em um teste de Vontade contra uma de suas habilidades mágicas, você recebe 1 PM temporário cumulativo. Você pode ganhar um máximo de PM temporários por cena desta forma igual a sua Sabedoria.',
            'type' => 'divine_granted',
            'usability' => 'trigger',
            'action_cost' => 'none',
            'trigger_on' => 'enemy_fails_save_vontade',
            'pm_cost' => 0,
            'effects' => [
                ['tag' => 'temp_pm', 'op' => 'add', 'value' => 1, 'limit' => 'knw'],
            ],
        ]);

        Power::create([
            'id' => 11,
            'name' => 'Percepção Temporal',
            'description' => 'Você pode gastar 3 PM para somar sua Sabedoria (limitado por seu nível e não cumulativo com efeitos que somam este atributo) a seus ataques, Defesa e testes de Reflexos até o fim da cena.',
            'type' => 'divine_granted',
            'usability' => 'action',
            'action_cost' => 'none',
            'duration' => 'scene',
            'pm_cost' => 3,
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 'knw', 'limit' => 'level', 'stack_group' => 'bonus_hit_knw'],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 'knw', 'limit' => 'level', 'stack_group' => 'bonus_def_knw'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 'knw', 'limit' => 'level', 'stack_group' => 'bonus_reflexos_knw'], // Reflexos
            ],
        ]);

        Power::create([
            'id' => 12,
            'name' => 'Rejeição Divina',
            'description' => 'Você recebe resistência a magia divina +5.',
            'type' => 'divine_granted',
            'usability' => 'trigger',
            'action_cost' => 'none',
            'trigger_on' => 'targets_you_spell_divine',
            'pm_cost' => 0,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5], // Vontade
            ],
        ]);
    }
}
