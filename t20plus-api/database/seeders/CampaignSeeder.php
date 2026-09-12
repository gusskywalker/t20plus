<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{

    public function run(): void
    {
        //TODO placeholder campaign until campaign creation/selection is actually implemented
        Campaign::create([
            'id' => 1,
            'user_id' => 1,
            'name' => 'Campanha Padrão',
        ]);
    }
}
