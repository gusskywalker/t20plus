<?php

namespace Database\Seeders\SpellSeeders;

use App\Models\Spell;
use Illuminate\Database\Seeder;

class DivinaSpellSeeder extends Seeder
{

    public function run(): void
    {

        Spell::create([
            'id' => 6,
            'name' => 'Comando',
            'description' => 'Você dá uma ordem irresistível, que o alvo deve ser capaz de ouvir (mas não precisa entender). Se falhar na resistência, ele deve obedecer ao comando em seu próprio turno da melhor maneira possível. Escolha um dos efeitos. Fuja: o alvo gasta seu turno se afastando de você (usando todas as suas ações). Largue: o alvo solta quaisquer itens que esteja segurando e não pode pegá-los novamente até o início de seu próximo turno. Como esta é uma ação livre, ele ainda pode executar outras ações (exceto pegar aquilo que largou). Pare: o alvo fica pasmo (apenas uma vez por cena). Senta: com uma ação livre, o alvo senta no chão (se estava pendurado ou voando, desce até o chão). Ele pode fazer outras ações, mas não se levantar até o início de seu próximo turno. Venha: o alvo gasta seu turno se aproximando de você (usando todas as suas ações).',
            'type' => 'divina',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'effect' => '1 humanoide',
            'duration' => '1 rodada',
            'resistance' => 'vontade',
            'icon_file_name' => 'comando_01.webp',
            'enhancements' => [
                [
                    'description' => 'muda o alvo para 1 criatura.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
                [
                    'description' => 'aumenta a quantidade de alvos em +1.',
                    'pm_cost' => 2,
                    'repeatable' => false,
                    'is_truque' => false,
                ],
            ],
        ]);
    }
}
