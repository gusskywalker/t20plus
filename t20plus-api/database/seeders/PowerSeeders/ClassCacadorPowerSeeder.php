<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ClassCacadorPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.

        // Same tiered/PM-gated shape as Ataque Especial — one power per
        // tier, gated by the class's min_level, PM cost climbing each
        // tier. Unrolled from a $markTiers/foreach loop 2026-09-08, same
        // reasoning as Ataque Especial's own unroll above.
        //
        // usability: active/duration: scene — matches the rule's own
        // "ação de movimento to mark, then it lasts until the fim da
        // cena" shape (same as Xadrez de Batalha/Percepção Temporal), not
        // a fresh per-roll checkbox. WHICH enemy is marked isn't tracked
        // (no target-tracking state exists) — the player just self-
        // reports "am I attacking my marked creature?" by leaving the
        // tier Ativado while they are, and Desativando/re-Ativando a
        // different tier when they switch targets. Only one tier can ever
        // be is_active at once — CharacterActiveEffectController::update()
        // deactivates every other Marca da Presa tier the moment one is
        // activated (a leveled-up Caçador holds every tier they've
        // unlocked as a separate granted power simultaneously, same as
        // Ataque Especial, but only one should ever apply at a time).
        // attack-modal.ts's currentlyActivePowerRows() picks up whichever
        // tier is currently is_active automatically — no checklist entry,
        // its extra_die still gets its own named breakdown line exactly
        // like a checked roll_active power would.
        Power::create([
            'id' => 196,
            'name' => 'Marca da Presa (1d4)',
            'description' => 'Você pode gastar uma ação de movimento e 1PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +1d4 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 1], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d4'],
            ],
        ]);

        Power::create([
            'id' => 197,
            'name' => 'Marca da Presa (1d8)',
            'description' => 'Você pode gastar uma ação de movimento e 2PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +1d8 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 198,
            'name' => 'Marca da Presa (1d12)',
            'description' => 'Você pode gastar uma ação de movimento e 3PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +1d12 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 9], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d12'],
            ],
        ]);

        Power::create([
            'id' => 199,
            'name' => 'Marca da Presa (2d8)',
            'description' => 'Você pode gastar uma ação de movimento e 4PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +2d8 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 4,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 13], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d8'],
            ],
        ]);

        Power::create([
            'id' => 200,
            'name' => 'Marca da Presa (2d10)',
            'description' => 'Você pode gastar uma ação de movimento e 5PM para analisar uma criatura em alcance curto. Até o fim da cena, você recebe +2d10 nas rolagens de dano contra essa criatura. <br><br>No APP, você ativa Marca da Presa e os PMs são gastos automaticamente. O dano extra é aplicado enquanto o poder estiver ativo. Para usar em um novo alvo, desative e reative o poder para gastar os PMs.',
            'source' => 'class_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'marca_da_presa_01.webp',
            'pm_cost' => 5,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 17], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d10'],
            ],
        ]);

        Power::create([
            'id' => 201,
            'name' => 'Rastreador',
            'description' => 'Você recebe +2 em Sobrevivência. Além disso, pode se mover com seu deslocamento normal enquanto rastreia sem sofrer penalidades no teste de Sobrevivência.',
            'source' => 'class_granted',
            'usability' => 'passive',
            'icon_file_name' => 'rastreador_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 1], // Caçador
            ],
            'effects' => [
                // The movement-while-tracking clause is fluff — no
                // Sobrevivência-while-moving penalty is modeled anywhere to
                // waive in the first place.
                ['tag' => 'skill', 'skill_id' => 28, 'op' => 'add', 'value' => 2], // Sobrevivência
            ],
        ]);

        Power::create([
            'id' => 202,
            'name' => 'Explorador',
            'description' => 'No 3º nível, escolha um tipo de terreno entre aquático, ártico, colina, deserto, floresta, montanha, pântano, planície, subterrâneo ou urbano. A partir do 11º nível, você também pode escolher área de Tormenta. Quando estiver no tipo de terreno escolhido, você soma sua Sabedoria (mínimo +1) na Defesa e nos testes de Acrobacia, Atletismo, Furtividade, Percepção e Sobrevivência. A cada quatro níveis, escolha outro tipo de terreno para receber o bônus ou aumente o bônus em um tipo de terreno já escolhido em +2. <br><br>No APP, ative o poder quando estiver no terreno escolhido.',
            'source' => 'class_granted',
            // Real Ativar/Desativar toggle, same as Xadrez de Batalha — no
            // terrain-type tracking exists, so the toggle itself is the
            // self-report: the player turns it on while in their chosen
            // terrain and off otherwise. The 4-level terrain-count/+2
            // scaling and the "mínimo +1" floor aren't modeled — value is
            // just the character's raw Sabedoria bonus, per the user.
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'explorador_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 3], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 'knw'],
                ['tag' => 'skill', 'skill_id' => 1, 'op' => 'add', 'value' => 'knw'], // Acrobacia
                ['tag' => 'skill', 'skill_id' => 3, 'op' => 'add', 'value' => 'knw'], // Atletismo
                ['tag' => 'skill', 'skill_id' => 11, 'op' => 'add', 'value' => 'knw'], // Furtividade
                ['tag' => 'skill', 'skill_id' => 23, 'op' => 'add', 'value' => 'knw'], // Percepção
                ['tag' => 'skill', 'skill_id' => 28, 'op' => 'add', 'value' => 'knw'], // Sobrevivência
            ],
        ]);

        Power::create([
            'id' => 203,
            'name' => 'Mestre Caçador',
            'description' => 'No 20º nível, você pode usar a habilidade Marca da Presa como uma ação livre. Além disso, quando usa a habilidade, pode pagar 5 PM para aumentar sua margem de ameaça contra a criatura em +2. Se você reduz uma criatura contra a qual usou Marca da Presa a 0 pontos de vida, recupera 5 PM. <br><br>No APP, adicione os PMs manualmente quando reduzir um inimigo a 0 PV.',
            'source' => 'class_granted',
            // Free-action Marca da Presa and the 5-PM PV-recovery-on-kill
            // clause are fluff — no action-cost distinction or kill-
            // tracking exists to model either. Just the +2 threat-range
            // widen (mod_margin is negative to widen, see tag-library.md),
            // self-reported per roll like every other conditional bonus.
            'usability' => 'roll_active',
            'icon_file_name' => 'mestre_cacador_01.webp',
            'pm_cost' => 5,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 20], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 204,
            'name' => 'Armadilha: Arataca',
            'description' => 'A vítima sofre 2d6 pontos de dano de perfuração e fica agarrada. Uma criatura agarrada pode escapar com uma ação padrão e um teste de Força ou Acrobacia (CD Sab). <br><br>No APP, ativando, você gastará os PMs. O resto é com você!',
            'source' => 'class',
            // Armadilhas shared shape (see claude-stuff comments/session
            // notes) — prepare costs a complete action + 3 PM, resolves
            // instantly (no duration). Damage/grapple/escape aren't
            // modeled — self-reported like every other unresolvable effect.
            'usability' => 'active',
            'icon_file_name' => 'armadilha_arataca_01.webp',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
        ]);

        Power::create([
            'id' => 205,
            'name' => 'Armadilha: Espinhos',
            'description' => 'A vítima sofre 6d6 pontos de dano de perfuração. Um teste de Reflexos (CD Sab) reduz o dano à metade.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'armadilha_espinhos_01.webp',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
        ]);

        Power::create([
            'id' => 206,
            'name' => 'Armadilha: Laço',
            'description' => 'A vítima deve fazer um teste de Reflexos (CD Sab). Se passar, fica caída. Se falhar, fica agarrada. Uma criatura agarrada pode se soltar com uma ação padrão e um teste de Força ou Acrobacia (CD Sab).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'armadilha_laco_01.webp',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
        ]);

        Power::create([
            'id' => 207,
            'name' => 'Armadilha: Rede',
            'description' => 'Todas as criaturas na área ficam enredadas e não podem sair da área. Uma vítima pode se libertar com uma ação padrão e um teste de Força ou Acrobacia (CD 25). Além disso, a área ocupada pela rede é considerada terreno difícil. Nesta armadilha você escolhe quantas criaturas precisam estar na área para ativá-la.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'armadilha_rede_01.webp',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
        ]);

        Power::create([
            'id' => 208,
            'name' => 'Armadilheiro',
            'description' => 'Você soma sua Sabedoria no dano e na CD de suas armadilhas (cumulativo).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'armadilheiro_01.webp',
            'prerequisites' => [
                // "um poder de armadilha" — any one of the 4 Armadilha
                // powers, not a specific one (see tag-library.md's
                // power_ids_any).
                ['type' => 'power', 'power_ids_any' => [204, 205, 206, 207]],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5], // Caçador 5
            ],
            // No effects — armadilha damage/CD aren't modeled at all (see
            // the Armadilha powers themselves), so there's nothing for a
            // Sabedoria bonus to add onto.
        ]);

        Power::create([
            'id' => 209,
            'name' => 'Bote',
            'description' => 'Se estiver empunhando duas armas e fizer uma investida, você pode pagar 1 PM para fazer um ataque adicional com sua arma secundária.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'bote_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 77], // Ambidestria
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 6], // Caçador 6
            ],
            // No effects — the extra attack itself (dual-wielding +
            // investida) is self-reported, same treatment as Ambidestria's
            // own second-attack clause.
        ]);

        Power::create([
            'id' => 210,
            'name' => 'Camuflagem',
            'description' => 'Você pode gastar 2 PM para se esconder mesmo sem camuflagem ou cobertura disponível.',
            'source' => 'class',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'camuflagem_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 6], // Caçador 6
            ],
            // TODO implement condition
        ]);

        Power::create([
            'id' => 211,
            'name' => 'Chuva de Lâminas',
            'description' => 'Uma vez por rodada, quando usa Ambidestria, você pode pagar 2 PM para fazer um ataque adicional com sua arma primária.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'chuva_de_laminas_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 4],
                ['type' => 'power', 'power_id' => 77], // Ambidestria
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 12], // Caçador 12
            ],
            // No effects — the extra attack itself and the once-per-round
            // cap are self-reported, same treatment as Ambidestria/Bote.
        ]);

        Power::create([
            'id' => 212,
            'name' => 'Companheiro Animal',
            'description' => 'Você recebe um companheiro animal.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'companheiro_animal_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'car', 'min' => 1],
                ['type' => 'skill_trained', 'skill_id' => 2], // Adestramento
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            // TODO implement companions
        ]);

        Power::create([
            'id' => 213,
            'name' => 'Elo com a Natureza',
            'description' => 'Você soma sua Sabedoria em seu total de pontos de mana e aprende e pode lançar Caminhos da Natureza (atributo-chave Sabedoria).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'elo_com_a_natureza_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 3], // Caçador 3
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 'knw'],
            ],
            // TODO implement spells
        ]);

        Power::create([
            'id' => 214,
            'name' => 'Emboscar',
            'description' => 'Você pode gastar 2 PM para realizar uma ação padrão adicional em seu turno. Você só pode usar este poder na primeira rodada de um combate.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'emboscar_01.webp',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'skill_trained', 'skill_id' => 11], // Furtividade
            ],
            // No effects — the extra standard action and the first-round-
            // only restriction aren't modeled (no round/turn tracking at
            // all), self-reported like every other action-economy power.
        ]);

        Power::create([
            'id' => 215,
            'name' => 'Empatia Selvagem',
            'description' => 'Você pode se comunicar com animais por meio de linguagem corporal e vocalizações. Você pode usar Adestramento com animais para mudar atitude e persuasão.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'empatia_selvagem_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            // No effects — pure roleplay/narrative, no mechanical
            // resolution (no NPC attitude/persuasion system exists).
        ]);

        Power::create([
            'id' => 216,
            'name' => 'Escaramuça',
            'description' => 'Quando se move 6m ou mais, você recebe +2 na Defesa e Reflexos e +1d8 nas rolagens de dano de ataques corpo a corpo e à distância em alcance curto até o início de seu próximo turno. Você não pode usar esta habilidade se estiver vestindo armadura pesada. <br><br>No APP, ative o poder quando se mover 6m ou mais! Os bônus serão adicionados.',
            'source' => 'class',
            // Pure vessel — the pickable dropdown entry, never shown in
            // Poderes and never a real standing effect itself (see the
            // 'vessel' usability). Split into two power_granted children
            // since the damage half needs a fresh per-attack self-report
            // (roll_active, so it shows up in attack-modal's checklist)
            // while the Defesa/Reflexos half is a standing Ativar/Desativar
            // toggle (active/turn) — one usability value can't be both at
            // once (see Espreitar's own split).
            'usability' => 'vessel',
            'icon_file_name' => 'escaramuca_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 2],
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 6], // Caçador 6
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 228], // Escaramuça (Dano)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 229], // Escaramuça (Defesa)
            ],
        ]);

        Power::create([
            'id' => 228,
            'name' => 'Escaramuça (Dano)',
            'description' => 'Bônus de dano de Escaramuça — quando se moveu 6m ou mais neste turno.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'escaramuca_01.webp',
            'effects' => [
                // stack_group shared with Escaramuça Superior (Dano)'s own
                // entry — bigger die step wins if both are checked (see
                // step-extra-die.ts's extraDieStepIndex).
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d8', 'stack_group' => 'escaramuca_dmg'],
            ],
        ]);

        Power::create([
            'id' => 229,
            'name' => 'Escaramuça (Defesa)',
            'description' => 'Bônus de Defesa e Reflexos de Escaramuça — quando se moveu 6m ou mais, até o início do seu próximo turno.',
            'source' => 'power_granted',
            // duration: 'turn' is the closest bucket to "até o início do seu
            // próximo turno" — self-reported on/off like every other
            // movement-conditional power. The heavy-armor restriction isn't
            // modeled (no such gate exists anywhere).
            'usability' => 'active',
            'duration' => 'turn',
            'icon_file_name' => 'escaramuca_01.webp',
            'effects' => [
                // stack_group shared with Escaramuça Superior (Defesa)'s own
                // entries — resolveTag keeps only the higher value per group
                // (same treatment as Cruel/Atroz), so having both never
                // double-counts.
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2, 'stack_group' => 'escaramuca_def'],
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2, 'stack_group' => 'escaramuca_reflexos'], // Reflexos
            ],
        ]);

        Power::create([
            'id' => 217,
            'name' => 'Escaramuça Superior',
            'description' => 'Quando usa Escaramuça, seus bônus aumentam para +5 na Defesa e Reflexos e +1d12 em rolagens de dano.',
            'source' => 'class',
            'usability' => 'vessel',
            'icon_file_name' => 'escaramuca_superior_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 216], // Escaramuça
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 12], // Caçador 12
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 230], // Escaramuça Superior (Dano)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 231], // Escaramuça Superior (Defesa)
            ],
        ]);

        Power::create([
            'id' => 230,
            'name' => 'Escaramuça Superior (Dano)',
            'description' => 'Bônus de dano de Escaramuça Superior — quando se moveu 6m ou mais neste turno.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'escaramuca_superior_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d12', 'stack_group' => 'escaramuca_dmg'],
            ],
        ]);

        Power::create([
            'id' => 231,
            'name' => 'Escaramuça Superior (Defesa)',
            'description' => 'Bônus de Defesa e Reflexos de Escaramuça Superior — quando se moveu 6m ou mais, até o início do seu próximo turno.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'turn',
            'icon_file_name' => 'escaramuca_superior_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 5, 'stack_group' => 'escaramuca_def'],
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 5, 'stack_group' => 'escaramuca_reflexos'], // Reflexos
            ],
        ]);

        Power::create([
            'id' => 218,
            'name' => 'Ervas Curativas',
            'description' => 'Você pode gastar uma ação completa e uma quantidade de PM a sua escolha (limitado por sua Sabedoria) para aplicar ervas que curam ou desintoxicam em você ou num aliado adjacente. Para cada PM que gastar, cura 2d6 PV ou remove uma condição envenenado afetando o alvo. <br><br>No APP, remova os PMs manualmente. O alvo deve adicionar manualmente os PMs.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'ervas_curativas_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            // No pm_cost/effects — variable self-chosen PM spend, healing,
            // and condition removal aren't modeled. Self-reported: the
            // player manually deducts PM and applies the healing/condition
            // change themselves.
        ]);

        // Inimigo de (Criatura) — one power per creature-type option instead
        // of a single repeatable pick (simpler than wiring up
        // repeatablePowerIds for a power with no real per-copy data beyond
        // its name). "Duas raças humanoides, escolha um par" collapses into
        // one Inimigo de Humanóides — which pair isn't tracked, same
        // self-reported trust model as everything else here. Which type is
        // actually being fought isn't tracked either — the player only
        // checks the box that matches. The doubling itself isn't self-
        // reported though: marca_da_presa_die (see attack-modal.ts) reuses
        // whichever Marca da Presa tier is checked, so checking both
        // together always rolls that tier's die twice — correct at any
        // level, no per-tier hardcoding.
        // Unrolled from a $inimigoDeNames/foreach loop 2026-09-08 — one
        // explicit Power::create per creature-type option.
        Power::create([
            'id' => 219,
            'name' => 'Inimigo de Animais',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Animal, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_animais_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'marca_da_presa_die'],
            ],
        ]);

        Power::create([
            'id' => 220,
            'name' => 'Inimigo de Construtos',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Construto, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_construto_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'marca_da_presa_die'],
            ],
        ]);

        Power::create([
            'id' => 221,
            'name' => 'Inimigo de Espíritos',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Espírito, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_espiritos_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'marca_da_presa_die'],
            ],
        ]);

        Power::create([
            'id' => 222,
            'name' => 'Inimigo de Monstros',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Monstro, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_monstros_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'marca_da_presa_die'],
            ],
        ]);

        Power::create([
            'id' => 223,
            'name' => 'Inimigo de Mortos-Vivos',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Morto-Vivo, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_mortos_vivos_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'marca_da_presa_die'],
            ],
        ]);

        Power::create([
            'id' => 224,
            'name' => 'Inimigo de Humanoides',
            'description' => 'Quando você usa a habilidade Marca da Presa em um Humanoide, dobra os dados de bônus no dano. <br><br>No APP, na tela de rolagem, marque Inimigo caso você esteja atacando uma das criaturas que é Inimigo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'inimigo_humanoides_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'marca_da_presa_die'],
            ],
        ]);

        Power::create([
            'id' => 225,
            'name' => 'Espreitar',
            'description' => 'Quando usa a habilidade Marca da Presa, você recebe um bônus de +1 em testes de perícia contra a criatura marcada. Esse bônus aumenta em +1 para cada PM adicional gasto na habilidade e também dobra com a habilidade Inimigo.',
            'source' => 'class',
            // Pure vessel — the pickable dropdown entry, never shown in
            // Poderes and never a real standing effect itself (see the
            // 'vessel' usability). All real resolution lives on its two
            // power_granted children (added automatically alongside it,
            // see resolve-granted-power-ids.ts), since the actual bonus
            // differs by which roll screen it's read from (attack roll vs
            // a future generic skill roll).
            'usability' => 'vessel',
            'icon_file_name' => 'espreitar_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 226], // Espreitar (Passiva)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 227], // Espreitar (Perícias)
            ],
        ]);

        Power::create([
            'id' => 226,
            'name' => 'Espreitar (Combate)',
            'description' => 'Bônus de Espreitar aplicado automaticamente na rolagem de ataque (Luta/Pontaria) quando Marca da Presa (e/ou Inimigo) estão marcadas.',
            'source' => 'power_granted',
            // No effects — the bonus (Marca da Presa's own pm_cost,
            // doubled if an Inimigo de (Criatura) is also checked) is
            // computed bespoke in attack-modal.ts's roll(), same category
            // as marca_da_presa_die/weapon_die — not a fixed value
            // resolveTag could sum. Never shows as a checkbox anywhere
            // (passive powers are excluded from attack-modal's checklist);
            // the line just appears in the hit breakdown on its own.
            'usability' => 'passive',
            'icon_file_name' => 'espreitar_01.webp',
        ]);

        Power::create([
            'id' => 227,
            'name' => 'Espreitar (Perícias)',
            'description' => 'Bônus de Espreitar em testes de perícia contra a criatura marcada por Marca da Presa (fora da rolagem de ataque). <br><br>No APP, adicione o bônus manualmente na rolagem das perícias. Luta e Pontaria são contabilizadas na tela de rolagem; Para as outras perícias a adição é manual.',
            'source' => 'power_granted',
            // No effects — the value isn't a fixed skill bonus (it scales
            // with whichever Marca da Presa tier is active and doubles
            // with Inimigo, same as the Passiva half), and there's no
            // generic skill-roll screen to auto-resolve it against anyway.
            // roleplay, not roll_active — not worth a bespoke per-screen
            // resolver for this one power; fully manual, player adds it
            // themselves to whatever skill test applies.
            'usability' => 'roleplay',
            'icon_file_name' => 'espreitar_01.webp',
        ]);

        Power::create([
            'id' => 232,
            'name' => 'Olho do Falcão',
            'description' => 'Você pode usar a habilidade Marca da Presa em criaturas em alcance longo.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'olho_do_falcao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            // No effects — Marca da Presa's own range isn't modeled at all
            // (no range/distance system anywhere), so there's nothing to
            // extend.
        ]);

        Power::create([
            'id' => 233,
            'name' => 'Ponto Fraco',
            'description' => 'Quando usa a habilidade Marca da Presa, seus ataques contra a criatura marcada recebem +2 na margem de ameaça. Esse bônus dobra com a habilidade Inimigo.',
            'source' => 'class',
            // No effects — auto-applied, never a checkbox, same bespoke
            // treatment as Espreitar (Passiva): only counts when the
            // character has it AND a Marca da Presa tier is checked this
            // roll, doubled if an Inimigo de (Criatura) is also checked.
            // See attack-modal.ts's pontoFracoMarginBonus.
            'usability' => 'passive',
            'icon_file_name' => 'ponto_fraco_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
        ]);

        Power::create([
            'id' => 234,
            'name' => 'Armadilha Alquímica',
            'description' => 'Quando prepara uma armadilha, você pode gastar uma dose de um preparado alquímico. Se fizer isso, as criaturas afetadas pela armadilha também sofrem os efeitos desse preparado automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'armadilha_alquimica_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'power', 'power_id' => 208], // Armadilheiro
            ],
            // No effects — alchemical preparados aren't modeled anywhere,
            // same as the armadilha powers themselves.
        ]);

        Power::create([
            'id' => 235,
            'name' => 'Avanço do Predador',
            'description' => 'Uma vez por rodada, quando uma criatura marcada por sua Marca da Presa se afasta voluntariamente de você, você pode gastar 1 PM para se mover na direção dela (até o limite do seu deslocamento).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'avanco_do_predador_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 45], // Ímpeto
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 11], // Caçador 11
            ],
            // No effects — the movement itself and the once-per-round cap
            // aren't modeled (no position/movement tracking at all).
        ]);

        Power::create([
            'id' => 236,
            'name' => 'Batedor Marcial',
            'description' => 'Você pode usar testes de Sobrevivência no lugar de testes de Guerra. Além disso, se passar em um teste para analisar terreno, além de quaisquer benefícios encontrados, na próxima vez que usar Marca da Presa nessa cena você recupera 1 PM. <br><br>No APP, adicione o 1PM manualmente quando passar no teste.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'batedor_marcial_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            // No effects — the skill substitution and the terrain-analysis
            // PM refund aren't modeled (no such systems exist). Self-
            // reported: the player manually adds the PM back themselves.
        ]);

        Power::create([
            'id' => 237,
            'name' => 'Curandeiro dos Ermos',
            'description' => 'Você pode usar Ervas Curativas como uma ação de movimento e os dados de cura dessa habilidade aumentam para d8.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'curandeiro_dos_ermos_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'power', 'power_id' => 218], // Ervas Curativas
            ],
            // No effects — Ervas Curativas itself has none to upgrade
            // (healing isn't modeled), same treatment carries over here.
        ]);

        Power::create([
            'id' => 238,
            'name' => 'Elo com a Natureza Maior',
            'description' => 'Escolha uma magia entre Abençoar Alimentos, Acalmar Animal, Alarme, Aviso, Conjurar Armadilhas, Detectar Ameaças, Orientação ou Suporte Ambiental. Você aprende e pode lançar as magias escolhidas (atributo-chave Sabedoria) e pode usar seus aprimoramentos como se tivesse acesso aos mesmos círculos de magia que um druida do seu nível. Você pode escolher este poder mais vezes para magias diferentes.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'elo_com_a_natureza_maior_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'power', 'power_id' => 213], // Elo com a Natureza
            ],
            // No effects — spells aren't modeled at all yet (no spells
            // table), so there's nothing to choose between or grant.
            // Repeatable-pick and the actual spell choice are both
            // deferred — real design needs a spell-choice-group shape
            // (like origins.grants' {picks, options}), not a prerequisite
            // mechanism, once spells exist. Revisit then, not now.
        ]);

        Power::create([
            'id' => 239,
            'name' => 'Flecheiro',
            'description' => 'Você pode usar Sobrevivência no lugar de Ofício para fabricar munições e pode fabricar munições com uma melhoria.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'flecheiro_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 3], // Caçador 3
            ],
            'effects' => [
                // The Sobrevivência-instead-of-Ofício crafting substitution
                // isn't modeled (no crafting system) — just the flag the
                // future item-improvements screen will check before
                // letting a general_item (ammo) type take a melhoria.
                ['tag' => 'allow_improve_ammo', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 240,
            'name' => 'Golpe do Predador',
            'description' => 'Se você causar dano em uma criatura analisada por sua Marca da Presa, ela fica sangrando. Se ela já estiver sangrando, a perda de vida por sangramento aumenta em um passo (cumulativo até um máximo de d12) e ela falha automaticamente em seu próximo teste de Constituição para remover essa condição.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'golpe_do_predador_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            'effects' => [
                // Same shape as Farpada's on_critical_strike (see
                // ItemGrantedPowerSeeder.php) — new circumstance, same
                // condition/op. Only the base "inflict Sangrando" part is
                // modeled; the escalating-severity/cumulative-to-d12/
                // auto-fail-save clause has no condition-severity or
                // save-override system to hook into. Not resolved yet
                // anywhere on the frontend either, same as Farpada's own
                // on_critical_strike — part of the roll-screen edge-case
                // standardization pass, not built now.
                ['tag' => 'on_marca_da_presa_hit', 'op' => 'inflict', 'condition_id' => 1], // Sangrando
            ],
        ]);

        Power::create([
            'id' => 241,
            'name' => 'Herói do Povo',
            'description' => 'Você recebe +2 na Defesa e em testes de resistência. Além disso, sempre que acertar um ataque em um vilão que esteja ameaçando pessoas comuns (um bandido assolando camponeses, um nobre tirano, um monstro devorando viajantes...), você recebe 2 PM temporários. Você pode receber um número máximo de PM temporários por cena igual ao seu nível e eles desaparecem no fim da cena.',
            'source' => 'class',
            // Vessel — split since the standing Defesa/saves bonus (passive)
            // and the attack-roll-triggered temp PM (roll_active) need
            // different usability values at once, same as Espreitar/
            // Escaramuça.
            'usability' => 'vessel',
            'icon_file_name' => 'heroi_do_povo_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5], // Caçador 5
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 242], // Herói do Povo (Passiva)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 243], // Herói do Povo (PM Temporário)
            ],
        ]);

        Power::create([
            'id' => 242,
            'name' => 'Herói do Povo (Passiva)',
            'description' => 'Bônus de Defesa e testes de resistência de Herói do Povo.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'heroi_do_povo_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 10, 'op' => 'add', 'value' => 2], // Fortitude
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2], // Reflexos
                ['tag' => 'skill', 'skill_id' => 29, 'op' => 'add', 'value' => 2], // Vontade
            ],
        ]);

        Power::create([
            'id' => 243,
            'name' => 'Herói do Povo (PM Temporário)',
            'description' => 'PM temporário de Herói do Povo — ao acertar um ataque em um vilão ameaçando pessoas comuns. <br><br>No APP, adicione manualmente os PMs temporários, até o limite definido pelo poder, seguindo as regras.',
            'source' => 'power_granted',
            // Who counts as "a villain threatening common people" is
            // self-reported (no such classification exists), same as
            // every other narrative-judgment condition. temp_pm is tagged
            // for documentation only — not resolved anywhere yet (no
            // temp_pm column/tracking exists at all, characters only have
            // a single current_pm pool), same status as Êxtase da Loucura's
            // own temp_pm. The per-scene cap (= level) and end-of-scene
            // expiry aren't modeled either — deliberately deferred, not
            // worth building per-source tracking for now (see Sifão's own
            // comment on this exact tradeoff).
            'usability' => 'roll_active',
            'icon_file_name' => 'heroi_do_povo_01.webp',
            'effects' => [
                ['tag' => 'temp_pm', 'op' => 'add', 'value' => 2, 'limit' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 244,
            'name' => 'Identificar Presas',
            'description' => 'Você pode identificar criaturas como uma ação de movimento. Além disso, se passar nesse teste, para cada informação obtida você recebe +1 em rolagens de dano contra criaturas dessa espécie até o fim da cena.<br><br>No APP, some manualmente o bônus ao dano rolado e declare ao mestre.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'identificar_presas_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            // No effects — identifying a creature and tracking "which
            // species is currently buffed" aren't modeled anywhere. Self-
            // reported: the player manually adds the bonus themselves.
        ]);

        Power::create([
            'id' => 245,
            'name' => 'Lâminas Guardiãs',
            'description' => 'Enquanto você estiver empunhando duas armas corpo a corpo, recebe +2 na Defesa e em testes de resistência contra inimigos em seu alcance natural. <br><br>No APP, ative o poder quando estiver empunhando duas armas corpo a corpo.',
            'source' => 'class',
            // Real Ativar/Desativar toggle, same self-report treatment as
            // Xadrez de Batalha — dual-wielding melee weapons is technically
            // trackable (character.hands + inventory + weapon.purpose), but
            // building that check is real new resolver code for one power;
            // the player just turns this on while dual-wielding melee and
            // off otherwise, same trust model as everywhere else.
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'laminas_guardias_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 77], // Ambidestria
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5], // Caçador 5
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 10, 'op' => 'add', 'value' => 2], // Fortitude
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2], // Reflexos
                ['tag' => 'skill', 'skill_id' => 29, 'op' => 'add', 'value' => 2], // Vontade
            ],
        ]);

        Power::create([
            'id' => 246,
            'name' => 'Caminho do Explorador',
            'description' => 'No 5º nível, você pode atravessar terrenos difíceis sem sofrer redução em seu deslocamento e a CD para rastrear você aumenta em +10. Esta habilidade só funciona em terrenos nos quais você tenha a habilidade Explorador.',
            'source' => 'class_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'caminhos_do_explorador_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5], // Caçador 5
            ],
            // No effects — terreno difícil movement and tracking CD aren't
            // modeled anywhere (no such systems exist), same treatment as
            // every other terrain/tracking clause. roleplay, not
            // roll_active/passive — nothing here is ever checked in a
            // roll screen.
        ]);

        Power::create([
            'id' => 247,
            'name' => 'Lanceiro',
            'description' => 'Você recebe +2 em testes de ataque e rolagens de dano com lanças (exceto lanças montadas e de justa). Além disso, se estiver empunhando uma dessas armas com as duas mãos, seu dano com ela aumenta em um passo e ela é considerada uma arma alongada.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'lanceiro_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            // No effects — lanças aren't seeded as their own weapon
            // category/ability yet (no way to match "lança" weapons in the
            // catalog).
            // TODO implement all lanças then add the effect
        ]);

        Power::create([
            'id' => 248,
            'name' => 'Pega!',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para fazer a manobra agarrar contra uma criatura em alcance curto usando Adestramento em vez de Luta, usando seu companheiro animal para isso. Se o alvo estiver sob efeito de sua Marca da Presa, você soma os dados dessa habilidade como um bônus no teste. A criatura permanece agarrada até vencer um teste de manobra contra seu companheiro animal (como acima) ou até você mandar seu animal soltá-la (uma ação livre). <br><br>No APP, faça a rolagem da perícia e as adições de dados manualmente.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'pega_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'power', 'power_id' => 212], // Companheiro Animal
            ],
            // No effects — grapple maneuvers aren't modeled at all (no
            // maneuver system exists, see mod_maneuver in tag-library.md).
        ]);

        Power::create([
            'id' => 249,
            'name' => 'Primeiro Sangue',
            'description' => 'Na primeira rodada de cada combate, você recebe +2 em testes de ataque e todos os seus dados de dano aumentam em dois passos.',
            'source' => 'class',
            // No round/turn tracking exists — "primeira rodada de cada
            // combate" is self-reported, same as Executor/Rejeição Divina.
            // mod_hit resolves normally; all_die_step_increase steps every
            // damage die (weapon's own + every extra_die) — see
            // attack-modal.ts/calculate-weapon-dice.ts for the double-count
            // guard on weapon_die-sourced entries.
            'usability' => 'roll_active',
            'icon_file_name' => 'primeiro_sangue_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'power', 'power_id' => 214], // Emboscar
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'all_die_step_increase', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 250,
            'name' => 'Sequência Dilaceradora',
            'description' => 'Quando usa Ambidestria, se acertar ambos os ataques, você pode gastar 1 PM para causar +2d8 pontos de dano no segundo ataque.',
            'source' => 'class',
            // "Se acertar ambos os ataques" isn't tracked (no second-attack
            // resolution) — self-reported, same trust model as Ambidestria
            // itself.
            'usability' => 'roll_active',
            'icon_file_name' => 'sequencia_dilaceradora_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 77], // Ambidestria
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 11], // Caçador 11
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d8'],
            ],
        ]);

        Power::create([
            'id' => 251,
            'name' => 'Sequência do Predador',
            'description' => 'Quando usa Ambidestria com armas que causam tipos de dano diferentes, se acertar ambos os ataques, você pode gastar 1 PM para fazer uma manobra entre desarmar, derrubar ou quebrar contra o mesmo alvo. <br><br>No APP, role a manobra manualmente. Ativando o poder aqui gastará seu 1PM.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'sequencia_do_predador_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 77], // Ambidestria
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 8], // Caçador 8
            ],
            // No effects — maneuvers aren't modeled at all (no maneuver
            // system exists, see mod_maneuver in tag-library.md), same
            // treatment as Pega!. The different-damage-types/both-attacks-
            // hitting condition is self-reported too.
        ]);

        Power::create([
            'id' => 252,
            'name' => 'Sombra dos Ermos',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para receber camuflagem leve com duração sustentada.',
            'source' => 'class',
            // duration has no exact "sustentada" bucket (turn/scene/day) —
            // 'scene' is the closest, same as Camuflagem's own toggle,
            // which this one grants a lighter version of.
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'sombra_dos_ermos_01.webp',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'power', 'power_id' => 210], // Camuflagem
            ],
            // TODO implement condition camuflagem
        ]);

        Power::create([
            'id' => 254,
            'name' => 'Tiro de Abate',
            'description' => 'Quando usa a ação mirar, até o fim do turno você recebe +2 em testes de ataque e na margem de ameaça com ataques à distância, e os dados extras de sua habilidade Marca da Presa também são multiplicados em caso de acerto crítico.',
            'source' => 'class',
            // No effects — bespoke, same shape as Ponto Fraco: only counts
            // when the character has it, Mirar (id 253) is checked this
            // roll, and the weapon is ranged. The crit-multiplied Marca da
            // Presa extra_die is a deliberate one-off exception to the
            // usual "extra dice never scale by crit" rule — needs its own
            // small special case in markPassed()'s extra-die loop, not a
            // generic mechanism.
            // TODO implement — blocked on Mirar's own checklist wiring
            // (see GeneralActionPowerSeeder.php's TODO).
            'usability' => 'passive',
            'icon_file_name' => 'tiro_de_abate_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
                ['type' => 'power', 'power_id' => 225], // Espreitar
            ],
        ]);

        Power::create([
            'id' => 255,
            'name' => 'Tiro Trespassante',
            'description' => 'Quando você faz um ataque à distância com uma arma de disparo e reduz os pontos de vida do alvo a 0 ou menos, pode gastar 1 PM para fazer um ataque adicional contra outra criatura que esteja adiante na mesma linha, usando a mesma arma e munição do ataque original.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'tiro_trespassante_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
                ['type' => 'power', 'power_id' => 78], // Arqueiro
            ],
            // No effects — the extra attack, kill-confirmation, and line-
            // of-fire condition aren't modeled (no position/grid tracking).
        ]);

        Power::create([
            'id' => 256,
            'name' => 'Tempestade de Lâminas',
            'description' => 'Quando usa Chuva de Lâminas, você pode fazer um ataque adicional com sua arma secundária (para um total de quatro ataques na ação).',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'tempestade_de_laminas_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 211], // Chuva de Lâminas
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 17], // Caçador 17
            ],
            // No effects — the extra attack itself is self-reported, same
            // treatment as Ambidestria/Bote/Chuva de Lâminas.
        ]);

        Power::create([
            'id' => 257,
            'name' => 'Tocaia Habilidosa',
            'description' => 'Sua Marca da Presa também fornece +1 na CD de suas habilidades contra a criatura marcada para cada PM gasto. Esse bônus dobra com a habilidade Inimigo.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'tocaia_habilidosa_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2]], // Caçador
            ],
            // No effects — no CD-calculator screen exists (dc_active isn't
            // resolved anywhere) to fold this into. Self-reported: the
            // player manually adds the bonus themselves.
        ]);

        Power::create([
            'id' => 258,
            'name' => 'Último Sangue',
            'description' => 'Seus ataques contra criaturas sangrando causam um dado extra de dano do mesmo tipo. <br><br>No APP, confirme o dado de sangramento manualmente com o mestre.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'ultimo_sangue_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [2], 'min_level' => 5], // Caçador 5
            ],
            // No effects — whether the target is bleeding isn't tracked
            // (no condition-tracking on a target exists), same as the
            // extra die itself; the player manually tracks/adds it.
        ]);
    }
}
