<?php

namespace Database\Seeders;

use App\Models\ItemImprovement;
use Illuminate\Database\Seeder;

class ItemImprovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        ItemImprovement::create([
            'id' => 1,
            'name' => 'Farpada',
            'description' => 'Acertos críticos provocam sangramento.',
            'is_material' => false,
            'applies_to' => ['weapon'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 13], // Farpada (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 2,
            'name' => 'Matéria Vermelha',
            'description' => 'Qualquer material de origem lefeu — desde suas garras e carapaças, até minérios e partes de estruturas encontradas em áreas de Tormenta — apresenta propriedades parecidas, sendo conhecido como "matéria vermelha". Estes itens assustadores impõem ao usuário penalidade de –2 em perícias baseadas em Carisma (exceto Intimidação). Arma: causa +1d6 de dano extra, mas o usuário perde 1 ponto de vida ao acertar (Lefou e lefeu são imunes tanto ao dano extra quanto à perda de vida). Armadura e Escudo: chance de falha de 10% (escudos e armaduras leves) ou 25% (armaduras pesadas), cumulativas entre si (Lefeu ignoram este efeito). Esotérico: você e inimigos em alcance curto sofrem -2 em testes de resistência contra efeitos mágicos. Instrumentos Musicais: +1 CD das habilidades de bardo, exceto magias.',
            'is_material' => true,
            'applies_to' => ['weapon', 'armor', 'shield', 'esoteric', 'tool'],
            'effects' => [
                ['tag' => 'skill_group', 'op' => 'add', 'attribute' => 'car', 'value' => -2, 'exclude_skill_id' => 14], // Intimidação excluded
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14, 'when_category' => 'weapon'], // Arma - Matéria Vermelha
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17, 'when_category' => 'esoteric'], // Esotérico (Portador)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 19, 'when_category' => 'esoteric'], // Esotérico (Inimigos Próximos)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 18, 'when_category' => 'tool'], // Instrumento Musical (no tool catalog table yet)
            ],
        ]);
    }
}
