<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ComplicationGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 25,
            'name' => 'Chato',
            'description' => 'Sempre que você sai de uma aldeia, uma festa acontece. Você sofre –5 em Diplomacia e a atitude inicial de NPCs em relação a você é uma categoria pior.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'chato_01.webp',

            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 26,
            'name' => 'Abatido',
            'description' => 'Seu vigor se foi. Você recebe –2 PV por nível.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'abatido_01.webp',
            'effects' => [

                ['tag' => 'mod_max_pv', 'op' => 'add_per_level', 'value' => -2, 'per_levels' => 1],
            ],
        ]);

        Power::create([
            'id' => 27,
            'name' => 'Catarata',
            'description' => 'Seus olhos já não são os mesmos. Você sofre –5 em Percepção e Pontaria.',
            'source' => 'complication_granted',
            'usability' => 'passive',
            'icon_file_name' => 'catarata_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => -5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 25, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 274,
            'name' => 'Teimoso',
            'description' => 'Sempre que falha em um teste de atributo ou de perícia que possa tentar novamente, você é obrigado a tentar pelo menos mais uma vez. Teimoso é quem teima com você!',
            'source' => 'complication_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'teimoso_01.webp',
        ]);

        Power::create([
            'id' => 275,
            'name' => 'Matugo',
            'description' => 'Você não se dá bem em cidades. Fica alquebrado em ambientes urbanos e, quando descansa nesses ambientes, sua recuperação é uma categoria pior (se já era ruim, você recupera apenas 1 PV e 1 PM, independentemente do seu nível). <br><br>No APP, ative a condição Alquebrado manualmente.',
            'source' => 'complication_granted',
            'usability' => 'resting',
            'icon_file_name' => 'matugo_01.webp',
            'effects' => [
                ['tag' => 'resting', 'op' => 'set', 'value' => -1],
            ],
        ]);
    }
}
