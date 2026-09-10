<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{

    public function run(): void
    {

        Condition::create([
            'id' => 1,
            'name' => 'Sangrando',
            'description' => 'Você está sangrando. Efeito de metabolismo. No início de seu turno, o personagem deve fazer um teste de Constituição (CD 15). Se falhar, perde 1d6 pontos de vida e continua sangrando. Se passar, remove essa condição.',
            'type' => 'metabolism',
        ]);

        Condition::create([
            'id' => 2,
            'name' => 'Atordoado',
            'description' => 'Você está atordoado. Efeito mental. O personagem fica desprevenido (sofre –5 na Defesa e em Reflexos, contra inimigos que não possa perceber) e não pode fazer ações.',
            'type' => 'mental',
        ]);

        Condition::create([
            'id' => 3,
            'name' => 'Desprevenido',
            'description' => 'Você está desprevenido. O personagem sofre –5 na Defesa e em Reflexos. Um personagem fica desprevenido contra inimigos que não possa perceber.',
            'type' => null,
        ]);

        Condition::create([
            'id' => 4,
            'name' => 'Ofuscado',
            'description' => 'Você está ofuscado. Efeito nos sentidos. O personagem sofre –2 em testes de ataque e de Percepção.',
            'type' => 'senses',
        ]);

        Condition::create([
            'id' => 5,
            'name' => 'Vulnerável',
            'description' => 'Você está vulnerável. O personagem sofre –2 na Defesa.',
            'type' => null,
        ]);

        Condition::create([
            'id' => 6,
            'name' => 'Inconsciente',
            'description' => 'Você está inconsciente. O personagem fica indefeso (–10 na Defesa, falha automaticamente em testes de Reflexo, pode sofrer golpes de misericórdia). O personagem não pode fazer ações, incluindo reações, mas ainda pode fazer testes que sejam naturalmente feitos quando se está inconsciente, como testes de Constituição para estabilizar sangramento. Balançar uma criatura para acordá-la gasta uma ação padrão.',
            'type' => null,
        ]);

        Condition::create([
            'id' => 7,
            'name' => 'Indefeso',
            'description' => 'Você está indefeso. O personagem fica desprevenido (–5 na Defesa e em testes de Reflexo), mas sofre –10 na Defesa. O personagem falha automaticamente em testes de Reflexo. O personagem pode sofrer golpes de misericórdia.',
            'type' => null,
        ]);

        Condition::create([
            'id' => 8,
            'name' => 'Caído',
            'description' => 'Você está caído. O personagem sofre –5 em ataques corpo a corpo. O deslocamento do personagem é reduzido a 1,5m. O personagem sofre –5 na Defesa contra ataques corpo a corpo (cumulativo com outras condições). O personagem sofre +5 na Defesa contra ataques à distância (cumulativo com outras condições). <br><br>No APP, não será adicionada defesa automaticamente.',
            'type' => 'movement',
        ]);

        Condition::create([
            'id' => 9,
            'name' => 'Debilitado',
            'description' => 'Você está debilitado. O personagem sofre –5 em testes de Força, Destreza e Constituição e de perícias baseadas nesses atributos. Se o personagem ficar debilitado novamente, em vez disso fica inconsciente. <br><br>No APP, as perícias serão automaticamente reduzidas de acordo, para rolagens de teste de atributo, reduza 5 manualmente.',
            'type' => null,
        ]);

        Condition::create([
            'id' => 10,
            'name' => 'Lento',
            'description' => 'Você está lento. Efeito de movimento. Todas as formas de deslocamento do personagem são reduzidas à metade (arredonde para baixo para o primeiro incremento de 1,5m). O personagem não pode correr ou fazer investidas.',
            'type' => 'movement',
        ]);

        Condition::create([
            'id' => 11,
            'name' => 'Exausto',
            'description' => 'Você está exausto. Efeito de cansaço. O personagem fica debilitado (–5 em testes de Força, Destreza e Constituição e em perícias baseadas nesses atributos), lento (deslocamento reduzido à metade) e vulnerável (–2 na Defesa). Se ficar exausto novamente, em vez disso, o personagem fica inconsciente. <br><br>No APP, adicione Inconsciente e remova Exausto manualmente se for necessário.',
            'type' => 'tired',
        ]);

        Condition::create([
            'id' => 12,
            'name' => 'Fraco',
            'description' => 'Você está fraco. O personagem sofre –2 em testes de Força, Destreza e Constituição e de perícias baseadas nesses atributos. Se ficar fraco novamente, em vez disso fica debilitado. <br><br>No APP, adicione Debilitado e remova Fraco manualmente se for necessário.',
            'type' => null,
        ]);

        Condition::create([
            'id' => 13,
            'name' => 'Fatigado',
            'description' => 'Você está fatigado. Efeito de cansaço. O personagem fica fraco (–2 em testes de Força, Destreza e Constituição e em perícias baseadas nesses atributos) e vulnerável (–2 na Defesa). Se ficar fatigado novamente, em vez disso fica exausto. <br><br>No APP, adicione Exausto e remova Fatigado manualmente se for necessário.',
            'type' => 'tired',
        ]);

        Condition::create([
            'id' => 14,
            'name' => 'Confuso',
            'description' => 'Você está confuso. Efeito mental. O personagem comporta-se de modo aleatório. Role 1d6 no início de seus turnos: 1: Movimenta-se em uma direção escolhida por uma rolagem de 1d8; 2-3: Não pode fazer ações, e fica balbuciando incoerentemente; 4-5: Usa a arma que estiver empunhando para atacar a criatura mais próxima, ou a si mesmo se estiver sozinho (nesse caso, apenas role o dano); 6: A condição termina e pode agir normalmente.',
            'type' => 'mental',
        ]);
    }
}
