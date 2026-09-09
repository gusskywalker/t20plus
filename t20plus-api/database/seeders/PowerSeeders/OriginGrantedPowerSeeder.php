<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class OriginGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 8,
            'name' => 'Membro da Igreja',
            'description' => 'Você consegue hospedagem confortável e informação em qualquer templo de sua divindade, para você e seus aliados.',

            'source' => 'origin_granted',

            'usability' => 'resting',
            'icon_file_name' => 'membro_da_igreja_01.webp',
            'effects' => [
                ['tag' => 'resting', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 284,
            'name' => 'Armadura Trabalhada',
            'description' => 'Pode gastar 10 minutos e T$ 10 para trabalhar em uma armadura de couro (incluindo couro batido, gibão de peles e brunea), aumentar a Defesa dela em +1 e reduzir sua penalidade de armadura em –2 por um dia. <br><br>No APP, ative o poder para gastar os Tibares e receber os bônus. Caso faça para um companheiro, ele deve usar "Adicionar Poder" e ativar o poder no seu próprio personagem.',
            'source' => 'origin_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'spend_tibares', 'op' => 'add', 'value' => 10],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_armor_penalty', 'op' => 'add', 'value' => -2],
            ],
        ]);
    }
}
