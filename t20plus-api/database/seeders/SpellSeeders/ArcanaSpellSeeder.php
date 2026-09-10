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
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => '6d6'],
            ],
            'enhancements' => [
                [
                    'description' => 'aumenta o dano em +2d6.',
                    'pm_cost' => 2,
                    'cumulative' => true,
                    'is_truque' => false,
                    'tag' => 'mod_spell_dmg',
                    'op' => 'add',
                    'value' => '2d6',
                ],
                [
                    'description' => 'muda a área para efeito de esfera flamejante com tamanho Médio e a duração para cena. Em vez do normal, cria uma esfera flamejante com 1,5m de diâmetro que causa 3d6 pontos de dano a qualquer criatura no mesmo espaço. Você pode gastar uma ação de movimento para fazer a esfera voar 9m em qualquer direção. Ela é imune a dano, mas pode ser apagada com água. Uma criatura só pode sofrer dano da esfera uma vez por rodada.',
                    'pm_cost' => 2,
                    'cumulative' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda a duração para um dia ou até ser descarregada. Em vez do normal, você cria uma pequena pedra flamejante, que pode detonar como uma reação, descarregando a magia. A pedra pode ser usada como uma arma de arremesso com alcance curto. Uma vez detonada, causa o dano da magia numa área de esfera com 6m de raio.',
                    'pm_cost' => 3,
                    'cumulative' => false,
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
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => '2d6'],
                ['tag' => 'on_spell_success', 'op' => 'inflict', 'condition_id' => 2],
            ],
            'enhancements' => [
                [
                    'description' => 'você lança a magia sem gesticular ou pronunciar palavras (o que permite lançar esta magia de armadura) e a adaga se torna invisível. Se o alvo falhar no teste de resistência, não percebe que você lançou uma magia contra ele.',
                    'pm_cost' => 1,
                    'cumulative' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda a duração para um dia. Além do normal, você "finca" a adaga na mente do alvo. Enquanto a magia durar, você sabe a direção e localização do alvo, desde que ele esteja no mesmo mundo.',
                    'pm_cost' => 2,
                    'cumulative' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'aumenta o dano em +1d6.',
                    'pm_cost' => 2,
                    'cumulative' => true,
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
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'on_spell_success', 'op' => 'inflict', 'condition_id' => 2],
                ['tag' => 'on_spell_success', 'op' => 'inflict', 'condition_id' => 4],
            ],
            'enhancements' => [
                [
                    'description' => 'além do normal, as criaturas afetadas ficam vulneráveis pela cena.',
                    'pm_cost' => 2,
                    'cumulative' => false,
                    'is_truque' => false,
                    'tag' => 'on_spell_success',
                    'op' => 'inflict',
                    'condition_id' => 5,
                ],
                [
                    'description' => 'também afeta espíritos e monstros na área. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'cumulative' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'também afeta construtos, espíritos, monstros e mortos-vivos na área. Requer 3º círculo.',
                    'pm_cost' => 5,
                    'cumulative' => false,
                    'is_truque' => false,
                ],
            ],
        ]);
    }
}
