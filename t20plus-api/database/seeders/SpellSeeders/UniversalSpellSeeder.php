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
            'icon_file_name' => 'dissipar_magia_01.webp',
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
            'icon_file_name' => 'runa_de_protecao_01.webp',
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

        Spell::create([
            'id' => 2002,
            'name' => 'Luz',
            'description' => 'O alvo emite luz (mas não produz calor) em uma área com 6m de raio. O objeto pode ser guardado (em um bolso, por exemplo) para interromper a luz, que voltará a funcionar caso o objeto seja revelado. Se lançar a magia num objeto de uma criatura involuntária, ela tem direito a um teste de Vontade para anulá-la. Luz anula Escuridão.',
            'usability' => 'utility',
            'type' => 'universal',
            'circle' => 1,
            'school' => 'evocacao',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 objeto',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'buff_affects' => ['caster', 'allies'],
            'icon_file_name' => 'magia_luz_01.webp',
            'enhancements' => [
                [
                    'description' => 'aumenta a área iluminada em +3m de raio.',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda a duração para um dia.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => 'duracao',
                ],
                [
                    'description' => 'muda a duração para permanente e adiciona componente material (pó de rubi no valor de T$ 50). Não pode ser usado em conjunto com outros aprimoramentos. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                    'unique_change_group' => 'duracao',
                ],
                [
                    'description' => '(Apenas Arcanos) muda o alvo para 1 criatura. Você lança a magia nos olhos do alvo, que fica ofuscado pela cena. Não afeta criaturas cegas.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'change_usability', 'op' => 'set', 'value' => 'debuff'],
                        ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 4],
                    ],
                ],
                [
                    'description' => '(Apenas Arcanos) muda o alcance para longo e o efeito para cria 4 pequenos globos flutuantes de pura luz. Você pode posicionar os globos onde quiser dentro do alcance e movê-los uma vez por rodada com uma ação livre. Cada um ilumina como uma tocha, mas não produz calor. Se um globo ocupar o espaço de uma criatura, ela fica ofuscada e sua silhueta pode ser vista claramente. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                ],
                [
                    'description' => '(Apenas Divinos) a luz é cálida como a do sol. Criaturas que sofrem penalidades e dano pela luz solar sofrem seus efeitos como se estivessem expostos à luz solar real. Seus aliados na área estabilizam automaticamente e ficam imunes à condição sangrando, e seus inimigos ficam ofuscados. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                ],
                [
                    'description' => '(Apenas Divinos) muda o alcance para toque e o alvo para 1 criatura. Em vez do normal, o alvo é envolto por um halo de luz, recebendo +10 em testes de Diplomacia e redução de trevas 10. Requer 2º círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                    'effects' => [
                        ['tag' => 'change_usability', 'op' => 'set', 'value' => 'buff'],
                        ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 10],
                        ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'darkness'],
                    ],
                ],
            ],
        ]);

        Spell::create([
            'id' => 2003,
            'name' => 'Escuridão',
            'description' => 'O alvo emana sombras em uma área com 6m de raio. Criaturas dentro da área recebem camuflagem leve por escuridão leve. As sombras não podem ser iluminadas por nenhuma fonte de luz natural. O objeto pode ser guardado (em um bolso, por exemplo) para interromper a escuridão, que voltará a funcionar caso o objeto seja revelado. Se lançar a magia num objeto de uma criatura involuntária, ela tem direito a um teste de Vontade para anulá-la. Escuridão anula Luz.',
            'usability' => 'utility',
            'type' => 'universal',
            'circle' => 1,
            'school' => 'necromancia',
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => '1 objeto',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'buff_affects' => ['caster'],
            'icon_file_name' => 'magia_escuridao_01.webp',
            'enhancements' => [
                [
                    'description' => 'aumenta a área da escuridão em +1,5m de raio.',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o efeito para fornecer camuflagem total por escuridão total. As sombras bloqueiam a visão na área e através dela. No APP, aliados dentro da área devem adicionar a condição a si mesmos.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'change_usability', 'op' => 'set', 'value' => 'buff'],
                        ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 50],
                    ],
                ],
                [
                    'description' => 'muda o alvo para 1 criatura e a resistência para Fortitude parcial. Você lança a magia nos olhos do alvo, que fica cego pela cena. Se passar na resistência, fica cego por 1 rodada. Requer 2º círculo.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                    'effects' => [
                        ['tag' => 'change_usability', 'op' => 'set', 'value' => 'debuff'],
                        ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 17],
                        ['trigger' => 'on_spell_fail', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 17],
                    ],
                ],
                [
                    'description' => 'muda a duração para um dia.',
                    'pm_cost' => 3,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alcance para pessoal e o alvo para você. Em vez do normal, você é coberto por sombras, recebendo +10 em testes de Furtividade e camuflagem leve. Requer 2º círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 2,
                    'effects' => [
                        ['tag' => 'change_usability', 'op' => 'set', 'value' => 'buff'],
                        ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 10],
                    ],
                ],
            ],
        ]);

        Spell::create([
            'id' => 2004,
            'name' => 'Aviso',
            'description' => 'Envia um aviso telepático para uma criatura, mesmo que não possa vê-la nem tenha linha de efeito. Escolha um: <br><br>Alerta: o alvo recebe +5 em seu próximo teste de Iniciativa e de Percepção dentro da cena. <br>Mensagem: o alvo recebe uma mensagem sua de até 25 palavras. Vocês devem ter um idioma em comum para o alvo poder entendê-lo. <br>Localização: o alvo sabe onde você está naquele momento. Se você mudar de posição, ele não saberá.',
            'usability' => 'utility',
            'type' => 'universal',
            'circle' => 1,
            'school' => 'adivinhacao',
            'action_cost' => 'movement',
            'range' => 'longo',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => null,
            'buff_affects' => ['caster', 'allies'],
            'icon_file_name' => 'aviso_01.webp',
            'enhancements' => [
                [
                    'description' => 'Alerta: o alvo recebe +5 em seu próximo teste de Iniciativa e de Percepção dentro da cena.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => 'aviso',
                    'effects' => [
                        ['tag' => 'change_usability', 'op' => 'set', 'value' => 'buff'],
                        ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 5],
                        ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 5],
                    ],
                ],
                [
                    'description' => 'Mensagem: o alvo recebe uma mensagem sua de até 25 palavras. Vocês devem ter um idioma em comum para o alvo poder entendê-lo.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => 'aviso',
                ],
                [
                    'description' => 'Localização: o alvo sabe onde você está naquele momento. Se você mudar de posição, ele não saberá.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => 'aviso',
                ],
                [
                    'description' => 'aumenta o alcance em um fator de 10 (90m para 900m, 900m para 9km e assim por diante).',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'se escolher mensagem, o alvo pode enviar uma resposta de até 25 palavras para você até o fim de seu próximo turno.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'requires_enhancement_index' => 1,
                ],
                [
                    'description' => 'se escolher localização, muda a duração para cena. O alvo sabe onde você está mesmo que você mude de posição.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                    'requires_enhancement_index' => 2,
                ],
                [
                    'description' => 'aumenta o número de alvos em +1.',
                    'pm_cost' => 3,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
            ],
        ]);
    }
}
