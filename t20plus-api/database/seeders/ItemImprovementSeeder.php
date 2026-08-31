<?php

namespace Database\Seeders;

use App\Models\ItemImprovement;
use Illuminate\Database\Seeder;

class ItemImprovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        ItemImprovement::create([
            'id' => 1,
            'name' => 'Farpada',
            'description' => 'Acertos críticos provocam sangramento.',
            'is_material' => false,
            'applies_to' => ['weapon'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 13], // Farpada (item_granted)
            ],
        ]);
    }
}
