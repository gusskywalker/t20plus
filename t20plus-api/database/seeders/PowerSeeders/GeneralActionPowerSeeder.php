<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class GeneralActionPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Power::create([
            'id' => 253,
            'name' => 'Mirar',
            'description' => 'Você mira em um alvo que possa ver, dentro do alcance de sua arma. Isso anula a penalidade de -5 em testes de Pontaria realizados neste turno contra aquele alvo caso ele esteja engajado em combate corpo a corpo. <br><br>No APP, ative o poder quando mirar. No final do seu ataque mirado, desative-o.',
            'source' => 'general_action',
            // Toggled from the sheet's Efeitos Ativos, same as Marca da
            // Presa/Escaramuça — not a per-roll checklist checkbox. duration
            // 'turn' matches "neste turno" and is already an established
            // bucket (see Escaramuça (Defesa), ClassCacadorPowerSeeder.php).
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
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -5],
            ],
        ]);
    }
}
