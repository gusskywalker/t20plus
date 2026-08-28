<?php

namespace Database\Seeders;

use App\Models\Origin;
use Illuminate\Database\Seeder;

class OriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Origin::create(['name' => 'Acólito']);
        Origin::create(['name' => 'Amigo dos Animais']);
        Origin::create(['name' => 'Amnésico']);
    }
}
