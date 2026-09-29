<?php

namespace Database\Seeders;

use App\Models\Companion;
use Illuminate\Database\Seeder;

class CompanionSeeder extends Seeder
{

    public function run(): void
    {

        Companion::create([
            'id' => 1,
            'name' => 'Monstro Conjurado',
            'description' => 'Um monstro Pequeno que ataca seus inimigos. Não é uma criatura real, e sim um construto feito de energia. Se for destruído, ou quando a magia acaba, desaparece com um brilho, sem deixar nada para trás. O monstro não age sem receber uma ordem.',
            'type' => 'conjurado',
            'base_stats' => [
                'base_size' => -1,
                'base_str' => 2,
                'base_dex' => 3,
                'base_defense' => 'caster',
                'base_reflexes' => 'caster',
                'base_max_pv' => 20,
                'base_movement' => 9,
                'base_attack' => [
                    'dmg' => '2d4+2',
                    'reach' => 0,
                    'damage_types' => ['slashing', 'bludgeoning', 'piercing'],
                ],
                'base_immunities' => ['fortitude', 'vontade'],
            ],
            'source_spell_id' => 33,
            'icon_file_name' => 'conjurar_monstro_01.webp',
        ]);
    }
}
