<?php

namespace Database\Seeders;

use App\Models\Portrait;
use Illuminate\Database\Seeder;

class PortraitSeeder extends Seeder
{

    public function run(): void
    {

        $racePortraits = [
            'anoes' => [1],
            'bugbear' => [2],
            'centauro' => [3],
            'ceratops' => [4],
            'dahllan' => [5],
            'eiradaan' => [6],
            'elfos' => [7, 8, 22],
            'finntroll' => [9],
            'galokk' => [10],
            'gnoll' => [11],
            'goblins' => [12],
            'harpia' => [13],
            'hobgoblin' => [14],
            'humanos' => [15],
            'hynne' => [16],
            'kaijin' => [17],
            'kappa' => [18],
            'kliren' => [19],
            'lefou' => [20],
            'medusas' => [21],
            'minotauros' => [24, 25],
            'moreaubufalo' => [26],
            'moreaucoelho' => [27],
            'moreaucoruja' => [28],
            'moreaucrocodilo' => [29],
            'moreaugato' => [30],
            'moreauhiena' => [31],
            'moreauleao' => [32],
            'moreaulobo' => [33],
            'moreauraposa' => [34],
            'moreauserpente' => [35],
            'moreauurso' => [36],
            'nagahf' => [37],
            'nagahm' => [38],
            'nezumi' => [39],
            'ogro' => [40],
            'orcs' => [23, 41],
            'osteon' => [42],
            'pteros' => [43],
            'qareen' => [44],
            'satiro' => [45],
            'sereia' => [46],
            'tritao' => [46],
            'silfide' => [47],
            'aggelus' => [48],
            'sulfure' => [49],
            'tabrachi' => [50],
            'tengu' => [51],
            'trogg' => [52],
            'velocis' => [53],
            'voracis' => [54],
            'yidishan' => [55],
        ];

        $portraitsDir = base_path('../t20plus-frontend/public/images/portraits');

        $nextId = 1;
        foreach ($racePortraits as $prefix => $raceIds) {
            $files = glob("{$portraitsDir}/{$prefix}_*.webp");
            natsort($files);
            foreach ($files as $path) {
                Portrait::create([
                    'id' => $nextId++,
                    'file_name' => basename($path),
                    'race_ids' => $raceIds,
                ]);
            }
        }
    }
}
