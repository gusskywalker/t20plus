<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ComplicationGrantedPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Power::create([
            'id' => 25,
            'name' => 'Chato',
            'description' => 'Sempre que você sai de uma aldeia, uma festa acontece. Você sofre –5 em Diplomacia e a atitude inicial de NPCs em relação a você é uma categoria pior.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'chato_01.webp',
            // The NPC-attitude clause is pure roleplay (master call, no
            // stored state to check it against) — not modeled here.
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => -5], // Diplomacia
            ],
        ]);

        Power::create([
            'id' => 26,
            'name' => 'Abatido',
            'description' => 'Seu vigor se foi. Você recebe –2 PV por nível.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'abatido_01.webp',
            'effects' => [
                // New tag: mod_max_pv (Pontos de Vida) — same add_per_level
                // shape as mod_max_pm's "+1 PM a cada dois níveis", just
                // per_levels: 1 here since it's every level, not every two.
                ['tag' => 'mod_max_pv', 'op' => 'add_per_level', 'value' => -2, 'per_levels' => 1],
            ],
        ]);

        Power::create([
            'id' => 27,
            'name' => 'Catarata',
            'description' => 'Seus olhos já não são os mesmos. Você sofre –5 em Percepção e Pontaria.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'catarata_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => -5], // Percepção
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 25, 'value' => -5], // Pontaria
            ],
        ]);    }
}
