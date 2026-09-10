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
            'name' => 'Trabalhar Armadura de Couro',
            'description' => 'Pode gastar 10 minutos e T$ 10 para trabalhar em uma armadura de couro (incluindo couro batido, gibão de peles e brunea), aumentar a Defesa dela em +1 e reduzir sua penalidade de armadura em –2 por um dia. <br><br>No APP, ative o poder para gastar os Tibares e receber os bônus. Caso faça para um companheiro, ele deve usar "Adicionar Poder" e ativar o poder no seu próprio personagem.',
            'source' => 'origin_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => 'trabalhar_armadura_01.webp',
            'effects' => [
                ['tag' => 'spend_tibares', 'op' => 'add', 'value' => 10],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_armor_penalty', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 323,
            'name' => 'Cão de Briga',
            'description' => 'Na primeira vez a cada cena em que você faz a ação agredir, pode fazer um ataque extra.',
            'source' => 'origin_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'cao_de_briga_01.webp',
        ]);

        Power::create([
            'id' => 326,
            'name' => 'Coração Heroico',
            'description' => 'Você recebe +3 pontos de mana. Quando atinge um novo patamar (no 5°, 11° e 17° níveis), recebe +3 PM.',
            'source' => 'origin_granted',
            'usability' => 'passive',
            'icon_file_name' => 'coracao_heroico_01.webp',
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 3],
                ['tag' => 'mod_max_pm', 'op' => 'add_per_patamar', 'value' => 3],
            ],
        ]);
    }
}
