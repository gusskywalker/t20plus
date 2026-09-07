<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class OriginGrantedPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Power::create([
            'id' => 8,
            'name' => 'Membro da Igreja',
            'description' => 'Você consegue hospedagem confortável e informação em qualquer templo de sua divindade, para você e seus aliados.',
            // Granted through Acólito's own "Perícias e Poderes" choice
            // (see OriginSeeder power_id 8), not a general pickable power.
            'source' => 'origin_granted',
            // Only matters at the moment of resting, not a standing total
            // — same "which screen resolves this" reasoning as active/
            // roll_active, just for a future Descansar screen instead of
            // an attack/skill roll.
            'usability' => 'resting',
            'icon_file_name' => 'membro_da_igreja_01.webp',
            'effects' => [
                ['tag' => 'resting', 'op' => 'set', 'value' => 1],
            ],
        ]);    }
}
