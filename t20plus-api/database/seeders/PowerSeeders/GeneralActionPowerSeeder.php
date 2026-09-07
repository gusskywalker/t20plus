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
            'description' => 'Você mira em um alvo que possa ver, dentro do alcance de sua arma. Isso anula a penalidade de -5 em testes de Pontaria realizados neste turno contra aquele alvo caso ele esteja engajado em combate corpo a corpo. <br><br>No APP, como custos de ação não são calculados, todos ataques em que você utilizou Mirar, marque Mirar na tela de rolagens. Isso permite que poderes como Tiro de Abate funcionem corretamente.',
            'source' => 'general_action',
            'usability' => 'roll_active',
            'icon_file_name' => 'mirar_01.webp',
            'action_cost' => 'movement',
            'effects' => [
                ['tag' => 'nullify_fired_weapon_melee_penalty', 'op' => 'grant'],
            ],
            // TODO implement Mirar — synthesize a checklist row in
            // attack-modal.ts (general_action, never in active_effects,
            // same idea as golpePessoalRows()), shown only for fired
            // weapons. Needed before Tiro de Abate (id 254) can actually
            // detect "was Mirar checked this roll."
        ]);
    }
}
