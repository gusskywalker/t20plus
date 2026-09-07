<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class TormentaPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Power::create([
            'id' => 20,
            'name' => 'Armamento Aberrante',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para produzir uma versão orgânica de qualquer arma corpo a corpo ou de arremesso com a qual seja proficiente — ela brota do seu braço, ombro ou costas como uma planta grotesca e então se desprende. O dano da arma aumenta em um passo para cada dois outros poderes da Tormenta que você possui. A arma dura pela cena, então se desfaz numa poça de gosma.',
            'source' => 'tormenta',
            'usability' => 'active',
            'icon_file_name' => 'armamento_aberrante_01.webp',
            'action_cost' => 'movement',
            'duration' => 'scene',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'power_type', 'value' => 'tormenta'], // "outro poder da Tormenta"
            ],
            // effects not modeled: creating a temporary weapon-copy (of any
            // weapon the character is proficient with) and scaling its
            // damage by floor(other Tormenta powers / 2) steps needs
            // infrastructure (weapon templating, live power-count scaling)
            // that doesn't exist. Self-reported for now — accepted gap, per
            // claude-stuff/tag-system.md "Parked".
        ]);    }
}
