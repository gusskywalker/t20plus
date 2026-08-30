<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CampaignSeeder::class,
            CharacterSeeder::class,
            CampaignCharacterSeeder::class,
            RaceSeeder::class,
            OriginSeeder::class,
            GodSeeder::class,
            PowerSeeder::class,
            SkillSeeder::class,
            ClassSeeder::class,
            AccessorySeeder::class,
            ArmorSeeder::class,
        ]);
    }
}
