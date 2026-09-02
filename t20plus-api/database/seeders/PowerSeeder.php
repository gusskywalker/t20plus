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
                'type' => 'class_granted',
                'usability' => 'roll_toggle',
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
            'usability' => 'active',
            'action_cost' => 'complete',
        ]);

        Power::create([
            'id' => 7,
            'name' => 'Vontade de Ferro',
            'description' => 'Você recebe +1 PM para cada dois níveis de personagem e +2 em Vontade.',
            'type' => 'general',
            'usability' => 'passive',
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
            'trigger_on' => ['targets_you_tormenta'],
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
            'type' => 'divine_granted',
            'usability' => 'trigger',
            'trigger_on' => ['enemy_fails_save_vontade'],
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
            'type' => 'divine_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
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
            'trigger_on' => ['targets_you_spell_divine'],
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
            'type' => 'item_granted',
            'usability' => 'trigger',
            'trigger_on' => ['enemy_is_hit_critical'],
            'effects' => [
                [
                    'tag' => 'condition',
                    'op' => 'inflict',
                    'condition_id' => 1, // Sangrando
                    'removal_check' => 'con', // raw Constituição test, not a skill
                    'removal_cd' => 15,
                    'removal_frequency' => 'turn',
                ],
            ],
        ]);

        Power::create([
            'id' => 14,
            'name' => 'Arma - Matéria Vermelha',
            'description' => 'Poder concedido por armas cobertas de matéria vermelha. Causa +1d6 de dano extra ao acertar, mas o usuário perde 1 ponto de vida.',
            'type' => 'item_granted',
            'usability' => 'trigger',
            'trigger_on' => ['enemy_is_hit'],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => '1d6'],
                ['tag' => 'self_damage', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15,
            'name' => 'Armadura/Escudo Leve - Matéria Vermelha',
            'description' => 'Poder concedido por armaduras leves ou escudos cobertos de matéria vermelha. Ataques contra o usuário têm 10% de chance de falhar automaticamente.',
            'type' => 'item_granted',
            'usability' => 'passive',
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 16,
            'name' => 'Armadura Pesada - Matéria Vermelha',
            'description' => 'Poder concedido por armaduras pesadas cobertas de matéria vermelha. Ataques contra o usuário têm 25% de chance de falhar automaticamente.',
            'type' => 'item_granted',
            'usability' => 'passive',
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 25],
            ],
        ]);

        Power::create([
            'id' => 17,
            'name' => 'Esotérico - Matéria Vermelha (Portador)',
            'description' => 'Poder concedido por esotéricos cobertos de matéria vermelha. O usuário sofre -2 em testes de resistência contra efeitos mágicos.',
            'type' => 'item_granted',
            'usability' => 'passive',
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
            'type' => 'item_granted',
            'usability' => 'passive',
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
            'type' => 'item_granted',
            'usability' => 'passive',
            'effects' => [
                ['tag' => 'mod_dc', 'op' => 'add', 'value' => 1, 'scope' => 'bard_abilities_non_spell'],
            ],
        ]);

        Power::create([
            'id' => 20,
            'name' => 'Armamento Aberrante',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para produzir uma versão orgânica de qualquer arma corpo a corpo ou de arremesso com a qual seja proficiente — ela brota do seu braço, ombro ou costas como uma planta grotesca e então se desprende. O dano da arma aumenta em um passo para cada dois outros poderes da Tormenta que você possui. A arma dura pela cena, então se desfaz numa poça de gosma.',
            'type' => 'tormenta',
            'usability' => 'active',
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
            'type' => 'divine_granted',
            'usability' => 'active',
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
            'type' => 'divine_granted',
            'usability' => 'roleplay',
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
            'type' => 'divine_granted',
            'usability' => 'trigger',
            'trigger_on' => ['enemy_is_hit', 'you_take_damage'],
            'decay_after' => 1,
            'prerequisites' => [
                ['type' => 'god', 'god_id' => 1], // Aharadak
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 1, 'limit' => 'knw'],
            ],
        ]);

        Power::create([
            'id' => 24,
            'name' => 'Mediador da Tempestade',
            'description' => 'Você pode se comunicar com lefeu inteligentes (Int –3 ou maior) livremente e recebe +5 em testes de Diplomacia e Intuição com criaturas da Tormenta e devotos de Aharadak.',
            'type' => 'divine_granted',
            'usability' => 'roll_toggle',
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
            'type' => 'complication_granted',
            'usability' => 'passive',
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
            'type' => 'complication_granted',
            'usability' => 'passive',
            'effects' => [
                // New tag: mod_pv (Pontos de Vida) — same add_per_level
                // shape as mod_pm's "+1 PM a cada dois níveis", just
                // per_levels: 1 here since it's every level, not every two.
                ['tag' => 'mod_pv', 'op' => 'add_per_level', 'value' => -2, 'per_levels' => 1],
            ],
        ]);

        Power::create([
            'id' => 27,
            'name' => 'Catarata',
            'description' => 'Seus olhos já não são os mesmos. Você sofre –5 em Percepção e Pontaria.',
            'type' => 'complication_granted',
            'usability' => 'passive',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => -5], // Percepção
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 25, 'value' => -5], // Pontaria
            ],
        ]);

        Power::create([
            'id' => 28,
            'name' => 'Criança',
            'description' => 'Crianças são fisicamente mais fracas e frágeis que adultos, além de menos capazes de entender as sutilezas do mundo.',
            'type' => 'age_granted',
            'usability' => 'passive',
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
            'type' => 'age_granted',
            'usability' => 'passive',
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
            'type' => 'age_granted',
            'usability' => 'passive',
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
            'type' => 'age_granted',
            'usability' => 'passive',
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
            'type' => 'age_granted',
            'usability' => 'passive',
            'effects' => [
                ['tag' => 'mod_knw', 'op' => 'add', 'value' => -1], // Sabedoria
            ],
        ]);

        Power::create([
            'id' => 33,
            'name' => 'Ímpeto Juvenil',
            'description' => 'Você recebe +3 pontos de mana. Adolescentes acham que podem fazer qualquer coisa, e essa confiança os torna mais heroicos.',
            'type' => 'age_granted',
            'usability' => 'passive',
            'effects' => [
                ['tag' => 'mod_pm', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 34,
            'name' => 'Origem em Construção',
            'description' => 'Você recebe apenas um benefício de origem, em vez de dois (se sua origem possuir um único benefício, comece com uma perícia treinada a menos por sua classe).',
            'type' => 'age_granted',
            'usability' => 'passive',
            // No effects — same treatment as Sem Origem (power 30): this
            // restricts how many origin choice-groups step 4 lets the
            // player pick from, handled on the frontend, not a
            // resolver-facing effect. The power exists as a record.
        ]);

        Power::create([
            'id' => 35,
            'name' => 'Jovem',
            'description' => 'Você está na flor da idade, nem os percalços da juventude nem os fardos da maturidade o afetam.',
            'type' => 'age_granted',
            'usability' => 'passive',
            // No effects — Jovem is the baseline age bracket, no
            // modifiers. The power exists purely as a record.
        ]);

        Power::create([
            'id' => 36,
            'name' => 'Adulto',
            'description' => 'Você está em plena maturidade. Pode receber um Poder Geral extra e escolher uma Complicação de idade.',
            'type' => 'age_granted',
            'usability' => 'passive',
            // No effects — the bonus power/complication picks themselves
            // are what's granted (step 7's Poder Geral/Complicação (idade)
            // dropdowns), not a resolver-facing effect. The power exists
            // purely as a record.
        ]);

        Power::create([
            'id' => 37,
            'name' => 'Maduro',
            'description' => 'Você entra na meia-idade. Recebe um nível extra e duas Complicações de idade.',
            'type' => 'age_granted',
            'usability' => 'passive',
            // No effects — same reasoning as Adulto (power 36): the extra
            // level/complication picks are what's granted (step 7's
            // Classe/Complicação (idade) dropdowns), not a resolver-facing
            // effect. The power exists purely as a record.
        ]);

        Power::create([
            'id' => 38,
            'name' => 'Velho',
            'description' => 'Seu corpo já não responde como antes.',
            'type' => 'age_granted',
            'usability' => 'passive',
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
            'type' => 'age_granted',
            'usability' => 'passive',
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
            'description' => 'Você recebe proficiência em armas marciais',
            'type' => 'general',
            'usability' => 'passive',
        ]);

        Power::create([
            'id' => 41,
            'name' => 'Proficiência - Armas de Fogo',
            'description' => 'Você recebe proficiência em armas de fogo.',
            'type' => 'general',
            'usability' => 'passive',
        ]);

        Power::create([
            'id' => 42,
            'name' => 'Proficiência - Armaduras Pesadas',
            'description' => 'Você recebe proficiência em armaduras pesadas.',
            'type' => 'general',
            'usability' => 'passive',
        ]);

        Power::create([
            'id' => 43,
            'name' => 'Proficiência - Escudos',
            'description' => 'Você recebe proficiência em escudos.',
            'type' => 'general',
            'usability' => 'passive',
        ]);

        Power::create([
            'id' => 44,
            'name' => 'Proficiência - Arco de Guerra',
            'description' => 'Você recebe proficiência em arcos de guerra.',
            'type' => 'general',
            'usability' => 'passive',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 40], // Proficiência - Armas Marciais
            ]
        ]);

        Power::create([
            'id' => 45,
            'name' => 'Ímpeto',
            'description' => 'Você pode gastar 1 PM para aumentar seu deslocamento em +6m por uma rodada.',
            'type' => 'class',
            'usability' => 'active',
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
                    'type' => 'class',
                    'usability' => 'passive',
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
            'type' => 'general',
            'usability' => 'passive',
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
            'type' => 'general',
            'usability' => 'passive',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'con', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_pv', 'op' => 'add_per_level', 'value' => 1, 'per_levels' => 1],
                ['tag' => 'skill', 'skill_id' => 10, 'op' => 'add', 'value' => 2], // Fortitude
            ],
        ]);

        Power::create([
            'id' => 72,
            'name' => 'Ataque Poderoso',
            'description' => 'Sempre que faz um ataque corpo a corpo, você pode sofrer –2 no teste de ataque para receber +5 na rolagem de dano.',
            'type' => 'general',
            // Same as Ataque Especial — rides a roll the player is already
            // making, decided fresh every attack, never persists.
            'usability' => 'roll_toggle',
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
            'type' => 'consumable_granted',
            'usability' => 'active',
            'action_cost' => 'standard',
            'effects' => [
                ['tag' => 'restore_pm', 'op' => 'roll', 'value' => '1d4'],
                ['tag' => 'reduce_qty', 'op' => 'add', 'value' => -1],
            ],
        ]);
    }
}
