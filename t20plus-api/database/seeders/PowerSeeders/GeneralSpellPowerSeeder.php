<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class GeneralSpellPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 351,
            'name' => 'Magia Acelerada',
            'description' => 'Muda a execução da magia para ação livre. Você só pode aplicar este aprimoramento em magias com execução de movimento, padrão ou completa e só pode lançar uma magia como ação livre por rodada.',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 4,
            'prerequisites' => [
                ['type' => 'available_spell_circle', 'min' => 2],
            ],
            'applies_when' => ['spell_action_costs' => ['movement', 'standard', 'complete']],
            'icon_file_name' => null,
        ]);
    }
}
