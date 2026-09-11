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

        Power::create([
            'id' => 352,
            'name' => 'Magia Ampliada',
            'description' => 'Aumenta o alcance da magia em um passo (de curto para médio, de médio para longo) ou dobra a área de efeito da magia. Por exemplo, uma Bola de Fogo ampliada tem seu alcance aumentado para longo ou sua área aumentada para 12m de raio.',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 2,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 353,
            'name' => 'Magia Discreta',
            'description' => 'Você lança a magia sem gesticular e falar, usando apenas concentração. Isso permite lançar magias com as mãos presas, amordaçado etc. Também permite lançar magias arcanas usando armadura sem teste de Misticismo. Outros personagens só percebem que você lançou uma magia se passarem num teste de Misticismo (CD 20).',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 2,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 354,
            'name' => 'Explosão Fulgente',
            'description' => 'Suas chamas mágicas emitem um brilho intenso. Um alvo que falhe no teste de resistência fica cego por 1 rodada (ou ofuscado, se já ficou cego por este aprimoramento nessa cena). Este aprimoramento só pode ser aplicado em magias que causam dano de fogo e permitem testes de resistência.',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 1,
            'applies_when' => ['spell_damage_types' => ['fire']],
            'effects' => [
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 17],
            ],
            'icon_file_name' => null,
        ]);
    }
}
