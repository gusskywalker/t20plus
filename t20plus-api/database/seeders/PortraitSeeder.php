<?php

namespace Database\Seeders;

use App\Models\Portrait;
use Illuminate\Database\Seeder;

class PortraitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // File prefix -> race id(s), for every prefix under
        // public/images/portraits (frontend repo, not this one — the files
        // themselves live there, this seeder just needs to enumerate them)
        // whose name unambiguously matches one race from RaceSeeder (or was
        // explicitly resolved: elfos -> every "elf" race, minotauros also
        // covers Minauro, orcs also covers Meio-Orc, hynne reuses the files
        // originally cropped as "duende", renamed to match). A prefix with
        // no matching playable race at all (animais, construtos, demonios,
        // dragoes, monstros — these are master/NPC art, not player races)
        // is left unseeded.
        $racePortraits = [
            'anoes' => [1], // Anão
            'bugbear' => [2],
            'centauro' => [3],
            'ceratops' => [4],
            'dahllan' => [5],
            'eiradaan' => [6],
            'elfos' => [7, 8, 22], // Elfo, Elfo-do-Mar, Meio-Elfo
            'finntroll' => [9],
            'galokk' => [10],
            'gnoll' => [11],
            'goblins' => [12], // Goblin
            'harpia' => [13],
            'hobgoblin' => [14],
            'humanos' => [15], // Humano
            'hynne' => [16],
            'kaijin' => [17],
            'kappa' => [18],
            'kliren' => [19],
            'lefou' => [20],
            'medusas' => [21], // Medusa
            'minotauros' => [24, 25], // Minauro, Minotauro
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
            'nagahf' => [37], // Nagah (F)
            'nagahm' => [38], // Nagah (M)
            'nezumi' => [39],
            'ogro' => [40],
            'orcs' => [23, 41], // Meio-Orc, Orc
            'osteon' => [42],
            'pteros' => [43],
            'qareen' => [44],
            'satiro' => [45], // Sátiro
            'sereia' => [46], // Sereia/Tritão
            'tritao' => [46], // Sereia/Tritão
            'silfide' => [47], // Sílfide
            'aggelus' => [48], // Suraggel (Aggelus)
            'sulfure' => [49], // Suraggel (Sulfure)
            'tabrachi' => [50],
            'tengu' => [51],
            'trogg' => [52], // Trog (filename has an extra "g")
            'velocis' => [53],
            'voracis' => [54],
            'yidishan' => [55],
        ];

        $portraitsDir = base_path('../t20plus-frontend/public/images/portraits');

        // 'id' is hardcoded on every row, same convention as every other
        // seeder — just computed here via a running counter instead of
        // typed out, since nothing else references a specific portrait id.
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
