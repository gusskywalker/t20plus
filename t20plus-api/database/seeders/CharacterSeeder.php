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

        Character::create([
            'user_id' => 1,
            'campaign_id' => $campaignId,
            'name' => 'Thalos Ferreira',
            'level' => 3,
            'secret_code' => '7#K2_',
            'base_str' => 4,
            'base_dex' => 1,
            'base_con' => 2,
            'base_int' => -2,
            'base_knw' => 0,
            'base_car' => -1,
        ]);

        Character::create([
            'user_id' => 1,
            'campaign_id' => $campaignId,
            'name' => 'Ilyra Ventomar',
            'level' => 2,
            'secret_code' => '1!X9%',
            'base_str' => -1,
            'base_dex' => 4,
            'base_con' => 0,
            'base_int' => 2,
            'base_knw' => 1,
            'base_car' => 3,
        ]);

        Character::create([
            'user_id' => 1,
            'campaign_id' => $campaignId,
            'name' => 'Braum Pedraço',
            'level' => 5,
            'secret_code' => '5@P1#',
            'base_str' => 5,
            'base_dex' => -1,
            'base_con' => 4,
            'base_int' => -2,
            'base_knw' => -1,
            'base_car' => 0,
        ]);

        Character::create([
            'user_id' => 1,
            'campaign_id' => $campaignId,
            'name' => 'Seraphine Noturna',
            'level' => 4,
            'secret_code' => '9%L3!',
            'base_str' => -2,
            'base_dex' => 2,
            'base_con' => -1,
            'base_int' => 4,
            'base_knw' => 3,
            'base_car' => 2,
        ]);

        Character::create([
            'user_id' => 1,
            'campaign_id' => $campaignId,
            'name' => 'Grokk Punhoférreo',
            'level' => 1,
            'secret_code' => '2_R7@',
            'base_str' => 3,
            'base_dex' => 0,
            'base_con' => 3,
            'base_int' => -3,
            'base_knw' => -2,
            'base_car' => -1,
        ]);

        Character::create([
            'user_id' => 1,
            'campaign_id' => $campaignId,
            'name' => 'Wren Sussurro',
            'level' => 6,
            'secret_code' => '4#8_%',
            'base_str' => 0,
            'base_dex' => 3,
            'base_con' => 1,
            'base_int' => 1,
            'base_knw' => 4,
            'base_car' => -2,
        ]);
    }
}
