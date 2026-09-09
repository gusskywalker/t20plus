<?php

namespace Database\Seeders;

use App\Models\Complication;
use Illuminate\Database\Seeder;

class ComplicationSeeder extends Seeder
{

    public function run(): void
    {

        Complication::create([
            'id' => 1,
            'name' => 'Chato',
            'description' => 'Sempre que você sai de uma aldeia, uma festa acontece. Você sofre –5 em Diplomacia e a atitude inicial de NPCs em relação a você é uma categoria pior.',
            'type' => 'general',
            'power_ids' => [25],
        ]);

        Complication::create([
            'id' => 2,
            'name' => 'Abatido',
            'description' => 'Seu vigor se foi. Você recebe –2 PV por nível.',
            'type' => 'age',
            'power_ids' => [26],
        ]);

        Complication::create([
            'id' => 3,
            'name' => 'Catarata',
            'description' => 'Seus olhos já não são os mesmos. Você sofre –5 em Percepção e Pontaria.',
            'type' => 'age',
            'power_ids' => [27],
        ]);

        Complication::create([
            'id' => 4,
            'name' => 'Teimoso',
            'description' => 'Sempre que falha em um teste de atributo ou de perícia que possa tentar novamente, você é obrigado a tentar pelo menos mais uma vez. Teimoso é quem teima com você!',
            'type' => 'age',
            'power_ids' => [274],
        ]);

        Complication::create([
            'id' => 5,
            'name' => 'Matugo',
            'description' => 'Você não se dá bem em cidades. Fica alquebrado em ambientes urbanos e, quando descansa nesses ambientes, sua recuperação é uma categoria pior (se já era ruim, você recupera apenas 1 PV e 1 PM, independentemente do seu nível).',
            'type' => 'general',
            'power_ids' => [275],
        ]);
    }
}
