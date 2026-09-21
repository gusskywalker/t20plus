<?php

namespace Database\Seeders\SpellSeeders;

use App\Models\Spell;
use Illuminate\Database\Seeder;

class SpecificSpellSeeder extends Seeder
{
    //This file must use IDs between 3000 and 3999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
    public function run(): void
    {
        Spell::create([
            'id' => 3000,
            'name' => 'Olhar Atordoante',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para forçar uma criatura em alcance curto a fazer um teste de Fortitude (CD Car). Se a criatura falhar, fica atordoada por uma rodada (apenas uma vez por cena).',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'usability' => 'debuff',
            'damage_type' => null,
            'action_cost' => 'movement',
            'range' => 'curto',
            'info_affects' => '1 criatura',
            'duration' => 'instantânea',
            'resistance' => 'fortitude',
            'icon_file_name' => 'olhar_atordoante_01.webp',
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 2],
            ],
        ]);

        Spell::create([
            'id' => 3001,
            'name' => 'Mau Cheiro',
            'description' => 'Você pode gastar uma ação padrão e 2 PM para expelir um gás fétido. Todas as criaturas (exceto trogs) em alcance curto devem passar em um teste de Fortitude contra veneno (CD Con) ou ficarão enjoadas durante 1d6 rodadas. Uma criatura que passe no teste de resistência fica imune a esta habilidade por um dia.',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'usability' => 'debuff',
            'damage_type' => null,
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => 'todas as criaturas (exceto trogs)',
            'duration' => 'instantânea',
            'resistance' => 'fortitude',
            'icon_file_name' => 'mau_cheiro_01.webp',
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'con'],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 18],
            ],
        ]);

        Spell::create([
            'id' => 3002,
            'name' => 'Grito Aterrorizante',
            'description' => 'Você pode gastar uma ação padrão e 1 PM para emitir um grito estridente. Criaturas em alcance curto ficam abaladas (Vontade CD Car evita).',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'usability' => 'debuff',
            'damage_type' => null,
            'action_cost' => 'standard',
            'range' => 'curto',
            'info_affects' => 'criaturas em alcance curto',
            'duration' => 'instantânea',
            'resistance' => 'vontade',
            'icon_file_name' => 'grito_aterrorizante_01.webp',
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 16],
            ],
        ]);

        // Comandar (general power 11032): modeled as a spell so the +1 actually
        // buffs allies (buff_affects allies only, not the caster).
        Spell::create([
            'id' => 3003,
            'name' => 'Comandar',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para gritar ordens para seus aliados em alcance médio. Eles recebem +1 em testes de perícia até o fim da cena.',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'usability' => 'buff',
            'damage_type' => null,
            'action_cost' => 'movement',
            'range' => 'médio',
            'info_affects' => 'aliados em alcance médio',
            'duration' => 'cena',
            'resistance' => null,
            'buff_affects' => ['allies'],
            'icon_file_name' => 'comandar_01.webp',
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 1],
                ['tag' => 'ignore_pm_limit', 'op' => 'grant'],
            ],
        ]);

        Spell::create([
            'id' => 3004,
            'name' => 'Saraivada Florestal',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para disparar folhas afiadas e lascas de madeira em um cone de 9m. Todas as criaturas nessa área sofrem 2d8 pontos de dano de corte (Ref CD Sab reduz à metade). Para cada patamar acima de iniciante, você pode gastar +1 PM para aumentar o dano em +1d8.',
            'type' => 'specific',
            'circle' => 1,
            'school' => null,
            'usability' => 'damage',
            'damage_type' => 'slashing',
            'action_cost' => 'movement',
            'range' => null,
            'info_affects' => 'todas as criaturas na área',
            'info_affected_area' => 'cone de 9m',
            'duration' => 'instantânea',
            'resistance' => 'reflexos',
            'icon_file_name' => 'saraivada_florestal_01.webp',
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'knw'],
                ['tag' => 'base_spell_dmg', 'op' => 'add', 'value' => '2d8'],
                ['trigger' => 'on_spell_fail', 'tag' => 'mod_spell_dmg', 'op' => 'multiply', 'value' => 0.5],
            ],
            'enhancements' => [
                [
                    'description' => 'aumenta o dano em +1d8.',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                    'effects' => [
                        ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => '1d8'],
                    ],
                ],
            ],
        ]);
    }
}
