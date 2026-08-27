<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Character;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignCharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('campaign_characters')->insert([
            'campaign_id' => Campaign::first()->id,
            'character_id' => Character::first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
