<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class DivineGrantedPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Power::create([
            'id' => 9,
            'name' => 'Afinidade com a Tormenta',
            'description' => 'Você recebe +10 em testes de resistência contra efeitos da Tormenta, de suas criaturas e de devotos de Aharadak. Além disso, seu primeiro poder da Tormenta não conta para perda de Carisma.',
            'source' => 'divine_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'afinidade_com_a_tormenta_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
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
            'source' => 'divine_granted',
            'usability' => 'active',
            'icon_file_name' => 'extase_na_loucura_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
            'effects' => [
                ['tag' => 'temp_pm', 'op' => 'add', 'value' => 1, 'limit' => 'knw'],
            ],
        ]);

        Power::create([
            'id' => 11,
            'name' => 'Percepção Temporal',
            'description' => 'Você pode gastar 3 PM para somar sua Sabedoria (limitado por seu nível e não cumulativo com efeitos que somam este atributo) a seus ataques, Defesa e testes de Reflexos até o fim da cena.',
            'source' => 'divine_granted',
            'usability' => 'active',
            'icon_file_name' => 'percepcao_temporal_01.webp',
            'duration' => 'scene',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 'knw', 'limit' => 'character_level', 'stack_group' => 'bonus_hit_knw'],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 'knw', 'limit' => 'character_level', 'stack_group' => 'bonus_def_knw'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 'knw', 'limit' => 'character_level', 'stack_group' => 'bonus_reflexos_knw'], // Reflexos
            ],
        ]);

        Power::create([
            'id' => 12,
            'name' => 'Rejeição Divina',
            'description' => 'Você recebe resistência a magia divina +5.',
            'source' => 'divine_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'rejeicao_divina_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5], // Vontade
            ],
        ]);

        Power::create([
            'id' => 21,
            'name' => 'Corromper Equipamento',
            'description' => 'Você pode gastar 2 PM para cobrir uma arma, um escudo ou um esotérico que esteja empunhando com carapaça quitinosa. Até o fim da cena, o item recebe os benefícios de matéria vermelha, cumulativo com outros materiais especiais. Se usar este poder em uma arma produzida com Armamento Aberrante, seu custo é reduzido em –1 PM.',
            'source' => 'divine_granted',
            'usability' => 'active',
            'icon_file_name' => 'corromper_equipamento_01.webp',
            'action_cost' => 'none', // not stated in the source text beyond the PM cost
            'duration' => 'scene',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
            // effects not modeled: this is a player-choice-at-activation
            // power (pick weapon/shield/esotérico), which grants the
            // matching Matéria Vermelha power (14/15+16/17) for the scene,
            // cumulative past the normal one-material limit — same "special
            // case, not generic" treatment as other choice-at-activation
            // powers (see powers migration comment). The -1 PM discount for
            // Armamento Aberrante-sourced weapons (power 20) is also
            // unmodeled — needs to check the target weapon's provenance,
            // which nothing tracks yet. Both accepted gaps for now, per
            // claude-stuff/tag-system.md "Parked".
        ]);

        Power::create([
            'id' => 22,
            'name' => 'Espalhar a Corrupção',
            'description' => 'Quando chega em uma comunidade, você pode gastar um dia e fazer um teste de Religião (CD 20). Se passar, você planta a semente da corrupção no coração das pessoas em uma área equivalente a uma aldeia, um castelo ou um bairro de uma cidade grande. Por uma semana, ou até você partir do lugar, a categoria de atitude dessas pessoas em relação umas às outras piora em um passo, à medida que o senso moral delas se deteriora e seus piores desejos vêm à tona. Isso pode ser útil para gerar conflitos entre elas, embora caiba a você descobrir exatamente como se aproveitar deles.',
            'source' => 'divine_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'espalhar_corrupcao_01.webp',
            'action_cost' => 'none', // "um dia" isn't a combat action-economy concept
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
            // no effects — purely narrative, resolved between player and
            // master; see usability: roleplay in claude-stuff/tag-system.md.
        ]);

        Power::create([
            'id' => 23,
            'name' => 'Júbilo na Dor',
            'description' => 'Quando causa ou sofre dano, você recebe redução de dano 1. Esse efeito é cumulativo e limitado por sua Sabedoria e termina se você passar 1 rodada sem causar ou sofrer dano.',
            'source' => 'divine_granted',
            'usability' => 'passive',
            'icon_file_name' => 'jubilo_na_dor_01.webp',
            'decay_after' => 1,
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ]
        ]);

        Power::create([
            'id' => 24,
            'name' => 'Mediador da Tempestade',
            'description' => 'Você pode se comunicar com lefeu inteligentes (Int –3 ou maior) livremente e recebe +5 em testes de Diplomacia e Intuição com criaturas da Tormenta e devotos de Aharadak.',
            'source' => 'divine_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'mediador_da_tempestade_01.webp',
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 5], // Diplomacia
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 5], // Intuição
            ],
        ]);    }
}
