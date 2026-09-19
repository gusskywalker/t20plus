<?php

namespace Database\Seeders\SpellSeeders;

use App\Models\Spell;
use Illuminate\Database\Seeder;

class ArcanaSpellSeeder extends Seeder
{
    //This file must use IDs between 0 and 999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
    public function run(): void
    {

        Spell::create([
            'id' => 1,
            'name' => 'Bola de Fogo',
            'description' => 'Esta famosa magia de ataque cria uma poderosa explosão, causando 6d6 pontos de dano de fogo em todas as criaturas e objetos livres na área.',
            'usability' => 'damage',
            'damage_type' => 'fire',
            'type' => 'arcana',
            'circle' => 2,
            'school' => 'evocacao',
            'action_cost' => 'standard',
            'range' => 'médio',
            'info_affected_area' => 'esfera com 6m de raio',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'bola_de_fogo_01.webp',
            'effects' => [
                ['tag' => 'base_spell_dmg', 'op' => 'add', 'value' => '6d6'],
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
            ],
            'enhancements' => [
                [
                    'description' => 'aumenta o dano em +2d6.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => '2d6'],
                    ],
                ],
                [
                    'description' => 'muda a área para efeito de esfera flamejante com tamanho Médio e a duração para cena. Em vez do normal, cria uma esfera flamejante com 1,5m de diâmetro que causa 3d6 pontos de dano a qualquer criatura no mesmo espaço. Você pode gastar uma ação de movimento para fazer a esfera voar 9m em qualquer direção. Ela é imune a dano, mas pode ser apagada com água. Uma criatura só pode sofrer dano da esfera uma vez por rodada.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'muda a duração para um dia ou até ser descarregada. Em vez do normal, você cria uma pequena pedra flamejante, que pode detonar como uma reação, descarregando a magia. A pedra pode ser usada como uma arma de arremesso com alcance curto. Uma vez detonada, causa o dano da magia numa área de esfera com 6m de raio.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
            ],
        ]);

        Spell::create([
            'id' => 2,
            'name' => 'Adaga Mental',
            'description' => 'Você manifesta e dispara uma adaga imaterial contra a mente do alvo, que sofre 2d6 pontos de dano psíquico e fica atordoado por uma rodada. Se passar no teste de resistência, sofre apenas metade do dano e evita a condição. Uma criatura só pode ficar atordoada por esta magia uma vez por cena.',
            'usability' => 'damage',
            'damage_type' => 'psychic',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'vontade',
            'icon_file_name' => 'adaga_mental_01.webp',
            'effects' => [
                ['tag' => 'base_spell_dmg', 'op' => 'add', 'value' => '2d6'],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 2],
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
                ['trigger' => 'on_spell_fail', 'tag' => 'condition', 'op' => 'no_condition_caused'],
            ],
            'enhancements' => [
                [
                    'description' => 'você lança a magia sem gesticular ou pronunciar palavras (o que permite lançar esta magia de armadura) e a adaga se torna invisível. Se o alvo falhar no teste de resistência, não percebe que você lançou uma magia contra ele.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda a duração para um dia. Além do normal, você "finca" a adaga na mente do alvo. Enquanto a magia durar, você sabe a direção e localização do alvo, desde que ele esteja no mesmo mundo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'aumenta o dano em +1d6.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => '1d6'],
                    ],
                ],
            ],
        ]);

        Spell::create([
            'id' => 3,
            'name' => 'Leque Cromático',
            'description' => 'Um cone de luzes brilhantes surge das suas mãos, deixando os animais e humanoides na área atordoados por 1 rodada (apenas uma vez por cena, Vontade anula) e ofuscados pela cena. Esta magia não afeta criaturas cegas.',
            'usability' => 'debuff',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'ilusao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'info_affected_area' => 'cone de 4,5m',
            'duration' => 'instantânea',
            'resistance' => 'vontade',
            'icon_file_name' => 'leque_cromatico_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 2],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 4],
                ['trigger' => 'on_spell_fail', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 4],
            ],
            'enhancements' => [
                [
                    'description' => 'além do normal, as criaturas afetadas ficam vulneráveis pela cena.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 5],
                    ],
                ],
                [
                    'description' => 'também afeta espíritos e monstros na área. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                ],
                [
                    'description' => 'também afeta construtos, espíritos, monstros e mortos-vivos na área. Requer 3º círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                ],
            ],
        ]);

        Spell::create([
            'id' => 4,
            'name' => 'Sono',
            'description' => 'Um cansaço místico recai sobre o alvo. Se falhar na resistência, ele fica inconsciente e caído ou, se estiver envolvido em combate ou outra situação perigosa, fica exausto por 1 rodada, depois fatigado. Em ambos os casos, se passar, o alvo fica fatigado por 1d4 rodadas.',
            'usability' => 'debuff',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 humanoide',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'icon_file_name' => 'sono_01.webp',
            'effects' => [
                ['tag' => 'on_sono_cast', 'op' => 'inflict', 'condition_id' => 6],
                ['tag' => 'on_sono_cast', 'op' => 'inflict', 'condition_id' => 8],
                ['tag' => 'on_sono_cast_target_in_combat', 'op' => 'inflict', 'condition_id' => 11],
                ['trigger' => 'on_spell_fail', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 13],
            ],
            'enhancements' => [
                [
                    'description' => 'Alvo em Combate',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'context_flag', 'op' => 'target_in_combat'],
                    ],
                ],
                [
                    'description' => 'alvos que falhem na resistência ficam exaustos por 1d4+1 rodadas, em vez de apenas 1.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para criatura.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'afeta todos os alvos válidos a sua escolha dentro do alcance.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
            ],
        ]);

        Spell::create([
            'id' => 5,
            'name' => 'Imagem Espelhada',
            'description' => 'Três cópias ilusórias suas aparecem. As duplicatas ficam ao seu redor e imitam suas ações, tornando difícil para um inimigo saber quem atacar. Você recebe +6 na Defesa. Cada vez que um ataque contra você erra, uma das imagens desaparece e o bônus na Defesa diminui em 2. Um oponente deve ver as cópias para ser confundido. Se você estiver invisível, ou o atacante fechar os olhos, você não recebe o bônus (mas o atacante ainda sofre penalidades normais por não enxergar). <br><br>No APP, a defesa inicial será automaticamente adicionada. Quando receber ataques, reduza manualmente até a magia acabar.',
            'usability' => 'buff',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'ilusao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'info_affects' => 'você',
            'duration' => 'cena',
            'resistance' => null,
            'icon_file_name' => 'imagem_espelhada_01.webp',
            'buff_affects' => ['caster'],
            'buff_base_max_targets' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 6, 'sum_group' => '1'],
            ],
            'enhancements' => [
                [
                    'description' => 'aumenta o número de cópias em +1 (e o bônus na Defesa em +2).',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'mod_def', 'op' => 'add', 'value' => 2, 'sum_group' => '1'],
                    ],
                ],
                [
                    'description' => 'além do normal, toda vez que uma cópia é destruída, emite um clarão de luz. A criatura que destruiu a cópia fica ofuscada por uma rodada. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                ],
            ],
        ]);

        Spell::create([
            'id' => 7,
            'name' => 'Sussurros Insanos',
            'description' => 'Você murmura palavras desconexas que afetam a mente do alvo. O alvo fica confuso.',
            'usability' => 'debuff',
            'type' => 'arcana',
            'circle' => 2,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 humanoide',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'icon_file_name' => 'sussurros_insanos_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 14],
                ['trigger' => 'on_spell_fail', 'tag' => 'condition', 'op' => 'no_condition_caused'],
            ],
            'enhancements' => [
                [
                    'description' => 'aumenta o número de alvos em +1.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para 1 criatura.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'muda o alvo para criaturas escolhidas. Requer 5º círculo.',
                    'pm_cost' => 12,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 5,
                    'unique_change_group' => '1',
                ],
            ],
        ]);

        Spell::create([
            'id' => 8,
            'name' => 'Aparência Perfeita',
            'description' => 'Esta magia lhe concede um rosto idealizado, porte físico garboso, voz melodiosa e olhar sedutor. Caso seu Carisma seja 5 ou mais, você recebe +2 neste atributo. Do contrário, ele se torna 5 (isso conta como um bônus). Além disso, você recebe +5 em Diplomacia e Enganação. Quando a magia acaba, quaisquer observadores percebem a mudança e tendem a suspeitar de você. Da mesma maneira, pessoas que o viram sob o efeito da magia sentirão que "algo está errado" ao vê-lo em condições normais. Quando a cena acabar, você pode gastar os PM da magia novamente como uma ação livre para mantê-la ativa. Este efeito não fornece PV ou PM adicionais.',
            'usability' => 'buff',
            'type' => 'arcana',
            'circle' => 2,
            'school' => 'ilusao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'info_affects' => 'você',
            'duration' => 'cena',
            'resistance' => null,
            'icon_file_name' => 'aparencia_perfeita_01.webp',
            'buff_affects' => ['caster'],
            'buff_base_max_targets' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 5],
                ['tag' => 'on_aparencia_perfeita_cast', 'op' => 'set_or_add', 'value' => 2, 'min' => 5],
            ],
            'enhancements' => [
                [
                    'description' => 'muda o alcance para toque e o alvo para 1 humanoide.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
            ],
        ]);
        
        //TODO implmement this bullshit
        Spell::create([
            'id' => 11,
            'name' => 'Açoite Flamejante',
            'description' => 'Um açoite de fogo surge em uma de suas mãos com a qual possa empunhar uma arma (essa mão fica ocupada pela duração da magia). Você pode usar uma ação padrão para causar 2d6 pontos de dano de fogo com o açoite em uma criatura em alcance curto e deixá-la em chamas e enredada enquanto estiver em chamas dessa forma. Passar na resistência reduz o dano à metade e evita as chamas.',
            'usability' => 'utility',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'convocacao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'info_affects' => 'açoite de chamas criado em sua mão (veja texto)',
            'duration' => 'sustentada',
            'resistance' => 'reflexos',
            'icon_file_name' => 'acoite_flamejante_01.webp',
            'enhancements' => [
                [
                    'description' => 'muda a execução para movimento.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'muda o dano para 4d6. Requer 2° círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                    'unique_change_group' => '2',
                ],
                [
                    'description' => 'muda o dano para 6d6. Requer 3° círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'unique_change_group' => '2',
                ],
            ],
        ]);

        Spell::create([
            'id' => 12,
            'name' => 'Alarme',
            'description' => 'Você cria uma barreira protetora invisível que detecta qualquer criatura que tocar ou entrar na área protegida. Ao lançar a magia, você pode escolher quais criaturas podem entrar na área sem ativar seus efeitos. Alarme pode emitir um aviso telepático ou sonoro, decidido quando a magia é lançada. Um aviso telepático alerta apenas você, inclusive acordando-o se estiver dormindo, mas apenas se estiver a até 1km da área protegida. Um aviso sonoro alerta todas as criaturas em alcance longo.',
            'usability' => 'utility',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'abjuracao',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affected_area' => 'esfera com 9m de raio',
            'duration' => '1 dia',
            'resistance' => null,
            'icon_file_name' => 'alarme_01.webp',
            'enhancements' => [
                [
                    'description' => 'muda o alcance para pessoal. A área é emanada a partir de você.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'além do normal, você também percebe qualquer efeito de adivinhação que seja usado dentro da área ou atravesse a área. Você pode fazer um teste oposto de Misticismo contra quem usou o efeito se passar, tem um vislumbre de seu rosto e uma ideia aproximada de sua localização ("três dias de viagem ao norte", por exemplo).',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda a duração para um dia ou até ser descarregada e a resistência para Vontade anula. Quando um intruso entra na área, você pode descarregar a magia. Se o intruso falhar na resistência, ficará paralisado por 1d4 rodadas. Além disso, pelas próximas 24 horas você e as criaturas escolhidas ganham +10 em testes de Sobrevivência para rastrear o intruso.',
                    'pm_cost' => 9,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '2',
                ],
            ],
        ]);

        Spell::create([
            'id' => 13,
            'name' => 'Amedrontar',
            'description' => 'O alvo é envolvido por energias sombrias e assustadoras. Se falhar na resistência, fica apavorado por 1 rodada, depois abalado. Se passar, fica abalado por 1d4 rodadas.',
            'usability' => 'debuff',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'necromancia',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 animal ou humanoide',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'icon_file_name' => 'amedrontar_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 15],
                ['trigger' => 'on_spell_fail', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 16],
            ],
            'enhancements' => [
                [
                    'description' => 'alvos que falhem na resistência ficam apavorados por 1d4+1 rodadas, em vez de apenas 1.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para 1 criatura.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'afeta todos os alvos válidos a sua escolha dentro do alcance.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
            ],
        ]);

        Spell::create([
            'id' => 14,
            'name' => 'Área Escorregadia',
            'description' => 'Esta magia recobre uma superfície com uma substância gordurosa e escorregadia. Criaturas na área devem passar na resistência para não cair. Nas rodadas seguintes, criaturas que tentem movimentar-se pela área devem fazer testes de Acrobacia para equilíbrio (CD 10). Área Escorregadia pode tornar um item escorregadio. Uma criatura segurando um objeto afetado deve passar na resistência para não deixar o item cair cada vez que usá-lo.',
            'usability' => 'utility',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'convocacao',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 objeto',
            'info_affected_area' => 'quadrado de 3m',
            'duration' => 'cena',
            'resistance' => 'reflexos',
            'icon_file_name' => 'area_escorregadia_01.webp',
            'enhancements' => [
                [
                    'description' => 'aumenta a área em +1 quadrado de 1,5m.',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda a CD dos testes de Acrobacia para 15.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'muda a CD dos testes de Acrobacia para 20.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
            ],
        ]);

        Spell::create([
            'id' => 17,
            'name' => 'Armadura Arcana',
            'description' => 'Esta magia cria uma película protetora invisível, mas tangível, fornecendo +5 na Defesa. Esse bônus é cumulativo com outras magias, mas não com bônus fornecido por armaduras.',
            'usability' => 'buff',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'abjuracao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'info_affects' => 'você',
            'duration' => 'cena',
            'resistance' => null,
            'icon_file_name' => 'armadura_arcana_01.webp',
            'buff_affects' => ['caster'],
            'buff_base_max_targets' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 5, 'sum_group' => '1', 'stack_group' => 'armor_bonus'],
            ],
            'enhancements' => [
                [
                    'description' => 'muda a execução para reação. Em vez do normal, quando sofre um ataque, você cria um escudo mágico que fornece +5 na Defesa contra esse ataque (cumulativo com o bônus do efeito básico desta magia e de armaduras).',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'aumenta o bônus na Defesa em +1.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'mod_def', 'op' => 'add', 'value' => 1, 'sum_group' => '1', 'stack_group' => 'armor_bonus'],
                    ],
                ],
                [
                    'description' => 'muda a duração para um dia.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
            ],
        ]);

        Spell::create([
            'id' => 19,
            'name' => 'Explosão de Chamas',
            'description' => 'Um leque de chamas irrompe de suas mãos, causando 2d6 pontos de dano de fogo às criaturas na área.',
            'usability' => 'damage',
            'damage_type' => 'fire',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'evocacao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'info_affected_area' => 'cone de 6m',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'explosao_de_chamas_01.webp',
            'effects' => [
                ['tag' => 'base_spell_dmg', 'op' => 'add', 'value' => '2d6'],
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
            ],
            'enhancements' => [
                [
                    'description' => 'truque: muda o alcance para curto, a área para alvo de 1 objeto e a resistência para Reflexos anula. Você gera uma pequena explosão que não causa dano mas pode acender uma vela, tocha ou fogueira. Também pode fazer um objeto inflamável com RD 0 (como uma corda ou pergaminho) ficar em chamas. Uma criatura em posse de um objeto pode evitar esse efeito se passar no teste de resistência.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => true,
                ],
                [
                    'description' => 'aumenta o dano em +1d6.',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => '1d6'],
                    ],
                ],
                [
                    'description' => 'muda a resistência para Reflexos parcial. Se passar, reduz o dano à metade. Se falhar, fica em chamas (veja Condições).',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 22],
                    ],
                ],
            ],
        ]);

        Spell::create([
            'id' => 20,
            'name' => 'Queda Suave',
            'description' => 'O alvo cai lentamente. A velocidade da queda é reduzida para 18m por rodada — o suficiente para não causar dano. Como lançar esta magia é uma reação, você pode lançá-la rápido o bastante para salvar a si ou um aliado de quedas inesperadas. Lançada sobre um projétil — como uma flecha ou uma rocha largada do alto de um penhasco —, a magia faz com que ele cause metade do dano normal, devido à lentidão. Queda Suave só funciona em criaturas e objetos em queda livre; a magia não vai frear um golpe de espada ou o mergulho rasante de um atacante voador.',
            'usability' => 'utility',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'transmutacao',
            'action_cost' => 'reaction',
            'range' => 'curto',
            'info_affects' => '1 criatura ou objeto Grande ou menor',
            'duration' => 'até chegar ao solo ou cena, o que vier primeiro',
            'resistance' => null,
            'icon_file_name' => 'queda_suave_01.webp',
            'enhancements' => [
                [
                    'description' => 'truque: muda o alvo para objeto Minúsculo. Em vez do normal, você pode gastar uma ação de movimento para levitar o alvo até 4,5m em qualquer direção.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => true,
                ],
                [
                    'description' => 'muda o alvo para até 10 criaturas ou objetos adequados.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'aumenta a categoria de tamanho do alvo em uma.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
            ],
        ]);

        Spell::create([
            'id' => 21,
            'name' => 'Raio Arcano',
            'description' => 'Você pode gastar uma ação padrão para causar 1d8 pontos de dano de essência num alvo em alcance curto. Esse dano aumenta em +1d8 para cada círculo de magia acima do 1º que você puder lançar. O alvo pode fazer um teste de Reflexos (CD atributo-chave) para reduzir o dano à metade. O raio arcano conta como uma magia para efeitos de habilidades e itens que beneficiem suas magias.',
            'usability' => 'damage',
            'damage_type' => 'essence',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'raio_arcano_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
            ],
        ]);

        Spell::create([
            'id' => 22,
            'name' => 'Raio Arcano (Ácido)',
            'description' => 'Como Raio Arcano, mas causa dano de ácido. Se o alvo falhar no teste de Reflexos, fica vulnerável por 1 rodada.',
            'usability' => 'damage',
            'damage_type' => 'acid',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'raio_arcano_acido_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 5],
            ],
        ]);

        Spell::create([
            'id' => 23,
            'name' => 'Raio Arcano (Eletricidade)',
            'description' => 'Como Raio Arcano, mas causa dano de eletricidade. Se o alvo falhar no teste de Reflexos, fica ofuscado por 1 rodada.',
            'usability' => 'damage',
            'damage_type' => 'electricity',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'raio_arcano_eletricidade_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 4],
            ],
        ]);

        Spell::create([
            'id' => 24,
            'name' => 'Raio Arcano (Fogo)',
            'description' => 'Como Raio Arcano, mas causa dano de fogo. Se o alvo falhar no teste de Reflexos, fica em chamas.',
            'usability' => 'damage',
            'damage_type' => 'fire',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'raio_arcano_fogo_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 22],
            ],
        ]);

        Spell::create([
            'id' => 25,
            'name' => 'Raio Arcano (Frio)',
            'description' => 'Como Raio Arcano, mas causa dano de frio. Se o alvo falhar no teste de Reflexos, fica lento por 1 rodada.',
            'usability' => 'damage',
            'damage_type' => 'cold',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'raio_arcano_frio_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 10],
            ],
        ]);

        Spell::create([
            'id' => 26,
            'name' => 'Raio Arcano (Trevas)',
            'description' => 'Como Raio Arcano, mas causa dano de trevas. Se o alvo falhar no teste de Reflexos, não pode curar PV por 1 rodada.',
            'usability' => 'damage',
            'damage_type' => 'darkness',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'raio_arcano_trevas_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
            ],
        ]);

        Spell::create([
            'id' => 27,
            'name' => 'Enfeitiçar',
            'description' => 'O alvo fica enfeitiçado (veja a página 394). Um alvo hostil ou que esteja envolvido em um combate recebe +5 em seu teste de resistência. Se você ou seus aliados tomarem qualquer ação hostil contra o alvo, a magia é dissipada e o alvo retorna à atitude que tinha antes (ou piorada, de acordo com o mestre).',
            'usability' => 'debuff',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 humanoide',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'icon_file_name' => 'enfeiticar_01.webp',
            'enhancements' => [
                [
                    'description' => 'Alvo em Combate.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'mod_cd', 'op' => 'add', 'value' => -5],
                    ],
                ],
                [
                    'description' => 'em vez do normal, você sugere uma ação para o alvo e ele obedece. A sugestão deve ser feita de modo que pareça aceitável, a critério do mestre. Pedir ao alvo que pule de um precipício, por exemplo, dissipa a magia. Já sugerir a um guarda que descanse um pouco, de modo que você e seus aliados passem por ele, é aceitável. Quando o alvo executa a ação, a magia termina. Você pode determinar uma condição específica para a sugestão: por exemplo, que um rico mercador doe suas moedas para o primeiro mendigo que encontrar.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para 1 espírito ou monstro. Requer 3º círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'effects' => [
                        ['tag' => 'fluff_change_target', 'op' => 'grant', 'value' => 'espírito ou monstro'],
                    ],
                ],
                [
                    'description' => 'afeta todos os alvos dentro do alcance.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'fluff_change_target', 'op' => 'grant', 'value' => 'todos os alvos no alcance'],
                    ],
                ],
            ],
        ]);

        Spell::create([
            'id' => 28,
            'name' => 'Hipnotismo',
            'description' => 'Suas palavras e movimentos ritmados deixam o alvo fascinado. Esta magia só afeta criaturas que possam perceber você. Se usar esta magia em combate, o alvo recebe +5 em seu teste de resistência. Se a criatura passar, fica imune a este efeito por um dia.',
            'usability' => 'debuff',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 animal ou humanoide',
            'duration' => '1d4 rodadas',
            'resistance' => 'vontade',
            'icon_file_name' => 'hipnotismo_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 26],
            ],
            'enhancements' => [
                [
                    'description' => 'Alvo em Combate.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'mod_cd', 'op' => 'add', 'value' => -5],
                    ],
                ],
                [
                    'description' => 'muda a duração para 1 rodada. Em vez de fascinado, o alvo fica pasmo (apenas uma vez por cena).',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => true,
                    'effects' => [
                        ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'override', 'condition_id' => 25],
                    ],
                ],
                [
                    'description' => 'como o normal, mas alvos que passem na resistência não sabem que foram vítimas de uma magia.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para animais ou humanoides escolhidos.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'fluff_change_target', 'op' => 'grant', 'value' => 'animais ou humanoides'],
                    ],
                ],
                [
                    'description' => 'muda a duração para sustentada.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'também afeta espíritos e monstros na área. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                    'effects' => [
                        ['tag' => 'fluff_change_target', 'op' => 'grant', 'value' => 'espíritos e monstros'],
                    ],
                ],
                [
                    'description' => 'também afeta construtos, espíritos, monstros e mortos-vivos na área. Requer 3º círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'effects' => [
                        ['tag' => 'fluff_change_target', 'op' => 'grant', 'value' => 'construtos, espíritos, monstros e mortos-vivos'],
                    ],
                ],
            ],
        ]);

        Spell::create([
            'id' => 29,
            'name' => 'Criar Ilusão',
            'description' => 'Esta magia cria uma ilusão visual (uma criatura, uma parede...) ou sonora (um grito de socorro, um uivo assustador...). A magia cria apenas imagens ou sons simples, com volume equivalente ao tom de voz normal para cada cubo de 1,5m no efeito. Não é possível criar cheiros, texturas ou temperaturas, nem sons complexos, como uma música ou diálogo. Criaturas e objetos atravessam uma ilusão sem sofrer dano, mas a magia pode, por exemplo, esconder uma armadilha ou inimigo. A magia é dissipada se você sair do alcance.',
            'usability' => 'utility',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'ilusao',
            'action_cost' => 'standard',
            'range' => 'médio',
            'info_affects' => 'ilusão que se estende a até 4 cubos de 1,5m',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'icon_file_name' => 'criar_ilusao_01.webp',
            'enhancements' => [
                [
                    'description' => 'muda a duração para sustentada. A cada rodada você pode gastar uma ação livre para mover a imagem ou alterar levemente o som. Quando você para de sustentar a magia, a imagem ou som persistem por mais uma rodada antes de a magia se dissipar.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => 'duracao',
                ],
                [
                    'description' => 'aumenta o efeito da ilusão em +1 cubo de 1,5m.',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'também pode criar ilusões de imagem e sons combinados.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'também pode criar sons complexos com volume máximo equivalente ao que cinco pessoas podem produzir para cada cubo de 1,5m no efeito. Com uma ação livre, você pode alterar o volume do som ou fazê-lo se aproximar ou se afastar dentro do alcance.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'também pode criar odores e sensações térmicas, percebidos a uma distância igual ao dobro do tamanho máximo do efeito.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alcance para longo e o efeito para esfera com 30m de raio. Em vez do normal, você cria um som muito alto, equivalente a uma multidão. Criaturas na área lançam magias como se estivessem em uma condição ruim e a CD de testes de Percepção para ouvir aumenta em +10. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                ],
                [
                    'description' => 'também pode criar sensações táteis, como texturas. Criaturas que não saibam que é uma ilusão não conseguem atravessá-la sem passar em um teste de Vontade (objetos ainda a atravessam). Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                ],
                [
                    'description' => 'muda a duração para sustentada. Além do normal, você pode gastar uma ação livre para modificar livremente a ilusão (mas não pode acrescentar novos aprimoramentos após lançá-la). Requer 3º círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'unique_change_group' => 'duracao',
                ],
            ],
        ]);

        Spell::create([
            'id' => 30,
            'name' => 'Metamorfose',
            'description' => 'Você muda sua aparência e forma — incluindo seu equipamento — para qualquer outra criatura, existente ou imaginada. Independentemente da forma escolhida, você recebe +20 em testes de Enganação para disfarce. Características não mencionadas não mudam. Se mudar para uma forma humanoide, pode mudar o tipo de dano (entre corte, impacto e perfuração) de suas armas (se usa uma maça e transformá-la em espada longa, ela pode causar dano de corte, por exemplo). Se quiser, pode assumir uma forma humanoide com uma categoria de tamanho acima ou abaixo da sua nesse caso aplique os modificadores em Furtividade e testes de manobra. Se mudar para outras formas, você pode escolher uma Forma Selvagem do druida (veja no Capítulo 1). Nesse caso você não pode atacar com suas armas, falar ou lançar magias até voltar ao normal, mas recebe uma ou mais armas naturais e os bônus da forma selvagem escolhida.',
            'usability' => 'buff',
            'type' => 'arcana',
            'circle' => 2,
            'school' => 'transmutacao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'info_affects' => 'você',
            'duration' => 'cena',
            'resistance' => null,
            'buff_affects' => ['caster'],
            'icon_file_name' => 'metamorfose_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 20],
            ],
            'enhancements' => [
                [
                    'description' => 'a forma escolhida recebe uma habilidade de sentidos entre faro, visão na penumbra e visão no escuro.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'a forma escolhida recebe percepção às cegas. Requer 3º círculo.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                ],
                [
                    'description' => 'muda o alcance para toque, o alvo para 1 criatura e adiciona resistência (Vontade anula).',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => 'alcance',
                ],
                [
                    'description' => 'muda o alcance para médio, o alvo para 1 criatura e a resistência para Vontade anula. Em vez do normal, transforma o alvo em uma criatura ou objeto inofensivo (ovelha, sapo, galinha, pudim de ameixa etc.). A criatura não pode atacar, falar e lançar magias, seu deslocamento vira 3m e sua Defesa vira 10. Suas outras características não mudam. No início de seus turnos, o alvo pode fazer um teste de Vontade; se passar, retorna à sua forma normal e a magia termina. Requer 3º círculo.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'unique_change_group' => 'alcance',
                ],
                [
                    'description' => 'se mudar para formas não humanoides, pode escolher uma Forma Selvagem Aprimorada. Requer 3º círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                ],
                [
                    'description' => 'se mudar para formas não humanoides, pode escolher uma Forma Selvagem Superior. Requer 4º círculo.',
                    'pm_cost' => 9,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 4,
                ],
                [
                    'description' => 'além do normal, no início de seus turnos o alvo pode mudar de forma novamente, como uma ação livre, fazendo novas escolhas. Requer 5º círculo.',
                    'pm_cost' => 12,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 5,
                ],
            ],
        ]);

        Spell::create([
            'id' => 31,
            'name' => 'Invisibilidade',
            'description' => 'O alvo fica invisível (incluindo seu equipamento). Um personagem invisível recebe camuflagem total, +10 em testes de Furtividade contra ouvir e criaturas que não possam vê-lo ficam desprevenidas contra seus ataques. A magia termina se o alvo faz uma ação hostil contra uma criatura. Ações contra objetos livres não dissipam a Invisibilidade (você pode tocar ou apanhar objetos que não estejam sendo segurados por outras criaturas). Causar dano indiretamente — por exemplo, acendendo o pavio de um barril de pólvora que vai detonar mais tarde — não é considerado um ataque. Objetos soltos pelo alvo voltam a ser visíveis e objetos apanhados por ele ficam invisíveis. Qualquer parte de um item carregado que se estenda além de seu alcance corpo a corpo natural se torna visível. Uma luz nunca fica invisível (mesmo que sua fonte seja).',
            'usability' => 'buff',
            'type' => 'arcana',
            'circle' => 2,
            'school' => 'ilusao',
            'action_cost' => 'free',
            'range' => 'pessoal',
            'info_affects' => 'você',
            'duration' => '1 rodada',
            'resistance' => null,
            'buff_affects' => ['caster'],
            'icon_file_name' => 'magia_invisibilidade_01.webp',
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 50],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 10, 'usability' => 'roll_active'],
            ],
            'enhancements' => [
                [
                    'description' => 'muda a execução para ação padrão, o alcance para toque e o alvo para 1 criatura ou 1 objeto Grande ou menor.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                    'effects' => [
                        ['tag' => 'add_buff_affects', 'op' => 'grant', 'value' => 'allies'],
                    ],
                ],
                [
                    'description' => 'muda a duração para cena. Requer 3º círculo.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'unique_change_group' => '2',
                ],
                [
                    'description' => 'muda a duração para sustentada. Em vez do normal, o alvo gera uma esfera de invisibilidade. Não pode ser usado em conjunto com outros aprimoramentos. O alvo e todas as criaturas a até 3m dele se tornam invisíveis, como no efeito normal da magia (ainda ficam visíveis caso façam uma ação hostil). A esfera se move juntamente com o alvo qualquer coisa que saia da esfera fica visível. Requer 3º círculo.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'unique_change_group' => '2',
                ],
                [
                    'description' => 'muda a execução para ação padrão, o alcance para toque e o alvo para 1 criatura. A magia não é dissipada caso o alvo faça uma ação hostil. Requer 4º círculo.',
                    'pm_cost' => 7,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 4,
                    'unique_change_group' => '1',
                    'effects' => [
                        ['tag' => 'add_buff_affects', 'op' => 'grant', 'value' => 'allies'],
                    ],
                ],
            ],
        ]);
    }
}
