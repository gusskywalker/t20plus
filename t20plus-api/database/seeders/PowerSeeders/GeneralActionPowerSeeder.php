<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 10000 and 10999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class GeneralActionPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 253,
            'name' => 'Mirar',
            'description' => 'Você mira em um alvo que possa ver, dentro do alcance de sua arma. Isso anula a penalidade de -5 em testes de Pontaria realizados neste turno contra aquele alvo caso ele esteja engajado em combate corpo a corpo. <br><br>No APP, ative o poder quando mirar. No final do seu ataque mirado, desative-o.',
            'source' => 'general_action',

            'usability' => 'active',
            'duration' => 'turn',
            'icon_file_name' => 'mirar_01.webp',
            'action_cost' => 'movement',
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'nullify_ranged_weapon_melee_penalty', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 262,
            'name' => 'Alvo em Cbt. Corpo a Corpo',
            'description' => 'Seu alvo está envolvido em combate corpo a corpo (com você ou com outra criatura), o que impõe -5 em testes de Pontaria contra ele.',
            'source' => 'general_action',
            'usability' => 'roll_active',
            'icon_file_name' => 'alvo_em_combate_corpo_a_corpo_01.webp',
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -5],
            ],
        ]);
    }
}
