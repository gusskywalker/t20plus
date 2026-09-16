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
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 2],
            ],
        ]);
    }
}
