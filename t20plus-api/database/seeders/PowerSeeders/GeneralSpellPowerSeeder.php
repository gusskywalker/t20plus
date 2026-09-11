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

        Power::create([
            'id' => 355,
            'name' => 'Gênese Elemental',
            'description' => 'Além do normal, a magia cria 1d4 (+1 por círculo) capangas elementais Pequenos em espaços desocupados adjacentes ao alvo ou dentro da área de efeito da magia (deslocamento 9m, Defesa 15, dano 1d6+1 do tipo da magia, imunidade a atordoamento, cansaço, dano do seu elemento, dano não letal, efeitos de metabolismo e paralisia). Eles desaparecem quando são reduzidos a 0 PV ou no fim da cena.',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 3,
            'prerequisites' => [
                ['type' => 'available_spell_circle', 'min' => 2],
            ],
            'effects' => [
                ['tag' => 'fluff_summon_minions', 'op' => 'grant'],
            ],
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 356,
            'name' => 'Magia Dividida',
            'description' => 'A área da magia é dividida em duas, cada uma com metade do tamanho da original, que não podem se sobrepor. Por exemplo, uma Bola de Fogo (raio de 6m) pode ser dividida em duas áreas com 3m de raio.',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 2,
            'prerequisites' => [
                ['type' => 'available_spell_circle', 'min' => 2],
            ],
            'applies_when' => ['spell_has_affected_area' => true],
            'effects' => [
                ['tag' => 'fluff_split_area', 'op' => 'grant'],
            ],
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 357,
            'name' => 'Miasma Tóxico',
            'description' => 'A magia exala vapores nocivos. Um alvo que falhe no teste de resistência fica enjoado por 1 rodada. Este aprimoramento só pode ser aplicado em magias que causam dano de ácido e permitem testes de resistência.',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 1,
            'applies_when' => ['spell_damage_types' => ['acid']],
            'effects' => [
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 18],
            ],
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 358,
            'name' => 'Trovão Retumbante',
            'description' => 'A magia emite um estrondo poderoso. Um alvo que falhe no teste de resistência fica caído (apenas uma vez por cena) e surdo. Este aprimoramento só pode ser aplicado em magias que causam dano de eletricidade e permitem testes de resistência.',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 1,
            'applies_when' => ['spell_damage_types' => ['electricity']],
            'effects' => [
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 8],
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 19],
            ],
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 365,
            'name' => 'Prisão Gélida',
            'description' => 'A magia cobre os alvos com cristais de gelo. Um alvo que falhe no teste de resistência fica enredado por 1 rodada. Este aprimoramento só pode ser aplicado em magias que causam dano de frio e permitem testes de resistência.',
            'source' => 'general',
            'usability' => 'spell_enhancement',
            'action_cost' => 'none',
            'pm_cost' => 1,
            'applies_when' => ['spell_damage_types' => ['cold']],
            'effects' => [
                ['trigger' => 'on_spell_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 20],
            ],
            'icon_file_name' => null,
        ]);
    }
}
