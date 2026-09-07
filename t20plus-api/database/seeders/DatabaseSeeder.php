<?php

namespace Database\Seeders;

use Database\Seeders\PowerSeeders\AgeGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\ClassPowerSeeder;
use Database\Seeders\PowerSeeders\ComplicationGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\ConsumableGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\DivineGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\GeneralActionPowerSeeder;
use Database\Seeders\PowerSeeders\GeneralPowerSeeder;
use Database\Seeders\PowerSeeders\GolpePessoalPowerSeeder;
use Database\Seeders\PowerSeeders\ItemGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\OriginGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\TormentaPowerSeeder;
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
            RaceSeeder::class,
            OriginSeeder::class,
            GodSeeder::class,
            GeneralPowerSeeder::class,
            GeneralActionPowerSeeder::class,
            ClassPowerSeeder::class,
            DivineGrantedPowerSeeder::class,
            TormentaPowerSeeder::class,
            ItemGrantedPowerSeeder::class,
            ConsumableGrantedPowerSeeder::class,
            ComplicationGrantedPowerSeeder::class,
            AgeGrantedPowerSeeder::class,
            OriginGrantedPowerSeeder::class,
            GolpePessoalPowerSeeder::class,
            SkillSeeder::class,
            ClassSeeder::class,
            GeneralItemSeeder::class,
            AccessorySeeder::class,
            ArmorSeeder::class,
            ConditionSeeder::class,
            ItemImprovementSeeder::class,
            ItemEnchantmentSeeder::class,
            WeaponAbilitySeeder::class,
            WeaponSeeder::class,
            ShieldSeeder::class,
            PortraitSeeder::class,
            ComplicationSeeder::class,
        ]);
    }
}
