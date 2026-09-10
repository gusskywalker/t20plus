<?php

namespace Database\Seeders;

use Database\Seeders\PowerSeeders\AgeGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\ClassArcanistaPowerSeeder;
use Database\Seeders\PowerSeeders\ClassCacadorPowerSeeder;
use Database\Seeders\PowerSeeders\ClassGuerreiroPowerSeeder;
use Database\Seeders\PowerSeeders\ClassSharedPowerSeeder;
use Database\Seeders\PowerSeeders\ComplicationGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\ConditionPowerSeeder;
use Database\Seeders\PowerSeeders\ConsumableGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\DivineGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\GeneralActionPowerSeeder;
use Database\Seeders\PowerSeeders\GeneralPowerSeeder;
use Database\Seeders\PowerSeeders\GolpePessoalPowerSeeder;
use Database\Seeders\PowerSeeders\ItemGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\OriginGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\RaceGrantedPowerSeeder;
use Database\Seeders\PowerSeeders\RaceOptionalPowerSeeder;
use Database\Seeders\PowerSeeders\TormentaPowerSeeder;
use Database\Seeders\SpellSeeders\ArcanaSpellSeeder;
use Database\Seeders\SpellSeeders\DivinaSpellSeeder;
use Database\Seeders\SpellSeeders\UniversalSpellSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RaceSeeder::class,
            OriginSeeder::class,
            GodSeeder::class,
            GeneralPowerSeeder::class,
            GeneralActionPowerSeeder::class,
            ClassSharedPowerSeeder::class,
            ClassGuerreiroPowerSeeder::class,
            ClassCacadorPowerSeeder::class,
            ClassArcanistaPowerSeeder::class,
            DivineGrantedPowerSeeder::class,
            TormentaPowerSeeder::class,
            ItemGrantedPowerSeeder::class,
            ConsumableGrantedPowerSeeder::class,
            ComplicationGrantedPowerSeeder::class,
            AgeGrantedPowerSeeder::class,
            OriginGrantedPowerSeeder::class,
            RaceGrantedPowerSeeder::class,
            RaceOptionalPowerSeeder::class,
            GolpePessoalPowerSeeder::class,
            ArcanaSpellSeeder::class,
            DivinaSpellSeeder::class,
            UniversalSpellSeeder::class,
            SkillSeeder::class,
            ClassSeeder::class,
            GeneralItemSeeder::class,
            AccessorySeeder::class,
            ArmorSeeder::class,
            ConditionSeeder::class,
            ConditionPowerSeeder::class,
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
