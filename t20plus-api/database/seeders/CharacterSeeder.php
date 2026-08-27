<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Character;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaignId = Campaign::first()->id;

        Character::create([
            'user_id' => 1,
            'campaign_id' => $campaignId,
            'name' => 'Personagem de Teste',
            'level' => 1,
            'secret_code' => '3@9_1',
            'base_str' => 3,
            'base_dex' => 2,
            'base_con' => 1,
            'base_int' => -1,
            'base_knw' => -2,
            'base_car' => 4,
        ]);
    }
}
