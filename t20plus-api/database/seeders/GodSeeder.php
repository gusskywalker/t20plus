<?php

namespace Database\Seeders;

use App\Models\God;
use Illuminate\Database\Seeder;

class GodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gods = [
            'Aharadak',
            'Allihanna',
            'Arsenal',
            'Azgher',
            'Hyninn',
            'Kallyadranoch',
            'Khalmyr',
            'Lena',
            'Lin-Wu',
            'Marah',
            'Megalokk',
            'Nimb',
            'Oceano',
            'Sszzaas',
            'Tanna-Toh',
            'Tenebra',
            'Thwor',
            'Thyatis',
            'Valkaria',
            'Wynna',
            'Glórienn',
            'Keenn',
            'Ragnar',
            'Tauron',
            'Tilliann',
        ];

        foreach ($gods as $name) {
            God::create(['name' => $name]);
        }
    }
}
