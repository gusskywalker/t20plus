<?php

namespace Database\Seeders;

use App\Models\Portrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortraitSeeder extends Seeder
{

    public function run(): void
    {

        $racePortraits = [
            'anao' => [1],
            'bugbear' => [2],
            'centauro' => [3],
            'ceratops' => [4],
            'dahllan' => [5],
            'eiradaan' => [6],
            'elfo' => [7, 8, 22],
            'finntroll' => [9],
            'galokk' => [10],
            'golem' => [61],
            'gnoll' => [11],
            'goblin' => [12],
            'harpia' => [13],
            'hobgoblin' => [14],
            'humano' => [15],
            'duplo' => [56],
            'duende' => [60],
            'hynne' => [16],
            'kaijin' => [17],
            'kappa' => [18],
            'kliren' => [19],
            'lefou' => [20],
            'medusa' => [21],
            'minauro' => [24],
            'minotauro' => [25],
            'moreau_bufalo' => [26],
            'moreau_coelho' => [27],
            'moreau_coruja' => [28],
            'moreau_crocodilo' => [29],
            'moreau_gato' => [30],
            'moreau_hiena' => [31],
            'moreau_leao' => [32],
            'moreau_lobo' => [33],
            'moreau_raposa' => [34],
            'moreau_serpente' => [35],
            'moreau_urso' => [36],
            'moreau_morcego' => [59],
            'nagah_female' => [37],
            'nagah_male' => [38],
            'nezumi' => [39],
            'ogro' => [40],
            'meio_orc' => [23],
            'orc' => [41],
            'osteon' => [42, 57],
            'pteros' => [43],
            'qareen' => [44],
            'satiro' => [45],
            'tritao_sereia' => [46],
            'silfide' => [47],
            'suraggel_aggelus' => [48],
            'suraggel_sulfure' => [49],
            'tabrachi' => [50],
            'tengu' => [51],
            'trog' => [52, 58],
            'velocis' => [53],
            'voracis' => [54],
            'yidishan' => [55],
        ];

        $portraitsDir = base_path('../t20plus-frontend/public/images/portraits');

        DB::transaction(function () use ($racePortraits, $portraitsDir) {
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
        });
    }
}
