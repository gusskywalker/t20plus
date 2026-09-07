<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class AgeGrantedPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Power::create([
            'id' => 28,
            'name' => 'Criança',
            'description' => 'Crianças são fisicamente mais fracas e frágeis que adultos, além de menos capazes de entender as sutilezas do mundo.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'crianca_01.webp',
            'effects' => [
                ['tag' => 'mod_str', 'op' => 'add', 'value' => -2],
                ['tag' => 'mod_con', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_knw', 'op' => 'add', 'value' => -1], // Sabedoria
            ],
        ]);

        Power::create([
            'id' => 29,
            'name' => 'Tamanho Menor',
            'description' => 'Você é uma categoria de tamanho menor que o padrão de sua raça (exceto se sua raça já for Minúscula; nesse caso, a mudança é apenas estética).',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'tamanho_menor_01.webp',
            'effects' => [
                // New tag: mod_size — same -2..+3 scale as races.base_size
                // (Minúsculo/Pequeno/Médio/Grande/Enorme/Colossal). The
                // "already Minúscula = purely cosmetic" clause isn't
                // modeled — self-reported like every other narrative-only
                // caveat.
                ['tag' => 'mod_size', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 30,
            'name' => 'Sem Origem',
            'description' => 'Você não recebe benefícios de origem. Você está apenas começando a viver os anos que definirão quem você será!',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sem_origem_01.webp',
            // No effects — this is enforced on the frontend by stripping
            // whatever the origin step granted before the character is
            // actually created, not by a resolver-facing effect. The power
            // still exists purely as a record of why (see Chato/Abatido/
            // etc. for the same "power exists to be remembered" pattern).
        ]);

        Power::create([
            'id' => 31,
            'name' => 'Protegido dos Deuses',
            'description' => 'Você recebe +2 na Defesa e +5 em todos os testes de resistência. Isso é uma mistura de sorte sobrenatural com o fato de que inimigos normalmente ignoram crianças, justamente por serem menos perigosas.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'protegido_pelos_deuses_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                // Testes de resistência are ordinary skills here (10/26/29),
                // same convention as Vontade de Ferro, Aharadak's Rejeição
                // Divina, and the medalhão accessory — not a separate mod_*
                // tag family.
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5], // Vontade
            ],
        ]);

        Power::create([
            'id' => 32,
            'name' => 'Adolescente',
            'description' => 'Sabedoria –1. Adolescentes são conhecidos por sua impetuosidade.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adolescente_01.webp',
            'effects' => [
                ['tag' => 'mod_knw', 'op' => 'add', 'value' => -1], // Sabedoria
            ],
        ]);

        Power::create([
            'id' => 33,
            'name' => 'Ímpeto Juvenil',
            'description' => 'Você recebe +3 pontos de mana. Adolescentes acham que podem fazer qualquer coisa, e essa confiança os torna mais heroicos.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'impeto_juvenil_01.webp',
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 34,
            'name' => 'Origem em Construção',
            'description' => 'Você recebe apenas um benefício de origem, em vez de dois (se sua origem possuir um único benefício, comece com uma perícia treinada a menos por sua classe).',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'origem_em_construcao_01.webp',
            // No effects — same treatment as Sem Origem (power 30): this
            // restricts how many origin choice-groups step 4 lets the
            // player pick from, handled on the frontend, not a
            // resolver-facing effect. The power exists as a record.
        ]);

        Power::create([
            'id' => 35,
            'name' => 'Jovem',
            'description' => 'Você está na flor da idade, nem os percalços da juventude nem os fardos da maturidade o afetam.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'jovem_01.webp',
            // No effects — Jovem is the baseline age bracket, no
            // modifiers. The power exists purely as a record.
        ]);

        Power::create([
            'id' => 36,
            'name' => 'Adulto',
            'description' => 'Você está em plena maturidade. Pode receber um Poder Geral extra e escolher uma Complicação de idade.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adulto_01.webp',
            // No effects — the bonus power/complication picks themselves
            // are what's granted (step 7's Poder Geral/Complicação (idade)
            // dropdowns), not a resolver-facing effect. The power exists
            // purely as a record.
        ]);

        Power::create([
            'id' => 37,
            'name' => 'Maduro',
            'description' => 'Você entra na meia-idade. Recebe um nível extra e duas Complicações de idade.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'maduro_01.webp',
            // No effects — same reasoning as Adulto (power 36): the extra
            // level/complication picks are what's granted (step 7's
            // Classe/Complicação (idade) dropdowns), not a resolver-facing
            // effect. The power exists purely as a record.
        ]);

        Power::create([
            'id' => 38,
            'name' => 'Velho',
            'description' => 'Seu corpo já não responde como antes.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'velho_01.webp',
            'effects' => [
                ['tag' => 'mod_str', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_dex', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_con', 'op' => 'add', 'value' => -1],
                // New tag: level_up_attribute_increase_lock — marks
                // "Aumentar Atributo bloqueado para atributos físicos."
                // Same placeholder pattern as tormenta_power_carisma_loss:
                // the level-up Aumentar Atributo system doesn't exist
                // yet, but the restriction is recorded now so a future
                // resolver can check it once that system is built.
                ['tag' => 'level_up_attribute_increase_lock', 'op' => 'grant', 'scope' => 'physical'],
            ],
        ]);

        Power::create([
            'id' => 39,
            'name' => 'Ancião',
            'description' => 'Seu corpo é frágil, mas sua mente carrega o peso da experiência.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'anciao_01.webp',
            'effects' => [
                ['tag' => 'mod_str', 'op' => 'add', 'value' => -2],
                ['tag' => 'mod_dex', 'op' => 'add', 'value' => -2],
                ['tag' => 'mod_con', 'op' => 'add', 'value' => -2],
                // Same placeholder tag as Velho (power 38) — see its
                // comment for why this exists ahead of the level-up
                // Aumentar Atributo system it references.
                ['tag' => 'level_up_attribute_increase_lock', 'op' => 'grant', 'scope' => 'physical'],
            ],
        ]);    }
}
