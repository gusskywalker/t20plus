<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 1000 and 1999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class AgeGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 28,
            'name' => 'Criança',
            'description' => 'Crianças são fisicamente mais fracas e frágeis que adultos, além de menos capazes de entender as sutilezas do mundo.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'crianca_01.webp',
            'effects' => [
                ['tag' => 'mod_str', 'op' => 'add', 'value' => -2],
                ['tag' => 'mod_con', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_knw', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 29,
            'name' => 'Tamanho Menor',
            'description' => 'Você é uma categoria de tamanho menor que o padrão de sua raça (exceto se sua raça já for Minúscula; nesse caso, a mudança é apenas estética).',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'tamanho_menor_01.webp',
            'effects' => [

                ['tag' => 'mod_size', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 30,
            'name' => 'Sem Origem',
            'description' => 'Você não recebe benefícios de origem. Você está apenas começando a viver os anos que definirão quem você será!',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sem_origem_01.webp',

        ]);

        Power::create([
            'id' => 31,
            'name' => 'Protegido dos Deuses',
            'description' => 'Você recebe +2 na Defesa e +5 em todos os testes de resistência. Isso é uma mistura de sorte sobrenatural com o fato de que inimigos normalmente ignoram crianças, justamente por serem menos perigosas.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'protegido_pelos_deuses_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],

                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 32,
            'name' => 'Adolescente',
            'description' => 'Sabedoria –1. Adolescentes são conhecidos por sua impetuosidade.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adolescente_01.webp',
            'effects' => [
                ['tag' => 'mod_knw', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 33,
            'name' => 'Ímpeto Juvenil',
            'description' => 'Você recebe +3 pontos de mana. Adolescentes acham que podem fazer qualquer coisa, e essa confiança os torna mais heroicos.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'impeto_juvenil_01.webp',
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 34,
            'name' => 'Origem em Construção',
            'description' => 'Você recebe apenas um benefício de origem, em vez de dois (se sua origem possuir um único benefício, comece com uma perícia treinada a menos por sua classe).',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'origem_em_construcao_01.webp',

        ]);

        Power::create([
            'id' => 35,
            'name' => 'Jovem',
            'description' => 'Você está na flor da idade, nem os percalços da juventude nem os fardos da maturidade o afetam.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'jovem_01.webp',

        ]);

        Power::create([
            'id' => 36,
            'name' => 'Adulto',
            'description' => 'Você está em plena maturidade. Pode receber um Poder Geral extra e escolher uma Complicação de idade.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adulto_01.webp',

        ]);

        Power::create([
            'id' => 37,
            'name' => 'Maduro',
            'description' => 'Você entra na meia-idade. Recebe um nível extra e duas Complicações de idade.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'maduro_01.webp',

        ]);

        Power::create([
            'id' => 38,
            'name' => 'Velho',
            'description' => 'Seu corpo já não responde como antes.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'velho_01.webp',
            'effects' => [
                ['tag' => 'mod_str', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_dex', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_con', 'op' => 'add', 'value' => -1],

                ['tag' => 'level_up_attribute_increase_lock', 'op' => 'grant', 'scope' => 'physical'],
            ],
        ]);

        Power::create([
            'id' => 39,
            'name' => 'Ancião',
            'description' => 'Seu corpo é frágil, mas sua mente carrega o peso da experiência.',
            'source' => 'age_granted',
            'usability' => 'passive',
            'icon_file_name' => 'anciao_01.webp',
            'effects' => [
                ['tag' => 'mod_str', 'op' => 'add', 'value' => -2],
                ['tag' => 'mod_dex', 'op' => 'add', 'value' => -2],
                ['tag' => 'mod_con', 'op' => 'add', 'value' => -2],

                ['tag' => 'level_up_attribute_increase_lock', 'op' => 'grant', 'scope' => 'physical'],
            ],
        ]);    }
}
