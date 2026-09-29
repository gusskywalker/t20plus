<?php

namespace Database\Seeders;

use App\Models\Shield;
use Illuminate\Database\Seeder;

class ShieldSeeder extends Seeder
{

    public function run(): void
    {

        Shield::create([
            'id' => 1,
            'name' => 'Escudo Leve',
            'description' => 'Tipicamente feito de madeira, este escudo é amarrado no antebraço, deixando a mão livre. Você pode carregar um objeto na mão que empunha o escudo, mas não manusear uma arma.',
            'type' => 'light',
            'mod_def' => 1,
            'armor_penalty' => 1,
            'cost' => 5,
            'slots' => 1,
            'icon_file_name' => 'escudo_leve_01.webp',
        ]);

        Shield::create([
            'id' => 2,
            'name' => 'Broquel',
            'description' => 'Este escudo parece pequeno demais para ter qualquer uso, protegendo apenas a mão e o pulso (o que impede o uso dessa mão). Contudo, ele não é como outros escudos: em vez de atuar como uma barreira fixa entre o usuário e seus inimigos, ajuda a bloquear os ataques contra ele. Você não recebe o bônus na Defesa por um broquel, como normal. Em vez disso, uma vez por rodada, quando é atingido por um ataque, você pode fazer um teste de ataque corpo a corpo. Para cada 10 pontos do resultado desse teste, você reduz o dano sofrido em 2 pontos. Um broquel pode ser usado para atacar como um escudo leve.',
            'type' => 'light',
            'mod_def' => 0,
            'armor_penalty' => 1,
            'cost' => 25,
            'slots' => 0.5,
            'icon_file_name' => 'broquel_01.webp',
        ]);

        Shield::create([
            'id' => 3,
            'name' => 'Escudo de Couro',
            'description' => 'Por sua extrema leveza, este grande escudo é popular entre os velocis e outros povos da Grande Savana. Feito com uma borda de madeira esticando uma membrana de couro (ou outro material flexível). Contra ataques à distância, o bônus na Defesa do escudo leve aumenta em +2. Entretanto, ele é muito leve para ser usado como arma.<br><br>No APP, adicione +2 em defesa manualmente caso esteja sendo alvo de um ataque a distância.',
            'type' => 'light',
            'mod_def' => 1,
            'armor_penalty' => 1,
            'cost' => 3,
            'slots' => 1,
            'icon_file_name' => 'escudo_de_couro_01.webp',
        ]);

        Shield::create([
            'id' => 4,
            'name' => 'Escudo Pesado',
            'description' => 'Normalmente feito de aço, este escudo é preso ao antebraço e também deve ser empunhado com firmeza, impedindo o usuário de usar aquela mão.',
            'type' => 'heavy',
            'mod_def' => 2,
            'armor_penalty' => 2,
            'cost' => 15,
            'slots' => 2,
            'icon_file_name' => 'escudo_pesado_01.webp',
        ]);

        Shield::create([
            'id' => 5,
            'name' => 'Escudo de Vime',
            'description' => 'Este escudo é grande e desajeitado, mas extremamente leve devido a seu material. Parece não proteger muito, pois existem pequenas “falhas” entre as fibras de vime. À primeira vista, apenas um louco usaria este tipo de proteção — mas, justamente por ser leve, volumoso e com uma trama aberta, pode ser movido com facilidade para confundir os inimigos. Não é preso ao braço, mas precisa ser empunhado com as duas mãos, impedindo que você empunhe uma arma ou qualquer outro item (um parceiro capaz de empunhar escudos pode segurar o escudo para você, mas não poderá fornecer seus benefícios enquanto faz isso) e é muito desajeitado para ser empunhado montado ou usado para atacar. Além de seus bônus na Defesa, o escudo de vime fornece camuflagem leve. Além disso, em seu turno, se estiver usando o escudo, você pode escolher um aliado adjacente. Se fizer isso, fornece seu bônus na Defesa e camuflagem a ele. Contudo, caso você ou o aliado sofram um acerto crítico, o escudo é destruído.',
            'type' => 'heavy',
            'mod_def' => 2,
            'armor_penalty' => 2,
            'cost' => 15,
            'slots' => 2,
            'icon_file_name' => 'escudo_de_vime_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 7006],
            ],
        ]);

        Shield::create([
            'id' => 6,
            'name' => 'Escudo Torre',
            'description' => 'Este escudo grande e retangular é feito de madeira e reforçado com aço e couro. É preso ao antebraço como um escudo pesado mas, devido a seu tamanho, não pode ser usado para atacar, ou por um personagem montado. Você pode gastar uma ação de movimento para tirar o escudo e fixá-lo chão, transformando-o em uma barreira que pode fornecer cobertura leve (conforme o normal para cobertura). Recolocar o escudo, desafixando-o do chão, também é uma ação de movimento.',
            'type' => 'heavy',
            'mod_def' => 2,
            'armor_penalty' => 4,
            'cost' => 45,
            'slots' => 2,
            'icon_file_name' => 'escudo_torre_01.webp',
        ]);

        Shield::create([
            'id' => 7,
            'name' => 'Sagna',
            'description' => 'Desenvolvido por devotos de Oceano e muito comum em regiões praianas, este grande escudo de madeira também pode ser usado para deslizar pelas ondas. Enquanto você o usa dessa forma, a penalidade de armadura deste escudo e de sua armadura para natação não são aplicadas. Além disso, quando passa em um teste para natação, você avança seu deslocamento normal (em vez de metade); se você já tiver deslocamento de natação, em vez disso ele aumenta em +3m. Um sagna pode ser usado para atacar como um escudo pesado.',
            'type' => 'heavy',
            'mod_def' => 2,
            'armor_penalty' => 3,
            'cost' => 20,
            'slots' => 2,
            'icon_file_name' => 'sagna_01.webp',
        ]);
    }
}
