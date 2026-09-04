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
                'source' => 'class_granted',
                'usability' => 'roll_active',
                'icon_file_name' => 'ataque_especial_01.webp',
                'pm_cost' => $tier['pm_cost'],
                'prerequisites' => [
                    [
                        'type' => 'class',
                        'class_ids' => [1], // Guerreiro
                        'min_level' => $tier['min_level'],
                    ],
                ],
                'effects' => [
                    // Odd one out — the player splits this bonus between the
                    // attack roll and the damage roll however they like
                    // (equally, or all into one), not a fixed split like
                    // every other mod_hit/mod_dmg power. Tagged separately
                    // from those two so a resolver can single out "needs a
                    // player choice at roll time" instead of just summing it
                    // blindly into one bucket. Not resolved yet — parked
                    // until the attack roll UI actually asks for the split.
                    ['tag' => 'mod_hit_or_dmg', 'op' => 'add', 'value' => $tier['bonus']],
                ],
            ]);
        }

        Power::create([
            'id' => 6,
            'name' => 'Medicina',
            'description' => 'Você pode gastar uma ação completa para fazer um teste de Cura (CD 15) em uma criatura. Se você passar, ela recupera 1d6 PV, mais 1d6 para cada 5 pontos pelos quais o resultado do teste exceder a CD (2d6 com um resultado 20, 3d6 com um resultado 25 e assim por diante). Você só pode usar este poder uma vez por dia numa mesma criatura.',
            'source' => 'general',
            'usability' => 'active',
            'icon_file_name' => 'medicina_01.webp',
            'action_cost' => 'complete',
        ]);

        Power::create([
            'id' => 7,
            'name' => 'Vontade de Ferro',
            'description' => 'Você recebe +1 PM para cada dois níveis de personagem e +2 em Vontade.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'vontade_de_ferro_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add_per_level', 'value' => 1, 'per_levels' => 2],
                ['tag' => 'skill', 'skill_id' => 29, 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 8,
            'name' => 'Membro da Igreja',
            'description' => 'Você consegue hospedagem confortável e informação em qualquer templo de sua divindade, para você e seus aliados.',
            // Granted through Acólito's own "Perícias e Poderes" choice
            // (see OriginSeeder power_id 8), not a general pickable power.
            'source' => 'origin_granted',
            // Only matters at the moment of resting, not a standing total
            // — same "which screen resolves this" reasoning as active/
            // roll_active, just for a future Descansar screen instead of
            // an attack/skill roll.
            'usability' => 'resting',
            'icon_file_name' => 'membro_da_igreja_01.webp',
            'effects' => [
                ['tag' => 'resting', 'op' => 'set', 'value' => 1],
            ],
        ]);

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
            'id' => 13,
            'name' => 'Farpada',
            'description' => 'Poder concedido pela melhoria de item Farpada. Um acerto crítico causa a condição Sangrando no alvo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arma_farpada_01.webp',
            'effects' => [
                [
                    'tag' => 'on_critical_strike',
                    'op' => 'inflict',
                    'condition_id' => 1 // Sangrando
                ],
            ],
        ]);

        Power::create([
            'id' => 14,
            'name' => 'Arma - Matéria Vermelha',
            'description' => 'Poder concedido por armas cobertas de matéria vermelha. Causa +1d6 de dano extra ao acertar, mas o usuário perde 1 ponto de vida.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arma_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => '1d6'],
                ['tag' => 'self_damage', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15,
            'name' => 'Armadura/Escudo Leve - Matéria Vermelha',
            'description' => 'Poder concedido por armaduras leves ou escudos cobertos de matéria vermelha. Ataques contra o usuário têm 10% de chance de falhar automaticamente.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_leve_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 16,
            'name' => 'Armadura Pesada - Matéria Vermelha',
            'description' => 'Poder concedido por armaduras pesadas cobertas de matéria vermelha. Ataques contra o usuário têm 25% de chance de falhar automaticamente.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_pesada_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 25],
            ],
        ]);

        Power::create([
            'id' => 17,
            'name' => 'Esotérico - Matéria Vermelha (Portador)',
            'description' => 'Poder concedido por esotéricos cobertos de matéria vermelha. O usuário sofre -2 em testes de resistência contra efeitos mágicos.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'esotericos_materia_vermelha_01.webp',
            'effects' => [
                // Known simplification: applies to these 3 skills for ANY
                // resistance test, not just magic-sourced ones ("contra
                // efeitos mágicos" per the source text) — no magic-vs-
                // mundane qualifier exists yet. Accepted gap, not a bug.
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => -2], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => -2], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => -2], // Vontade
            ],
        ]);

        Power::create([
            'id' => 19,
            'name' => 'Esotérico - Matéria Vermelha (Inimigos Próximos)',
            'description' => 'Poder concedido por esotéricos cobertos de matéria vermelha. Inimigos a curto alcance do portador sofrem -2 em testes de resistência contra efeitos mágicos.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'esotericos_materia_vermelha_01.webp',
            'range' => 9,
            'effects' => [
                // Targets enemies within range, not the character holding
                // this power — same as any effect on an enemy-targeted
                // power (see Farpada). Same magic-vs-mundane simplification
                // as power 17 — accepted gap, not a bug.
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => -2], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => -2], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => -2], // Vontade
            ],
        ]);

        Power::create([
            'id' => 18,
            'name' => 'Instrumento Musical - Matéria Vermelha',
            'description' => 'Poder concedido por instrumentos musicais cobertos de matéria vermelha. Aumenta em +1 a CD das habilidades de bardo (exceto magias) quando o usuário utiliza o instrumento.',
            'source' => 'item_granted',
            // dc_active — self-reported checkbox on a future CD-calculator
            // screen, same "player decides if it applies right now"
            // treatment as roll_active on the attack screen. No scope
            // needed: the player only ever checks it while actually
            // computing a bard (non-magia) ability's CD in the first
            // place, so context does the filtering, not a stored value.
            'usability' => 'dc_active',
            'icon_file_name' => 'instrumento_musical_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'mod_dc', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 20,
            'name' => 'Armamento Aberrante',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para produzir uma versão orgânica de qualquer arma corpo a corpo ou de arremesso com a qual seja proficiente — ela brota do seu braço, ombro ou costas como uma planta grotesca e então se desprende. O dano da arma aumenta em um passo para cada dois outros poderes da Tormenta que você possui. A arma dura pela cena, então se desfaz numa poça de gosma.',
            'source' => 'tormenta',
            'usability' => 'active',
            'icon_file_name' => 'armamento_aberrante_01.webp',
            'action_cost' => 'movement',
            'duration' => 'scene',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power_type', 'value' => 'tormenta'], // "outro poder da Tormenta"
            ],
            // effects not modeled: creating a temporary weapon-copy (of any
            // weapon the character is proficient with) and scaling its
            // damage by floor(other Tormenta powers / 2) steps needs
            // infrastructure (weapon templating, live power-count scaling)
            // that doesn't exist. Self-reported for now — accepted gap, per
            // claude-stuff/tag-system.md "Parked".
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
        ]);

        Power::create([
            'id' => 25,
            'name' => 'Chato',
            'description' => 'Sempre que você sai de uma aldeia, uma festa acontece. Você sofre –5 em Diplomacia e a atitude inicial de NPCs em relação a você é uma categoria pior.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'chato_01.webp',
            // The NPC-attitude clause is pure roleplay (master call, no
            // stored state to check it against) — not modeled here.
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => -5], // Diplomacia
            ],
        ]);

        Power::create([
            'id' => 26,
            'name' => 'Abatido',
            'description' => 'Seu vigor se foi. Você recebe –2 PV por nível.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'abatido_01.webp',
            'effects' => [
                // New tag: mod_max_pv (Pontos de Vida) — same add_per_level
                // shape as mod_max_pm's "+1 PM a cada dois níveis", just
                // per_levels: 1 here since it's every level, not every two.
                ['tag' => 'mod_max_pv', 'op' => 'add_per_level', 'value' => -2, 'per_levels' => 1],
            ],
        ]);

        Power::create([
            'id' => 27,
            'name' => 'Catarata',
            'description' => 'Seus olhos já não são os mesmos. Você sofre –5 em Percepção e Pontaria.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'catarata_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => -5], // Percepção
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 25, 'value' => -5], // Pontaria
            ],
        ]);

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
                // "Aumento de Atributo bloqueado para atributos físicos."
                // Same placeholder pattern as tormenta_power_carisma_loss:
                // the level-up Aumento de Atributo system doesn't exist
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
                // Aumento de Atributo system it references.
                ['tag' => 'level_up_attribute_increase_lock', 'op' => 'grant', 'scope' => 'physical'],
            ],
        ]);

        Power::create([
            'id' => 40,
            'name' => 'Proficiência - Armas Marciais',
            'description' => 'Você recebe proficiência em armas marciais.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_armas_marciais_01.webp',
        ]);

        Power::create([
            'id' => 41,
            'name' => 'Proficiência - Armas de Fogo',
            'description' => 'Você recebe proficiência em armas de fogo.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_armas_de_fogo_01.webp',
        ]);

        Power::create([
            'id' => 42,
            'name' => 'Proficiência - Armaduras Pesadas',
            'description' => 'Você recebe proficiência em armaduras pesadas.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_armadura_pesada_01.webp',
        ]);

        Power::create([
            'id' => 43,
            'name' => 'Proficiência - Escudos',
            'description' => 'Você recebe proficiência em escudos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_escudos_01.webp',
        ]);

        Power::create([
            'id' => 44,
            'name' => 'Proficiência - Arco de Guerra',
            'description' => 'Você recebe proficiência em arcos de guerra.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_arco_de_guerra_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 40], // Proficiência - Armas Marciais
            ]
        ]);

        Power::create([
            'id' => 45,
            'name' => 'Ímpeto',
            'description' => 'Você pode gastar 1 PM para aumentar seu deslocamento em +6m por uma rodada.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'impeto_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
        ]);

        // Split into one power per attribute PER PATAMAR (ids 46-69, 4 tiers
        // x 6 attributes) instead of one repeatable "Aumento de Atributo"
        // power — "apenas uma vez por patamar para um mesmo atributo" is
        // encoded directly as data via chained prerequisites (each tier
        // requires having the previous tier's power id, plus the patamar's
        // min character level) rather than as bespoke "count how many times
        // this was picked" validation code somewhere else. Whatever already
        // resolves prerequisites generically (power/character_level) is
        // then the only logic needed — the cap enforces itself, since tier
        // N simply isn't choosable without tier N-1, and tier N-1 is a
        // fact you either have or don't. New "type": "character_level"
        // prerequisite here — {min: total character level}, NOT tied to a
        // specific class the way "type": "class"'s min_level is — see
        // create_powers_table.php.
        $attributeLabels = [
            'str' => 'Força',
            'dex' => 'Destreza',
            'con' => 'Constituição',
            'int' => 'Inteligência',
            'knw' => 'Sabedoria',
            'car' => 'Carisma',
        ];
        // One icon per attribute, reused across that attribute's own 4
        // tiers (same icon for Iniciante/Veterano/Campeão/Lenda — the tier
        // is in the name, not the art).
        $attributeIconFileNames = [
            'str' => 'aumentar_forca_01.webp',
            'dex' => 'aumentar_destreza_01.webp',
            'con' => 'aumentar_con_01.webp',
            'int' => 'aumentar_int_01.webp',
            'knw' => 'aumentar_sabedoria_01.webp',
            'car' => 'aumentar_carisma_01.webp',
        ];
        // [level requirement, PT-BR patamar name — name isn't stored, just
        // documents which tier is which] per tier, in order.
        $patamares = [
            ['min_level' => null, 'label' => 'Iniciante'],
            ['min_level' => 5, 'label' => 'Veterano'],
            ['min_level' => 11, 'label' => 'Campeão'],
            ['min_level' => 17, 'label' => 'Lenda'],
        ];

        $id = 46;
        foreach ($attributeLabels as $attribute => $label) {
            $previousTierId = null;
            foreach ($patamares as $patamar) {
                // class_ids lists every class currently seeded — append the
                // new class's id here too whenever a new class gets seeded,
                // since Aumento de Atributo is a Poder de Classe every
                // class gets (not a Poder Geral).
                $prerequisites = [
                    ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ];
                if ($previousTierId !== null) {
                    $prerequisites[] = ['type' => 'power', 'power_id' => $previousTierId];
                }
                if ($patamar['min_level'] !== null) {
                    $prerequisites[] = ['type' => 'character_level', 'min' => $patamar['min_level']];
                }

                Power::create([
                    'id' => $id,
                    'name' => "Aumento de Atributo ({$label})",
                    'description' => 'Você recebe +1 em um atributo. Você pode escolher este poder várias vezes, mas apenas uma vez por patamar para um mesmo atributo.',
                    'source' => 'class',
                    'usability' => 'passive',
                    'icon_file_name' => $attributeIconFileNames[$attribute],
                    'effects' => [
                        ['tag' => "mod_{$attribute}", 'op' => 'add', 'value' => 1],
                    ],
                    'prerequisites' => $prerequisites ?: null,
                ]);

                $previousTierId = $id;
                $id++;
            }
        }

        Power::create([
            'id' => 70,
            'name' => 'Saque Rápido',
            'description' => 'Você recebe +2 em Iniciativa e pode sacar ou guardar itens como uma ação livre (em vez de ação de movimento). Além disso, a ação que você gasta para recarregar armas de disparo diminui em uma categoria (ação completa para padrão, padrão para movimento, movimento para livre).',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'saque_rapido_01.webp',
            'effects' => [
                ['tag' => 'skill', 'skill_id' => 13, 'op' => 'add', 'value' => 2], // Iniciativa
            ],
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 13], // treinado em Iniciativa
            ],
        ]);

        Power::create([
            'id' => 71,
            'name' => 'Vitalidade',
            'description' => 'Você recebe +1 PV por nível de personagem e +2 em Fortitude.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'vitalidade_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'con', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_max_pv', 'op' => 'add_per_level', 'value' => 1, 'per_levels' => 1],
                ['tag' => 'skill', 'skill_id' => 10, 'op' => 'add', 'value' => 2], // Fortitude
            ],
        ]);

        Power::create([
            'id' => 72,
            'name' => 'Ataque Poderoso',
            'description' => 'Sempre que faz um ataque corpo a corpo, você pode sofrer –2 no teste de ataque para receber +5 na rolagem de dano.',
            'source' => 'general',
            // Same as Ataque Especial — rides a roll the player is already
            // making, decided fresh every attack, never persists.
            'usability' => 'roll_active',
            'icon_file_name' => 'ataque_poderoso_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 73,
            'name' => 'Essência de Mana',
            'description' => 'Beber a essência de mana é uma ação padrão e recupera 1d4 pontos de mana.',
            'source' => 'consumable_granted',
            'usability' => 'active',
            'icon_file_name' => 'essencia_de_mana_01.webp',
            'action_cost' => 'standard',
            'effects' => [
                ['tag' => 'restore_pm', 'op' => 'roll', 'value' => '1d4'],
                ['tag' => 'reduce_qty', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 74,
            'name' => 'Esquiva',
            'description' => 'Você recebe +2 na Defesa e Reflexos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'esquiva_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2], // Reflexos
            ],
        ]);

        Power::create([
            'id' => 75,
            'name' => 'Durão',
            'description' => 'A partir do 3º nível, sua rijeza muscular permite que você absorva ferimentos. Sempre que sofre dano, você pode gastar 3 PM para reduzir esse dano à metade.',
            'source' => 'class_granted',
            'usability' => 'active',
            'icon_file_name' => 'durao_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 3], // Guerreiro 3
            ]
        ]);

        Power::create([
            'id' => 76,
            'name' => 'Ataque Extra',
            'description' => 'A partir do 6º nível de Guerreiro, quando usa a ação agredir, você pode gastar 2 PM para realizar um ataque adicional uma vez por rodada.',
            'source' => 'class_granted',
            'usability' => 'active',
            'icon_file_name' => 'ataque_extra_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6], // Guerreiro 6
            ],
            'effects' => [
                // Sums across sources — a second extra-attack power later
                // just adds another 1, no per-source special-casing.
                ['tag' => 'extra_attack', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 77,
            'name' => 'Ambidestria',
            'description' => 'Se estiver empunhando duas armas (e pelo menos uma delas for leve) e fizer a ação agredir, você pode fazer dois ataques, um com cada arma. Se fizer isso, sofre –2 em todos os testes de ataque até o seu próximo turno.',
            'source' => 'class',
            // Rides the attack roll itself — same shape as Ataque
            // Especial/Ataque Poderoso, decided fresh every time, no
            // pm_cost. Checking the box in the attack roll's power
            // checklist IS the self-report ("I'm using Ambidestria on
            // this attack") — once checked, both effects below apply
            // automatically via resolveTag, same as any other checked
            // power.
            'usability' => 'roll_active',
            'icon_file_name' => 'ambidestria_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 2],
            ],
            'effects' => [
                // Both bundled into the same activation choice — either
                // both apply (checkbox on) or neither do. The dual-
                // wielding requirement itself (two weapons, at least one
                // light) isn't modeled — no equipped-weapons check exists
                // yet, self-reported like every other equipment-state
                // condition.
                ['tag' => 'extra_attack', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 78,
            'name' => 'Arqueiro',
            'description' => 'Se estiver usando uma arma de ataque à distância, você soma sua Sabedoria em rolagens de dano (limitado pelo seu nível).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'arqueiro_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
            ],
            // weapon_purpose is real, checkable data
            // (weapons.purpose) — gates whether this power is even
            // relevant to surface in a self-report checklist (e.g. the
            // planned attack-mode picker), separate from the effect's own
            // numeric value below.
            'visibility_reqs' => ['weapon_purpose' => ['thrown', 'fired']],
            'effects' => [
                // Same "attribute-code value + level cap" shape as
                // Percepção Temporal, minus its stack_group — that one
                // exists because Percepção Temporal's text explicitly says
                // "não cumulativo com efeitos que somam este atributo";
                // Arqueiro's text has no such clause.
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 'knw', 'limit' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 79,
            'name' => 'Ataque Reflexo',
            'description' => 'Se um alvo em alcance de seus ataques corpo a corpo ficar desprevenido ou se mover voluntariamente para fora do seu alcance, você pode gastar 1 PM para fazer um ataque corpo a corpo contra esse alvo (apenas uma vez por alvo a cada rodada).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'ataque_reflexo_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 1],
            ],
            // No effects — this depends on melee range/engagement (who's
            // in range of whom) and reacting mid another creature's turn,
            // neither of which the app tracks at all (no grid/positioning
            // system, deliberately out of scope). Structurally
            // unresolvable without a whole separate positional layer, not
            // just "not modeled yet" — pure self-report, permanently.
        ]);

        Power::create([
            'id' => 80,
            'name' => 'Bater e Correr',
            'description' => 'Quando faz uma investida, você pode continuar se movendo após o ataque, até o limite de seu deslocamento. Se gastar 2 PM, pode fazer uma investida sobre terreno difícil e sem sofrer a penalidade de Defesa.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'bater_e_correr_01.webp',
            // pm_cost is only for the upgraded version (terreno
            // difícil + no Defesa penalty) — the base "keep moving after
            // an investida" part is free. One pm_cost field can't
            // represent "free base effect, paid upgrade" — same unmodeled
            // shape as Corromper Equipamento's Armamento Aberrante
            // discount. Both halves are movement-conditional anyway (see
            // combat-engine-plans.md's "No board, no grid" — permanently
            // self-reported, no positions tracked), so no effects either
            // way.
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
        ]);

        Power::create([
            'id' => 81,
            'name' => 'Destruidor',
            'description' => 'Quando causa dano com uma arma corpo a corpo de duas mãos, você pode rolar novamente qualquer resultado 1 ou 2 da rolagem de dano da arma.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'golpe_destruidor_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 1],
            ],
            // weapon_grip is real, checkable data (weapons.grip)
            // — gates whether this power is even relevant to surface in a
            // self-report checklist, separate from the granted capability
            // below.
            'visibility_reqs' => ['weapon_grip' => 'two_hand'],
            'effects' => [
                // New tag: reroll_dice_below — generic threshold value
                // instead of baking "1 or 2" into the tag name, so a
                // future power with a different threshold (e.g. reroll
                // any 1) reuses this same tag. op: grant, not add/set —
                // this hands you a capability, not a number to sum.
                ['tag' => 'reroll_dice_below', 'op' => 'grant', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 82,
            'name' => 'Esgrimista',
            'description' => 'Quando usa uma arma corpo a corpo leve ou ágil, você soma sua Inteligência em rolagens de dano (limitado pelo seu nível).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'esgrimista_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'int', 'min' => 1],
            ],
            // "leve ou ágil" is an OR across two different data sources —
            // grip (weapons.grip) vs. the Ágil weapon ability
            // (weapon_abilities id 2, weapons.ability_ids) — an array of
            // small condition objects, satisfied if any one matches.
            // Gates whether this power is even relevant to surface in a
            // self-report checklist, separate from the effect's own
            // numeric value below.
            'visibility_reqs' => ['weapon_any' => [
                ['grip' => 'light'],
                ['ability' => 2], // Ágil
            ]],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 'int', 'limit' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 83,
            'name' => 'Especialização em Arma',
            'description' => 'Escolha uma arma. Você recebe +2 em rolagens de dano com essa arma. Você pode escolher este poder outras vezes para armas diferentes. <br><br>No APP, virá automaticamente marcado na tela de rolagens de dano. Você pode escolher não utilizar o poder.',
            'source' => 'class',
            // Unlike Arqueiro/Destruidor, this can never be auto-resolved
            // — there's no chosen-weapon tracking (deliberately not
            // added; see tag-system.md's Parked section for the
            // discussion). No data exists for "is this the weapon I
            // specialized in," so a human declares it fresh every roll —
            // roll_active, not passive. No pm_cost, so roll_active fits
            'usability' => 'roll_active',
            // No downside to checking this — flat +2, no tradeoff.
            'default_checked' => true,
            'icon_file_name' => 'especializacao_em_arma_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // "Escolha uma arma... você pode escolher este poder
                // outras vezes para armas diferentes" isn't modeled — no
                // per-instance chosen-weapon reference exists (would need
                // a new column + loosened unique constraint on
                // character_active_effects, rejected as not worth the
                // schema growth for a flavor-only distinction the app
                // can't otherwise use). One copy of this power covers
                // every "espada que você especializou" the player has,
                // self-reported same as everything else — see
                // tag-system.md's Parked section.
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 84,
            'name' => 'Especialização em Armadura',
            'description' => 'Você recebe redução de dano 5 se estiver usando uma armadura pesada.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especializacao_em_armadura_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 12], // Guerreiro 12
            ],
            // No effects — same reasoning just applied to Durão/Júbilo na
            // Dor above: damage_reduction reduces damage RECEIVED, but
            // there's no incoming-damage calculation anywhere in the app
            // for it to plug into. Self-reported, prose only.
        ]);

        Power::create([
            'id' => 85,
            'name' => 'Golpe de Raspão',
            'description' => 'Uma vez por rodada, quando erra um ataque, você pode gastar 2 PM. Se fizer isso, causa metade do dano que causaria (ignorando efeitos que se aplicariam caso o ataque acertasse). Role seu ataque e dano novamente, então reduza pela metade.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'golpe_de_raspao_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — "metade do dano que causaria" needs the app to
            // actually know what a missed attack's damage would have been
            // (and to selectively ignore on-hit effects while computing
            // it), which no damage calculation the app has does. Same
            // "self-reported, no mechanism to plug into" treatment as the
            // damage_reduction powers above. "Uma vez por rodada" isn't
            // enforced either — no per-round usage tracking exists.
        ]);

        Power::create([
            'id' => 86,
            'name' => 'Golpe Demolidor',
            'description' => 'Quando usa a manobra quebrar ou ataca um objeto, você pode gastar 2 PM para ignorar a redução de dano dele.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'demolidor_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // New tag: ignore_dr — same flat-number-or-percent-string
                // convention as damage_reduction, so a flat "ignore N
                // points" power (Romper Resistências) and a full-bypass
                // power like this one share one tag/scale instead of
                // needing a separate boolean capability. "100%" here
                // means ignore all of it. Meant for the future damage
                // roll screen's checklist, same self-report-via-checkbox
                // pattern as mod_hit in the attack modal.
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => '100%'],
            ],
        ]);

        Power::create([
            'id' => 87,
            'name' => 'Mestre em Arma',
            'description' => 'Escolha uma arma. Com esta arma, seu dano aumenta em um passo e você pode gastar 2 PM para rolar novamente um teste de ataque recém realizado. <br><br>No APP, o dano será calculado automaticamente. <br><br>Para re-rollar, reduza manualmente o PM e role novamente o ataque.',
            'source' => 'class',
            'usability' => 'roll_active',
            // No downside to checking this — start it checked, same
            // reasoning as any no-cost bonus.
            'default_checked' => true,
            'icon_file_name' => 'mestre_em_armas_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                // "com a arma escolhida" isn't checkable — Especialização
                // em Arma (power 83) doesn't track which weapon either
                // (see tag-system.md's Parked section), so this just
                // requires having that power at all.
                ['type' => 'power', 'power_id' => 83], // Especialização em Arma
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 12], // Guerreiro 12
            ],
            'effects' => [
                // New tag: weapon_step_increase — bumps the weapon's
                // damage die up one step (1d6->1d8->1d10...). op: add
                // (steps sum across sources, same convention as
                // extra_attack). Not resolved yet — parked for the damage
                // roll screen, same treatment as Executor's dice-step
                // scaling.
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
            // The post-roll reroll option isn't tagged — it's not a
            // pre-roll checklist modifier at all (you already rolled by
            // the time you decide to use it), a fundamentally different
            // interaction shape than everything else here. The 2 PM cost
            // for the reroll isn't enforced either; the player tracks
            // their own PM if they use it.
        ]);

        Power::create([
            'id' => 88,
            'name' => 'Planejamento Marcial',
            'description' => 'Uma vez por dia, você pode gastar uma hora e 3 PM para escolher um poder de guerreiro ou de combate cujos pré-requisitos cumpra. Você recebe os benefícios desse poder até o próximo dia. <br><br>No APP, após ativar, use o botão "Adicionar Poder".',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'planejamento_marcial_01.webp',
            'action_cost' => 'none', // "uma hora" isn't a combat action-economy concept
            'duration' => 'day', // "até o próximo dia"
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12], // treinado em Guerra
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 10], // Guerreiro 10
            ],
            // No effects — the actual chosen power varies every use, so
            // there's nothing fixed to tag. Player self-manages via the
            // existing Adicionar Poder button: add whichever qualifying
            // power they picked for the day, remove it once it expires.
            // "Uma vez por dia" and the prerequisite check against the
            // chosen power aren't enforced — both self-reported.
        ]);

        Power::create([
            'id' => 89,
            'name' => 'Romper Resistências',
            'description' => 'Quando faz um Ataque Especial, você pode gastar 1 PM adicional para ignorar 10 pontos de redução de dano.',
            'source' => 'class',
            'usability' => 'roll_active',
            // No downside to checking this — start it checked.
            'default_checked' => true,
            'icon_file_name' => 'romper_resistencias_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                // "quando faz um Ataque Especial" is a per-use condition,
                // not a pick-time gate — self-reported, not a
                // prerequisite entry.
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 90,
            'name' => 'Solidez',
            'description' => 'Se estiver usando um escudo, você aplica o bônus na Defesa recebido pelo escudo em testes de resistência.',
            'source' => 'class',
            // Self-contained gear condition, no decision — same treatment
            // as Arqueiro/Destruidor.
            'usability' => 'passive',
            'icon_file_name' => 'solidez_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // value: 'mod_def_from_shield' — new sentinel alongside
                // the existing flat-number/attribute-code conventions:
                // "use whatever mod_def the character's currently
                // equipped shield grants" (character_hands ->
                // character_inventory -> shields.mod_def). Self-contained
                // — no shield worn means nothing to grab, so no separate
                // requires_shield_equipped condition needed. Two shields
                // worn at once: frontend just takes whichever comes up
                // first, no tie-break logic. Not resolved yet — parked
                // for whenever Defesa/skill resolution reads this value
                // kind. Same 3-skill shape as Afinidade com a Tormenta/
                // Rejeição Divina/Protegido dos Deuses.
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 'mod_def_from_shield'], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 'mod_def_from_shield'], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 'mod_def_from_shield'], // Vontade
            ],
        ]);

        Power::create([
            'id' => 91,
            'name' => 'Tornado de Dor',
            'description' => 'Você pode gastar uma ação padrão e 2 PM para desferir uma série de golpes giratórios. Faça um ataque corpo a corpo e compare-o com a Defesa de cada inimigo em seu alcance natural. Então faça uma rolagem de dano com um bônus cumulativo de +2 para cada acerto e aplique-a em cada inimigo atingido. <br><br>No APP, adicione manualmente o bônus de dano.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'tornado_de_dor_01.webp',
            'action_cost' => 'standard',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6], // Guerreiro 6
            ],
            // No effects — a real multi-target AoE resolution (one attack
            // roll compared against every enemy in reach, a damage roll
            // that scales with hit count, applied per target) is well
            // beyond a single-target mod_hit/mod_dmg tag, and there's no
            // multi-target attack/damage screen to plug it into anyway.
            // Fully self-reported.
        ]);

        Power::create([
            'id' => 92,
            'name' => 'Valentão',
            'description' => 'Você recebe +2 em testes de ataque e rolagens de dano contra oponentes caídos, desprevenidos, flanqueados ou indefesos.',
            'source' => 'class',
            // Condition is the target's status, not something the app
            // tracks — self-reported, same treatment as every other
            // enemy-state condition. default_checked stays false (the
            // column default): unlike a gear-based bonus, this one's
            // genuinely situational, not usually true.
            'usability' => 'roll_active',
            'icon_file_name' => 'valentao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 93,
            'name' => 'Campeão',
            'description' => 'No 20º nível, o dano de todos os seus ataques aumenta em um passo. Além disso, sempre que você faz um Ataque Especial ou um Golpe Pessoal e acerta o ataque, recupera metade dos PM gastos nele. <br><br>No APP, o dano será calculado automaticamente. Para recuperar os PM, adicione-os manualmente.',
            // Auto-granted at level 20, no pick involved — same as every
            // Ataque Especial tier, Durão, and Ataque Extra, not a
            // choosable "class" power like Especialização em Arma.
            'source' => 'class_granted',
            // Unconditional standing fact, not even gear-conditional like
            // Mestre em Arma — always true once granted.
            'usability' => 'passive',
            'icon_file_name' => 'campeao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 20], // Guerreiro 20
            ],
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
            // The PM-recovery clause isn't tagged — no PM is auto-
            // deducted/restored anywhere in the app for any power (see
            // Ataque Especial etc.), so nothing to plug an automatic
            // "recupera metade dos PM gastos" into. Player tracks and
            // restores it manually.
        ]);

        Power::create([
            'id' => 94,
            'name' => 'Análise Tática',
            'description' => 'Você recebe +2 em Guerra e pode fazer testes dessa perícia para identificar criatura contra humanoides.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'analise_tatica_01.webp',
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12], // treinado em Guerra
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 12, 'value' => 2], // Guerra
            ],
            // "Identificar criatura contra humanoides" isn't modeled —
            // no identify-creature mechanic exists anywhere in the app.
            // Self-reported, prose only.
        ]);

        Power::create([
            'id' => 95,
            'name' => 'Arremesso de Investida',
            'description' => 'Quando faz uma investida, você pode gastar 1 PM para realizar um ataque à distância adicional com uma arma de arremesso contra o alvo da investida.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'arremesso_de_investida_01.webp',
            'action_cost' => 'none', // rides the investida action, not a separate action of its own
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — a real extra ranged attack against a specific
            // target needs the same multi-target/second-attack resolution
            // Tornado de Dor and Ataque Extra would need, nothing to plug
            // into. Fully self-reported.
        ]);

        Power::create([
            'id' => 96,
            'name' => 'Bloqueio Brutal',
            'description' => 'Uma vez por rodada, quando é atingido por um ataque, você pode gastar 2 PM para fazer uma rolagem de dano corpo a corpo e subtrair o resultado dessa rolagem do dano causado pelo ataque.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'bloqueio_brutal_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 5],
            ],
            // No effects — same incoming-damage-calculation gap as
            // damage_reduction (Durão/Júbilo na Dor/Especialização em
            // Armadura): nothing in the app computes incoming damage for
            // a "roll and subtract" reduction to plug into. "Uma vez por
            // rodada" isn't enforced either. Fully self-reported.
        ]);

        Power::create([
            'id' => 97,
            'name' => 'Corte Ágil',
            'description' => 'Uma vez por rodada, quando faz um ataque com uma arma ágil ou leve, você pode gastar 1 PM para se mover até metade do seu deslocamento antes ou depois de fazer o ataque. Esse movimento não ativa reações dos inimigos (como de Ataque Reflexo).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'corte_agil_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — movement and reaction-avoidance are both
            // permanently self-reported categories (no board/grid, no
            // positioning system — see combat-engine-plans.md's "No
            // board, no grid"). "Uma vez por rodada" and the weapon
            // condition (ágil ou leve, same data shape as Esgrimista)
            // aren't enforced either. Fully self-reported.
        ]);

        Power::create([
            'id' => 98,
            'name' => 'Criar Oportunidade',
            'description' => 'Quando você ou um aliado em alcance curto atacar uma criatura sob efeito do seu Xadrez de Batalha, você pode gastar 1 PM para que esse ataque cause +1d10 pontos de dano. <br><br>No APP, adicione manualmente o dano extra.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'criar_oportunidade_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'power', 'power_id' => 99], // Xadrez de Batalha — confirmed against the book, the one dependent power its per_dependent_power scaling actually counts
            ],
            // No effects — depends on Xadrez de Batalha's active state
            // and an ally-targeted bonus damage roll, same
            // multi-participant resolution gap as Tornado de Dor/
            // Arremesso de Investida. Fully self-reported.
        ]);

        Power::create([
            'id' => 99,
            'name' => 'Xadrez de Batalha',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para analisar um oponente em alcance curto. Se fizer isso, você recebe +2 na Defesa e em testes de Reflexos contra essa criatura até o fim da cena. Esse bônus aumenta em +1 para cada outro poder que você possua que tenha Xadrez de Batalha como pré-requisito.',
            'source' => 'class',
            // Real Ativar/Desativar toggle, same as Percepção Temporal —
            // is_active on/off, effects fold into standing Defesa/
            // Reflexos totals while on. The "contra essa criatura"
            // scoping isn't separately tracked (no per-enemy state) — the
            // toggle itself is the self-report: the player turns it on
            // while fighting the analyzed target and off once that fight
            // ends, same trust model as every other self-reported
            // condition.
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'xadrez_de_batalha_01.webp',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12], // treinado em Guerra
            ],
            'effects' => [
                // value is a parseable formula string — same idea as a
                // percent string like "50%" or an attribute code like
                // "knw", not a separate sibling field. Shape:
                // "<base>+<per-match>*per_dependent_power[<id,id,...>]" —
                // here: base 2, +1 for every OTHER power the character
                // has whose prerequisites contain
                // {type: 'power', power_id: X} for any X in the bracketed
                // list (just [99], this power's own id, for now — a
                // comma-separated list lets a future power scale off
                // multiple dependency roots at once). Not resolved yet —
                // parked. Criar Oportunidade (power 98) formally lists
                // this power as a prerequisite (confirmed against the
                // book — the only one that does), so once a resolver
                // exists this would correctly evaluate to 3, not 2, for
                // a character who has both.
                ['tag' => 'mod_def', 'op' => 'add', 'value' => '2+1*per_dependent_power[99]'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => '2+1*per_dependent_power[99]'], // Reflexos
            ],
        ]);

        Power::create([
            'id' => 100,
            'name' => 'Defesa Estratégica',
            'description' => 'Você soma sua Inteligência na Defesa, limitada pelo seu nível.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'defesa_estrategica_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
                ['type' => 'attribute', 'attribute' => 'int', 'min' => 1],
            ],
            'effects' => [
                // Same "attribute-code value + level cap" shape as
                // Percepção Temporal/Arqueiro, no stack_group — no "não
                // cumulativo" clause in the text.
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 'int', 'limit' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 101,
            'name' => 'Determinação Inabalável',
            'description' => 'Enquanto estiver com metade dos seus pontos de vida ou menos, você recebe +2 em testes de resistência e o custo de sua habilidade Durão diminui em –1 PM. <br><br>No APP, depois que usar Durão, adicione 1 PM de volta.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'determinacao_inabalavel_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 11], // Guerreiro 11
            ],
            'effects' => [
                // New field: requires_hp_at_or_below — unlike range/
                // position, this is real checkable data (current_pv vs
                // maxPv, already live on the sheet), so a real reusable
                // condition instead of permanent self-report. Percent
                // string, same convention as damage_reduction/ignore_dr.
                // Durão's PM discount isn't tagged — see the description's
                // <br><br>No APP note instead.
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2, 'requires_hp_at_or_below' => '50%'], // Fortitude
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2, 'requires_hp_at_or_below' => '50%'], // Reflexos
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2, 'requires_hp_at_or_below' => '50%'], // Vontade
            ],
        ]);

        Power::create([
            'id' => 102,
            'name' => 'Estrategista Inspirador',
            'description' => 'Em seu primeiro turno de um combate, você pode gastar uma ação padrão e fazer um teste de Guerra. Se fizer isso, para cada 10 pontos no resultado do teste, você e seus aliados em alcance curto recebem 1 PM temporário. Esses PM temporários desaparecem no fim da cena. <br><br>No APP, adicione manualmente os PM temporários.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'estrategista_inspirador_01.webp',
            'action_cost' => 'standard',
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 12], // treinado em Guerra
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — ally-targeted, scales with a skill test result
            // (not a fixed value), and "primeiro turno de um combate"
            // needs turn tracking that doesn't exist. Same
            // multi-participant resolution gap as Tornado de Dor/Criar
            // Oportunidade/Estrategista's own Xadrez de Batalha-family
            // powers. Fully self-reported.
        ]);

        Power::create([
            'id' => 103,
            'name' => 'Executor',
            'description' => 'Você recebe +1d6 nas rolagens de dano contra criaturas que estejam com menos da metade dos pontos de vida. A cada quatro níveis além do 1º, esse dano extra aumenta em um passo.',
            'source' => 'class',
            // Target HP isn't tracked anywhere (unlike the character's own
            // current_pv) — no condition field to check against, so this
            // is a plain roll_active checkbox: the player judges "is the
            // target below half HP?" themselves and checks the box if so.
            'usability' => 'roll_active',
            'icon_file_name' => 'executor_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // op: 'extra_die' — an added die rolled separately from the
                // weapon's own base_dmg (own breakdown line, never scaled by
                // a future crit multiplier — only the weapon's die is,
                // see claude-stuff/tag-system.md). die_steps_per_levels
                // steps the die size up by one (1d6->1d8->1d10->...) for
                // every N levels past level 1 — not resolved yet.
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'die_steps_per_levels' => 4],
            ],
        ]);

        Power::create([
            'id' => 104,
            'name' => 'Fender Defesas',
            'description' => 'Quando você acerta um ataque usando Ataque Especial, a criatura sofre uma penalidade na Defesa igual ao total de PM gastos nessa habilidade por 1 rodada.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'fender_defesas_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // No effects — a debuff applied to the TARGET (not the
            // character), dynamically scaled by however much PM was
            // spent on Ataque Especial in that same instance, for a
            // fixed duration. No enemy-state tracking exists to apply a
            // Defesa penalty to. Fully self-reported.
        ]);

        Power::create([
            'id' => 105,
            'name' => 'Inércia do Aço',
            'description' => 'Quando acerta um ataque com uma arma de duas mãos em uma criatura, você pode gastar 3 PM para causar metade do dano desse ataque a cada inimigo adjacente a essa criatura.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inercia_do_aco_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5], // Guerreiro 5
            ],
            // weapon_grip gates whether this power is even
            // relevant to surface in a self-report checklist (e.g. the
            // planned attack-mode picker) — legitimate, real, checkable
            // data, independent of the mechanic below being unmodeled.
            'visibility_reqs' => ['weapon_grip' => 'two_hand'],
            // No effects — splash damage to every adjacent enemy is a
            // multi-target mechanic, same resolution gap as Tornado de
            // Dor. No combat engine planned, so this isn't "not resolved
            // yet" — there's no realistic consumer for the splash math
            // ever, tag or no tag. Fully self-reported; the checklist
            // entry (once built) is just a reminder to spend 3 PM and
            // apply it manually.
        ]);

        Power::create([
            'id' => 106,
            'name' => 'Investida Ricochete',
            'description' => 'Uma vez por rodada, quando faz uma investida e acerta o ataque, você pode gastar 2 PM para atacar outra criatura que você consiga alcançar como parte dessa investida.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'investida_ricochete_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 80], // Bater e Correr
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5], // Guerreiro 5
            ],
            // No effects — a second attack against a different target is
            // multi-target, same resolution gap as Tornado de Dor/
            // Inércia do Aço. "Uma vez por rodada" isn't enforced either.
            // Fully self-reported.
        ]);

        Power::create([
            'id' => 107,
            'name' => 'Manobra Dupla',
            'description' => 'Uma vez por rodada, quando faz uma manobra de combate usando uma arma versátil, você pode pagar 1 PM para executar uma manobra diferente extra.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'manobra_dupla_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            // "arma versátil" is real, checkable data (weapon_abilities id
            // 9), so it's worth gating visibility even though the
            // maneuver mechanic itself stays unmodeled.
            'visibility_reqs' => ['weapon_ability' => 9], // Versátil
            // No effects — an extra combat maneuver has no mechanical
            // resolution built (no maneuver system at all). "Uma vez por
            // rodada" isn't enforced either. Fully self-reported.
        ]);

        Power::create([
            'id' => 108,
            'name' => 'Mente Disciplinada',
            'description' => 'Sempre que você é afetado por uma habilidade de um aliado que fornece um bônus numérico em testes de perícia, rolagens de dano ou na Defesa, para você esse bônus aumenta em +1. <br><br>No APP, adicione manualmente o bônus extra.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'mente_disciplinada_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 6], // Guerreiro 6
            ],
            // No effects
        ]);

        Power::create([
            'id' => 109,
            'name' => 'Ordens de Engajamento',
            'description' => 'Uma vez por rodada, quando acerta um ataque em uma criatura sob efeito do seu Xadrez de Batalha, você pode gastar 2 PM para que um aliado em alcance curto possa fazer um ataque contra essa criatura.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'ordens_de_engajamento_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 98], // Criar Oportunidade
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 11], // Guerreiro 11
            ],
            // No effects — ally-targeted extra attack, same multi-
            // participant resolution gap as Criar Oportunidade/Tornado de
            // Dor. Note: doesn't count toward Xadrez de Batalha's
            // per_dependent_power scaling — its own prerequisite is Criar
            // Oportunidade (power 98), not Xadrez de Batalha (power 99)
            // directly, and that scaling is a direct-dependent count, not
            // transitive. "Uma vez por rodada" isn't enforced either.
            // Fully self-reported.
        ]);

        Power::create([
            'id' => 110,
            'name' => 'Operações Combinadas',
            'description' => 'Quando usa Ordens de Engajamento, você pode gastar +3 PM. Se fizer isso, pode atacar junto do aliado e, se um de vocês usar habilidades com custo em PM que forneçam bônus a esse ataque ou a seu dano, o outro também é afetado (apenas se isso for aplicável ao ataque).',
            'source' => 'class',
            // PM-costed add-on tied to another specific power's
            // activation moment, same shape as Bater e Correr's upgrade
            // half — active, not roll_active (doesn't ride your own
            // roll).
            'usability' => 'active',
            'icon_file_name' => 'operacoes_combinadas_01.webp',
            'pm_cost' => 3, // additional, on top of Ordens de Engajamento's own 2 PM
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 109], // Ordens de Engajamento
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 14], // Guerreiro 14
            ],
            // No effects — shared-attack participation plus conditionally
            // sharing whichever OTHER PM-costed bonuses either combatant
            // uses is well beyond a single-target mod_hit/mod_dmg tag,
            // same multi-participant gap as Ordens de Engajamento/Criar
            // Oportunidade. Fully self-reported.
        ]);

        Power::create([
            'id' => 111,
            'name' => 'Recuperar Fôlego',
            'description' => 'Uma vez por cena, se estiver com 0 PM, você pode gastar uma ação de movimento para recuperar 1d8 PM.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'recuperar_folego_01.webp',
            'action_cost' => 'movement',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // Reuses the existing restore_pm tag (same shape as
                // Essência de Mana). "Se estiver com 0 PM" and "uma vez
                // por cena" aren't enforced — self-reported, same as
                // every other once-per/condition clause on active powers.
                ['tag' => 'restore_pm', 'op' => 'roll', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 112,
            'name' => 'Resiliência Marcial',
            'description' => 'Sempre que sofrer dano letal, você recebe redução de dano 1 cumulativa (limitada pelo seu nível). Esse efeito dura até o fim da cena ou até você recuperar pontos de vida de qualquer forma.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'resiliencia_marcial_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 4], // Guerreiro 4
            ],
            // No effects — same damage_reduction gap as Durão/Júbilo na
            // Dor/Especialização em Armadura, no incoming-damage
            // calculation to plug into.
        ]);

        Power::create([
            'id' => 113,
            'name' => 'Soldado de Infantaria',
            'description' => 'Você recebe +3m em seu deslocamento e seu limite de carga aumenta em 6 espaços.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'soldado_da_infantaria_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1]], // Guerreiro
            ],
            'effects' => [
                // New tags: mod_movement, mod_inventory_space. Neither is
                // resolved yet — calculateMaxSlots (max-slots.ts) only
                // factors in base_str today, no powers/effects lookup at
                // all; there's no Movimento stat displayed on the sheet
                // yet either. Both are real near-term additions (a
                // planned Movimento row, and threading powers into
                // calculateMaxSlots the same way calculateDefense/
                // calculateMaxPv/calculateMaxPm already do), not
                // speculative — parked for real, not just "cheap to tag."
                ['tag' => 'mod_movement', 'op' => 'add', 'value' => 3],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 6],
            ],
        ]);

        Power::create([
            'id' => 114,
            'name' => 'Velho de Guerra',
            'description' => 'Seus olhos já viram muito e você não se abala facilmente. Você recebe +5 em Intimidação e imunidade a medo. Além disso, uma vez por cena pode gastar 5 PM para evitar completamente um efeito qualquer (ataque, magia etc.) usado contra você por outra criatura. Se o efeito for de área ou tiver outros alvos, continua funcionando normalmente contra eles. <br><br>No APP, use os PM manualmente quando ativar o poder.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'velho_de_guerra_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 17], // Guerreiro 17
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 5], // Intimidação
            ],
        ]);

        Power::create([
            'id' => 115,
            'name' => 'Golpe Pessoal',
            'description' => 'Quando faz um ataque, você pode desferir seu Golpe Pessoal, uma técnica única, com efeitos determinados por você. Você constrói seu Golpe Pessoal escolhendo efeitos da lista a seguir. Cada efeito possui um custo; a soma deles será o custo do Golpe Pessoal (mínimo 1 PM). O Golpe Pessoal só pode ser usado com uma arma específica (por exemplo, apenas espadas longas). Quando sobe de nível, você pode reconstruir seu Golpe Pessoal e alterar a arma que ele usa. Você pode escolher este poder outras vezes para golpes diferentes e não pode gastar mais PM em golpes pessoais em uma mesma rodada do que seu limite de PM.',
            'source' => 'class',
            // Rides the attack roll like any other roll_active power, but
            // the actual golpe selection/build lives entirely in
            // character_golpes_pessoais — this row is only what makes the
            // "Golpe Pessoal" card exist on the sheet at all (one row per
            // character.active_effects' own unique(character_id, power_id)
            // constraint), never itself queried for the mechanic. Picking
            // this power again for another golpe just adds another
            // character_golpes_pessoais row, not another active_effects
            // row — see claude-stuff/tag-system.md.
            'usability' => 'roll_active',
            'icon_file_name' => 'golpe_pessoal_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1], 'min_level' => 5], // Guerreiro 5
            ],
            // No effects — every menu item (Elemental, Brutal, Letal, etc.)
            // is its own 'specific'-source power referenced by id from
            // character_golpes_pessoais.power_ids, resolved live by
            // whichever bespoke UI/resolver handles Golpe Pessoal, never
            // generically via this power's own effects. Weapon restriction
            // is self-reported (see Especialização em Arma), not modeled.
            // "não pode gastar mais PM em golpes pessoais em uma mesma
            // rodada do que seu limite de PM" isn't enforced — self-
            // reported like every other PM-spend limit in the app.
        ]);

        // Golpe Pessoal menu options (source: 'specific') — never
        // independently held/picked, only ever referenced by id from
        // character_golpes_pessoais.power_ids. usability is required by
        // the schema but unused for this source (nothing resolves these
        // through the normal usability-gated checklist path) — set to
        // 'passive' arbitrarily, same as every other 'specific' power.
        Power::create([
            'id' => 116,
            'name' => 'Conjurador',
            'description' => 'Escolha uma magia de 1º ou 2º círculos que tenha como alvo uma criatura ou que afete uma área. Se acertar seu golpe, você lança a magia como uma ação livre, tendo como alvo a criatura atingida ou como centro de sua área o ponto atingido pelo ataque (atributo-chave é um mental a sua escolha). Considere que a mão da arma está livre para lançar esta magia.',
            'source' => 'specific',
            'usability' => 'passive',
            // pm_cost is only the flat "+1 PM" part of "Custo da Magia +
            // 1 PM" — the variable spell-cost half can't be modeled, no
            // spellcasting system exists in the app at all.
            'pm_cost' => 1,
            // TODO: no effects — needs a full spellcasting system (spell
            // list, circles, casting a spell as a free action on hit) that
            // doesn't exist anywhere in the app yet. Self-reported for now.
        ]);

        Power::create([
            'id' => 117,
            'name' => 'Amplo',
            'description' => 'Seu ataque atinge todas as criaturas em alcance curto (incluindo aliados, mas não você mesmo). Faça um único teste de ataque e compare com a Defesa de cada criatura. <br><br>No APP, sem efeitos automáticos além do custo de PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 3,
            // No effects — multi-target/AoE, same resolution gap as every
            // other AoE power this session (Tornado de Dor etc.). No
            // combat engine planned, so this isn't "not resolved yet,"
            // there's no realistic resolver ever. Just wastes the PM,
            // fully self-reported.
        ]);

        Power::create([
            'id' => 118,
            'name' => 'Atordoante',
            'description' => 'Uma criatura que sofra dano do ataque fica atordoada por uma rodada (apenas uma vez por cena; Fortitude CD For anula). <br><br>No APP, sem efeitos automáticos além do custo de PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            // No effects — inflicts a condition on the TARGET (not the
            // character), gated by a save DC and a once-per-scene limit.
            // Target/enemy state is never trackable (same reason
            // Atordoante/Paralisante/Desconcertante-style effects go
            // untagged everywhere else) — no realistic resolver, ever.
            // Just wastes the PM, fully self-reported.
        ]);

        Power::create([
            'id' => 119,
            'name' => 'Destruidor',
            'description' => 'Aumenta o multiplicador de crítico em +1.',
            // Same name as the unrelated class power "Destruidor" (id 81,
            // reroll_dice_below) — different power, different mechanic,
            // just a naming collision from the sourcebook itself.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            'effects' => [
                ['tag' => 'mod_multiplier', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 120,
            'name' => 'Elemental',
            'description' => 'Causa +2d6 pontos de dano de ácido, eletricidade, fogo ou frio. Você pode escolher este efeito mais vezes para aumentar o dano em +2d6 (do mesmo tipo ou de outro), por +2 PM a cada vez.',
            // Repeatable, no stated cap — handled by allowing this id to
            // repeat in a character_golpes_pessoais row's power_ids
            // (frontend-side chain-of-checkboxes UI, no schema change
            // needed). Each occurrence's own extra_die entry and pm_cost
            // both sum naturally from however many times the id repeats.
            // Element type (ácido/eletricidade/fogo/frio) is cosmetic —
            // damage type isn't tracked per roll anywhere in the app.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d6'],
            ],
        ]);

        Power::create([
            'id' => 121,
            'name' => 'Letal',
            'description' => 'Aumenta a margem de ameaça em +2. Você pode escolher este efeito duas vezes para aumentar a margem de ameaça em +5.',
            // Repeatable, capped at twice (frontend-side, same as Elemental
            // — no schema change). PM is cleanly additive (2 PM per pick),
            // but the margin bonus is NOT: two picks total -5, not -2-2=
            // -4. Naively summing this tag's value per occurrence in
            // power_ids gets the wrong number — the future golpe-pessoal-
            // solver needs to special-case "this id picked twice" rather
            // than sum generically. value here is the single-pick amount.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            'effects' => [
                // Negative — a wider threat range means a LOWER base_margin
                // number, and op: 'add' always means "add this signed
                // value directly" (same convention as mod_hit's -2
                // penalties), never a magnitude with an implied direction.
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 122,
            'name' => 'Sequencial',
            'description' => 'Seu golpe causa +1d6 pontos de dano. A cada vez que você acerta o golpe na mesma cena, esse bônus aumenta em um passo. <br><br>No APP, sem efeitos automáticos além do custo de PM. Role manualmente o bônus atual de dano extra.',
            // Needs a scene-persistent "how many times hit this scene"
            // counter — nothing in the app tracks that, same genuinely-new
            // gap as Sifão. Not self-report-and-forget either, since the
            // step size actually changes hit to hit — the player just
            // calculates it manually.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            // No effects — self-reported/manually calculated.
        ]);

        Power::create([
            'id' => 123,
            'name' => 'Sifão',
            'description' => 'Você recebe 1 PM temporário para cada 10 pontos da rolagem de dano. Você pode receber um máximo de PM temporários por cena igual ao seu nível e eles desaparecem no fim da cena. No APP, sem efeitos automáticos além do custo de PM. Adicione manualmente o PM temporário.',
            // Deliberately no `temp_pm` tag despite it existing (Êxtase na
            // Loucura) — that tag is a single flat pool with no concept of
            // per-source expiration/caps, and Sifão's own rule (expires
            // end of scene, capped at character level) can't share that
            // pool correctly alongside any other temp_pm source without
            // tracking which portion came from where. Not worth building
            // per-source tracking for one power — plainly self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            // No effects — self-reported/manually calculated.
        ]);

        Power::create([
            'id' => 124,
            'name' => 'Avanço',
            'description' => 'Você pode percorrer até o seu deslocamento em linha reta antes de desferir o golpe. <br><br>No APP, sem efeitos automáticos além do custo de PM.',
            // No effects — pure positioning, no board/grid exists to move
            // on (same reasoning as powers.range's own comment). Fully
            // self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 125,
            'name' => 'Brutal',
            'description' => 'Fornece um dado extra de dano do mesmo tipo.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [
                // value: 'weapon_die' — new sentinel, not a fixed dice
                // notation like Elemental's '2d6'. Just means "reroll
                // whatever weapon is already being used for this attack"
                // (the same one already rolled as Dados da Arma) — nothing
                // about the golpe's own configuration, since weapon
                // restriction isn't tracked anywhere (self-reported).
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'weapon_die'],
            ],
        ]);

        Power::create([
            'id' => 126,
            'name' => 'Carregado',
            'description' => 'Você pode gastar uma ação padrão para energizar seu ataque. Se você fizer isso e atacar até a próxima rodada, seu ataque causa +2d8 pontos de dano. <br><br>No APP, o dano será adicionado automaticamente, mas você deve gastar a ação padrão manualmente.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d8'],
            ],
            // The "spend a standard action to charge, then attack by next
            // round" condition isn't tracked — self-reported, same as
            // every other once-per/cross-turn condition. Only the +2d8
            // itself is modeled.
        ]);

        Power::create([
            'id' => 127,
            'name' => 'Distante',
            'description' => 'Aumenta o alcance em um passo (de corpo a corpo para curto, médio e longo). Outras características não mudam (um ataque corpo a corpo com alcance curto continua usando Luta e somando sua Força no dano).',
            // No effects — no distance/range-band mechanic exists (no
            // board/grid, same reasoning as powers.range). Just wastes the
            // PM, fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 128,
            'name' => 'Impactante',
            'description' => 'Empurra o alvo 1,5m para cada 10 pontos de dano causado (arredondado para baixo). Por exemplo, 3m para 22 pontos de dano.',
            // New tag: push_distance — formula string "<meters>/<damage>",
            // read as floor(damage / <damage>) * <meters>. No board/grid
            // exists to actually move the target on, so this is purely an
            // informational readout after the damage roll (frontend
            // computes and displays "empurra X metros") — the player
            // narrates/applies the shove themselves.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [
                ['tag' => 'push_distance', 'op' => 'add', 'value' => '1.5m/10damage'],
            ],
        ]);

        Power::create([
            'id' => 129,
            'name' => 'Preciso',
            'description' => 'Quando faz o teste de ataque, você rola dois dados e usa o melhor resultado.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [
                // New tag: advantage — a binary capability (op: grant), not
                // a summed value, since it changes how the die itself is
                // rolled rather than modifying a total. scope says which
                // roll it applies to, reusable across hit/dmg/skill rolls
                // instead of a distinct tag per roll type.
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'hit'],
            ],
        ]);

        Power::create([
            'id' => 130,
            'name' => 'Qualquer Arma',
            'description' => 'Você pode usar seu Golpe Pessoal com qualquer tipo de arma.',
            // No effects — weapon restriction isn't tracked anywhere in
            // the first place (self-reported, see Especialização em Arma),
            // so there's nothing here to actually bypass. Just wastes the
            // PM, fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 131,
            'name' => 'Ricocheteante',
            'description' => 'A arma volta pra você após o ataque. Só pode ser usado com armas de arremesso.',
            // No effects — thrown-weapon return/ammo bookkeeping isn't
            // modeled anywhere. Just wastes the PM, fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 132,
            'name' => 'Teleguiado',
            'description' => 'Ignora penalidades por camuflagem ou cobertura leves.',
            // No effects — no camouflage/cover mechanic exists anywhere in
            // the app. Just wastes the PM, fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 133,
            'name' => 'Brando',
            'description' => 'Seu golpe causa dano não letal.',
            // No effects — damage type isn't tracked per roll anywhere in
            // the app (same as Elemental's element type). Costs nothing,
            // fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 0,
        ]);

        Power::create([
            'id' => 134,
            'name' => 'Golpe de Abertura',
            'description' => 'Seu golpe só pode ser usado em seu primeiro turno do combate.',
            // No effects — a once-per-combat timing restriction, self-
            // reported like every other once-per condition. Negative
            // pm_cost is a real discount toward the golpe's total PM cost
            // (there's a per-round PM spend cap on golpes pessoais equal
            // to Guerreiro level), not a placeholder — sums normally with
            // every other menu item's pm_cost.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 135,
            'name' => 'Truque Secreto',
            'description' => 'Seu golpe só pode ser usado uma vez contra cada alvo por cena.',
            // No effects — once-per-target-per-scene restriction, self-
            // reported. Negative pm_cost is a real discount, same as
            // Golpe de Abertura.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 136,
            'name' => 'Lento',
            'description' => 'Seu ataque exige uma ação completa para ser usado.',
            // No effects — action_cost isn't tracked per-golpe
            // (character_golpes_pessoais has no such field), self-
            // reported. Negative pm_cost is a real discount, same as the
            // other -2 entries.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 137,
            'name' => 'Perto da Morte',
            'description' => 'O ataque só pode ser usado se você estiver com um quarto de seus PV ou menos. <br><br>No APP, sem efeitos automáticos além da redução de custo de PM. Siga a regra dos 1/4 PV manualmente.',
            // No effects — requires_hp_at_or_below doesn't apply here,
            // that field gates a specific EFFECT's contribution to a
            // total (Determinação Inabalável), and this power has no
            // numeric effect to attach it to. It's a pure usage
            // precondition on the whole golpe, self-reported like Golpe
            // de Abertura/Truque Secreto/Lento.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 138,
            'name' => 'Sacrifício',
            'description' => 'Sempre que usa seu Golpe Pessoal, você perde 10 PV.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
            'effects' => [
                ['tag' => 'self_damage', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 139,
            'name' => 'Penetrante',
            'description' => 'Ignora 10 pontos de RD.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => 10],
            ],
        ]);
    }
}
