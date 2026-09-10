<?php

namespace Database\Seeders\SpellSeeders;

use App\Models\Spell;
use Illuminate\Database\Seeder;

class ArcanaSpellSeeder extends Seeder
{

    public function run(): void
    {

        Spell::create([
            'id' => 1,
            'name' => 'Bola de Fogo',
            'description' => 'Esta famosa magia de ataque cria uma poderosa explosão, causando 6d6 pontos de dano de fogo em todas as criaturas e objetos livres na área.',
            'type' => 'arcana',
            'circle' => 2,
            'school' => 'evocacao',
            'action_cost' => 'standard',
            'range' => 'médio',
            'effect' => 'esfera com 6m de raio',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'bola_de_fogo_01.webp',
            'effects' => [
                ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => '6d6'],
            ],
            'enhancements' => [
                [
                    'description' => 'aumenta o dano em +2d6.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                    'tag' => 'mod_spell_dmg',
                    'op' => 'add',
                    'value' => '2d6',
                ],
                [
                    'description' => 'muda a área para efeito de esfera flamejante com tamanho Médio e a duração para cena. Em vez do normal, cria uma esfera flamejante com 1,5m de diâmetro que causa 3d6 pontos de dano a qualquer criatura no mesmo espaço. Você pode gastar uma ação de movimento para fazer a esfera voar 9m em qualquer direção. Ela é imune a dano, mas pode ser apagada com água. Uma criatura só pode sofrer dano da esfera uma vez por rodada.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda a duração para um dia ou até ser descarregada. Em vez do normal, você cria uma pequena pedra flamejante, que pode detonar como uma reação, descarregando a magia. A pedra pode ser usada como uma arma de arremesso com alcance curto. Uma vez detonada, causa o dano da magia numa área de esfera com 6m de raio.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
            ],
        ]);

        Spell::create([
            'id' => 2,
            'name' => 'Adaga Mental',
            'description' => 'Você manifesta e dispara uma adaga imaterial contra a mente do alvo, que sofre 2d6 pontos de dano psíquico e fica atordoado por uma rodada. Se passar no teste de resistência, sofre apenas metade do dano e evita a condição. Uma criatura só pode ficar atordoada por esta magia uma vez por cena.',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'effect' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'vontade',
            'icon_file_name' => 'adaga_mental_01.webp',
            'effects' => [
                ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => '2d6'],
                ['tag' => 'on_spell_success', 'op' => 'inflict', 'condition_id' => 2],
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
                ],
                [
                    'description' => 'aumenta o dano em +1d6.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                    'tag' => 'mod_spell_dmg',
                    'op' => 'add',
                    'value' => '1d6',
                ],
            ],
        ]);

        Spell::create([
            'id' => 3,
            'name' => 'Leque Cromático',
            'description' => 'Um cone de luzes brilhantes surge das suas mãos, deixando os animais e humanoides na área atordoados por 1 rodada (apenas uma vez por cena, Vontade anula) e ofuscados pela cena. Esta magia não afeta criaturas cegas.',
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'ilusao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'effect' => 'cone de 4,5m',
            'duration' => 'instantânea',
            'resistance' => 'vontade',
            'icon_file_name' => 'leque_cromatico_01.webp',
            'effects' => [
                ['tag' => 'on_spell_success', 'op' => 'inflict', 'condition_id' => 2],
                ['tag' => 'on_spell_success', 'op' => 'inflict', 'condition_id' => 4],
            ],
            'enhancements' => [
                [
                    'description' => 'além do normal, as criaturas afetadas ficam vulneráveis pela cena.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'tag' => 'on_spell_success',
                    'op' => 'inflict',
                    'condition_id' => 5,
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
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'effect' => '1 humanoide',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'icon_file_name' => 'sono_01.webp',
            'effects' => [
                ['tag' => 'on_sono_cast', 'op' => 'inflict', 'condition_id' => 6],
                ['tag' => 'on_sono_cast', 'op' => 'inflict', 'condition_id' => 8],
                ['tag' => 'on_sono_cast', 'op' => 'inflict', 'condition_id' => 11],
                ['tag' => 'on_sono_cast', 'op' => 'inflict', 'condition_id' => 13],
            ],
            'enhancements' => [
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
            'type' => 'arcana',
            'circle' => 1,
            'school' => 'ilusao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'effect' => 'você',
            'duration' => 'cena',
            'resistance' => null,
            'icon_file_name' => 'imagem_espelhada_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 6],
            ],
            'enhancements' => [
                [
                    'description' => 'aumenta o número de cópias em +1 (e o bônus na Defesa em +2).',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                    'tag' => 'mod_def',
                    'op' => 'add',
                    'value' => 2,
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
            'type' => 'arcana',
            'circle' => 2,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'effect' => '1 humanoide',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'icon_file_name' => 'sussurros_insanos_01.webp',
            'effects' => [
                ['tag' => 'on_spell_success', 'op' => 'inflict', 'condition_id' => 14],
            ],
            'enhancements' => [
                [
                    'description' => 'aumenta o número de alvos em +1.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para 1 criatura.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para criaturas escolhidas. Requer 5º círculo.',
                    'pm_cost' => 12,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 5,
                ],
            ],
        ]);

        Spell::create([
            'id' => 8,
            'name' => 'Aparência Perfeita',
            'description' => 'Esta magia lhe concede um rosto idealizado, porte físico garboso, voz melodiosa e olhar sedutor. Caso seu Carisma seja 5 ou mais, você recebe +2 neste atributo. Do contrário, ele se torna 5 (isso conta como um bônus). Além disso, você recebe +5 em Diplomacia e Enganação. Quando a magia acaba, quaisquer observadores percebem a mudança e tendem a suspeitar de você. Da mesma maneira, pessoas que o viram sob o efeito da magia sentirão que "algo está errado" ao vê-lo em condições normais. Quando a cena acabar, você pode gastar os PM da magia novamente como uma ação livre para mantê-la ativa. Este efeito não fornece PV ou PM adicionais.',
            'type' => 'arcana',
            'circle' => 2,
            'school' => 'ilusao',
            'action_cost' => 'standard',
            'range' => 'pessoal',
            'effect' => 'você',
            'duration' => 'cena',
            'resistance' => null,
            'icon_file_name' => 'aparencia_perfeita_01.webp',
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
                ],
            ],
        ]);
    }
}
