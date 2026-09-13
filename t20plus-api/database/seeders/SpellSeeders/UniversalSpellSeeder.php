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
    }
}
