<?php

namespace Database\Seeders;

use App\Models\GeneralItem;
use Illuminate\Database\Seeder;

class GeneralItemSeeder extends Seeder
{

    public function run(): void
    {

        GeneralItem::create([
            'id' => 1,
            'name' => 'Essência de Mana',
            'description' => 'Esta poção feita de ervas raras e compostos alquímicos recupera energia pessoal. Beber a essência de mana é uma ação padrão e recupera 1d4 pontos de mana.',
            'type' => 'potion',
            'cost' => 50,
            'slots' => 0.5,
            'icon_file_name' => 'essencia_de_mana_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 73],
            ],
            'consumable' => true,
        ]);

        GeneralItem::create([
            'id' => 2,
            'name' => 'Flechas (20)',
            'description' => 'Um feixe de 20 flechas para uso com arcos.',
            'type' => 'ammo',
            'cost' => 1,
            'slots' => 1,
            'icon_file_name' => 'flechas_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 3,
            'name' => 'Munição (20)',
            'description' => 'Um cartucho com 20 balas para uso com armas de fogo.',
            'type' => 'ammo',
            'cost' => 20,
            'slots' => 1,
            'icon_file_name' => 'municao_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 4,
            'name' => 'Instrumentos de Coureiro',
            'description' => 'Com essas ferramentas, você pode realizar o seu Ofício (Coureiro) sem penalidades.',
            'type' => 'tools',
            'cost' => 50,
            'slots' => 0.5,
            'icon_file_name' => 'instrumentos_de_coureiro_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 5,
            'name' => 'Bandoleira de Poções',
            'description' => 'Um cinto de couro com bolsos que comportam pequenos frascos. Se você estiver vestindo uma bandoleira, pode sacar itens alquímicos e poções como uma ação livre.',
            'type' => 'tools',
            'cost' => 20,
            'slots' => 1,
            'icon_file_name' => 'bandoleira_pocoes_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 6,
            'name' => 'Estojo de Disfarces',
            'description' => 'Um conjunto de cosméticos, tintas para cabelo e algumas próteses simples (como bigodes e narizes falsos). Um personagem sem este item sofre -5 em testes de Enganação para disfarce.',
            'type' => 'tools',
            'cost' => 50,
            'slots' => 1,
            'icon_file_name' => 'estojo_de_disfarces_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 7,
            'name' => 'Gazua',
            'description' => 'Uma barra fina de ferro, com a ponta torta ou em forma de gancho. Um personagem sem este item sofre -5 em testes de Ladinagem para abrir fechaduras.',
            'type' => 'tools',
            'cost' => 5,
            'slots' => 1,
            'icon_file_name' => 'gazua_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 8,
            'name' => 'Luneta',
            'description' => 'Este instrumento valioso consiste de um cilindro metálico com duas lentes. Fornece +5 em testes de Percepção para observar coisas em alcance longo ou além.',
            'type' => 'tools',
            'cost' => 100,
            'slots' => 1,
            'icon_file_name' => 'luneta_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 348],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 9,
            'name' => 'Cosmético',
            'description' => 'Perfume ou maquiagem. Aplicar um cosmético é uma ação completa e fornece +2 em testes de perícias baseadas em Carisma até o fim da cena.',
            'type' => 'alchemic',
            'cost' => 30,
            'slots' => 0.5,
            'icon_file_name' => 'cosmetico_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 349],
            ],
            'consumable' => true,
        ]);
    }
}
