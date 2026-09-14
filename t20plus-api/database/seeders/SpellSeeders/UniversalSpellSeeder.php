<?php

namespace Database\Seeders\SpellSeeders;

use App\Models\Spell;
use Illuminate\Database\Seeder;

class UniversalSpellSeeder extends Seeder
{
    //This file must use IDs between 2000 and 2999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
    public function run(): void
    {
        Spell::create([
            'id' => 2000,
            'name' => 'Dissipar Magia',
            'description' => 'Você dissipa outras magias que estejam ativas, como se sua duração tivesse acabado. Note que efeitos de magias instantâneas não podem ser dissipados (não se pode dissipar uma Bola de Fogo ou Relâmpago depois que já causaram dano...). Se lançar essa magia em uma criatura ou área, faça um teste de Misticismo você dissipa as magias com CD igual ou menor que o resultado do teste. Se lançada contra um item mágico, o transforma em um item mundano por 1d6 rodadas (Vontade anula).',
            'usability' => 'utility',
            'type' => 'universal',
            'circle' => 2,
            'school' => 'abjuracao',
            'action_cost' => 'standard',
            'range' => 'médio',
            'info_affects' => '1 criatura ou 1 objeto mágico',
            'info_affected_area' => 'esfera com 3m de raio',
            'duration' => 'instantânea',
            'resistance' => null,
            'icon_file_name' => null,
            'enhancements' => [
                [
                    'description' => 'muda a área para esfera com 9m de raio. Em vez do normal, cria um efeito de disjunção. Todas as magias na área são automaticamente dissipadas e todos os itens mágicos na área, exceto aqueles que você estiver carregando, viram itens mundanos por uma cena (com direito a um teste de Vontade para evitar esse efeito).',
                    'pm_cost' => 12,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 5,
                ],
            ],
        ]);

        Spell::create([
            'id' => 2001,
            'name' => 'Runa de Proteção',
            'description' => 'Você escreve uma runa pessoal em uma superfície fixa, como uma parede ou o chão, que protege uma pequena área ao redor. Quando uma criatura entra na área afetada a runa explode, causando 6d6 pontos de dano em todos os alvos a até 6m. A criatura que ativa a runa não tem direito a teste de resistência outras criaturas na área têm direito a um teste de Reflexos para reduzir o dano à metade. Quando lança a magia, você escolhe o tipo de dano, entre ácido, eletricidade, fogo, frio, luz ou trevas. Você pode determinar que a runa se ative apenas em condições específicas — por exemplo, apenas por goblins ou apenas por mortos-vivos. Você também pode criar uma palavra mágica que impeça a runa de se ativar. Um personagem pode encontrar a runa com um teste de Investigação e desarmá-la com um teste de Ladinagem. Componente material: pó de diamante no valor de T$ 200, com o qual o conjurador desenha a runa, que brilha por alguns instantes e depois se torna praticamente invisível. <br><br>No APP, sem efeitos mecânicos. Fica por sua conta usar essa magia! Apenas os PMs serão gastos.',
            'usability' => 'utility',
            'type' => 'universal',
            'circle' => 2,
            'school' => 'abjuracao',
            'action_cost' => 'none',
            'range' => 'toque',
            'info_affected_area' => 'área de 6m de raio',
            'duration' => 'permanente até ser descarregada',
            'resistance' => null,
            'icon_file_name' => null,
            'enhancements' => [
                [
                    'description' => 'aumenta o dano em +2d6.',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para "você" e o alcance para "pessoal". Ao invés do normal, escolha uma magia de 1º círculo que você conhece e pode lançar, com tempo de execução de uma ação padrão ou menor. Você escreve a runa em seu corpo e especifica uma condição de ativação como, por exemplo, "quando eu for alvo de um ataque". Quando a condição for cumprida, você pode ativar a runa e lançar a magia escolhida como uma reação. Você só pode escrever uma runa em seu corpo ao mesmo tempo.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'como o aprimoramento anterior, mas você pode escolher magias de 2º círculo. Requer 3º círculo.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'unique_change_group' => '1',
                ],
            ],
        ]);
    }
}
