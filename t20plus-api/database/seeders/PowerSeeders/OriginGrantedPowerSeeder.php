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
        ]);    }
}
