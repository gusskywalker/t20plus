<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{

    public function run(): void
    {

        Accessory::create([
            'id' => 1,
            'name' => 'Símbolo Sagrado',
            'description' => 'Um medalhão de madeira ou metal com o símbolo de uma divindade. Se você estiver vestindo (normalmente com uma corrente ao redor do pescoço) ou empunhando o símbolo sagrado de um deus do qual é devoto, recebe +1 em testes de resistência.',
            'cost' => 5,
            'slots' => 1,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 317],
            ],
            'mp_cost' => 0,
            'icon_file_name' => 'simbolo_sagrado_01.webp',
        ]);

        Accessory::create([
            'id' => 2,
            'name' => 'Mochila Discreta',
            'description' => 'Como uma mochila normal, mas tem um compartimento oculto onde o usuário pode esconder objetos equivalentes a 1 espaço. Esses itens contam em sua capacidade de carga, mas você recebe +5 em testes de Ladinagem para ocultá-los (cumulativo com qualquer bônus concedido pelo próprio item). Uma mochila discreta deve ser vestida, mas não ocupa espaço de carga do personagem.',
            'cost' => 20,
            'slots' => 0,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14011],
            ],
            'mp_cost' => 0,
            'icon_file_name' => 'mochila_discreta_01.webp',
        ]);

        Accessory::create([
            'id' => 3,
            'name' => 'Bandoleira de Poções',
            'description' => 'Um cinto de couro com bolsos que comportam pequenos frascos. Se você estiver vestindo uma bandoleira, pode sacar itens alquímicos e poções como uma ação livre.',
            'cost' => 20,
            'slots' => 1,
            'effects' => null,
            'mp_cost' => 0,
            'icon_file_name' => 'bandoleira_pocoes_01.webp',
        ]);

        Accessory::create([
            'id' => 4,
            'name' => 'Cinto de Utilidades',
            'description' => 'Um cinturão de couro cheio de bolsos e fivelas, com espaço para os vários equipamentos esquisitos de um inventor. Se você estiver vestindo um cinto de utilidades, pode sacar e guardar engenhocas como uma ação livre.',
            'cost' => 50,
            'slots' => 1,
            'effects' => null,
            'mp_cost' => 0,
            'icon_file_name' => 'cinto_de_utilidades_01.webp',
        ]);

        Accessory::create([
            'id' => 5,
            'name' => 'Organizador de Pergaminhos',
            'description' => 'Um estojo de madeira ou couro rígido. Se você estiver vestindo um organizador de pergaminhos, pode sacar pergaminhos como uma ação livre.',
            'cost' => 25,
            'slots' => 1,
            'effects' => null,
            'mp_cost' => 0,
            'icon_file_name' => 'organizador_de_pergaminhos_01.webp',
        ]);

        Accessory::create([
            'id' => 6,
            'name' => 'Dente de Wisphago',
            'description' => 'Um dente de wisphago pode ser vestido como um amuleto. Quando faz um teste de resistência contra uma magia arcana, você pode gastar o amuleto para rolar novamente esse teste. Uma vez ativado, o amuleto se desfaz. Um dente de wisphago não pode ser fabricado.',
            'cost' => 100,
            'slots' => 1,
            'effects' => null,
            'mp_cost' => 0,
            'icon_file_name' => 'dente_de_wisphago_01.webp',
        ]);

        Accessory::create([
            'id' => 7,
            'name' => 'Patuá',
            'description' => 'Vários deuses artonianos ensinam seus devotos a fazer pequenos sacos contendo ervas, substâncias sagradas, pedaços de pergaminhos com orações e outros objetos minúsculos. Esses saquinhos são vestidos como amuletos, pendurados no pescoço, e protegem o devoto de todos os males. Se tiver uma devoção, você recebe redução de dano 2/mundano e resistência a magia +1.',
            'cost' => 50,
            'slots' => 1,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14012],
            ],
            'mp_cost' => 0,
            'icon_file_name' => 'patua_01.webp',
        ]);

        Accessory::create([
            'id' => 8,
            'name' => 'Colar do Suplicante',
            'description' => 'Este colar simples possui 10 contas de um material ligado à divindade em questão, como jade para Lin-Wu ou cristal para Wynna. Se você estiver vestindo um colar do suplicante de um deus do qual é devoto, uma vez por dia você pode gastar uma ação de movimento e uma das contas para recuperar 1 PM. Uma vez que as contas sejam gastas, o colar perde seu efeito.',
            'cost' => 100,
            'slots' => 1,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14013],
            ],
            'mp_cost' => 0,
            'icon_file_name' => 'colar_do_suplicante_01.webp',
        ]);
    }
}
