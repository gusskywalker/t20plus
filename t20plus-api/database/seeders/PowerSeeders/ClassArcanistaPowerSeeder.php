<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ClassArcanistaPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 328,
            'name' => 'Bruxo',
            'description' => 'Você lança magias através de um foco — uma varinha, cajado, chapéu... Para lançar uma magia, você precisa empunhar o foco com uma mão (e gesticular com a outra) ou fazer um teste de Misticismo (CD 20 + o custo em PM da magia; se falhar, a magia não funciona, mas você gasta os PM mesmo assim). O foco tem RD 10 e PV iguais à metade dos seus, independentemente de seu material ou forma. Se for danificado, é totalmente restaurado na próxima vez que você recuperar seus PM. Se for destruído (reduzido a 0 PV), você fica atordoado por uma rodada. Você pode recuperar um foco destruído ou perdido com uma semana de trabalho e T$ 100. Seu atributo-chave para magias é Inteligência.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'bruxo_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'int'],
                ['tag' => 'starting_spell_count', 'op' => 'set', 'value' => 3],
                ['tag' => 'spell_count_growth', 'op' => 'add_after_first', 'value' => 1, 'per_levels' => 1],
            ],
        ]);

        Power::create([
            'id' => 329,
            'name' => 'Feiticeiro',
            'description' => 'Você lança magias através de um poder inato que corre em seu sangue. Escolha uma linhagem como origem de seus poderes. Você recebe a herança básica da linhagem escolhida. Você não depende de nenhum item ou estudo, mas sua capacidade de aprender magias é limitada — você aprende uma magia nova a cada nível ímpar (3º, 5º, 7º etc.), em vez de a cada nível. Seu atributo-chave para magias é Carisma.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'feiticeiro_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['tag' => 'starting_spell_count', 'op' => 'set', 'value' => 3],
                ['tag' => 'spell_count_growth', 'op' => 'add_after_first', 'value' => 1, 'per_levels' => 2],
            ],
        ]);

        Power::create([
            'id' => 330,
            'name' => 'Mago',
            'description' => 'Você lança magias através de estudo e memorização de fórmulas arcanas. Você só pode lançar magias memorizadas; suas outras magias não podem ser lançadas, mesmo que você tenha pontos de mana para tal. Para memorizar magias, você precisa estudar seu grimório por uma hora. Quando faz isso, escolhe metade das magias que conhece (por exemplo, se conhece 7 magias, escolhe 3). Essas serão suas magias memorizadas. Você pode memorizar magias uma vez por dia. Caso não possa estudar (por não ter tempo, por ter perdido o grimório...), não poderá trocar suas magias memorizadas. Um grimório tem as mesmas estatísticas de um foco (RD 10, PV iguais à metade dos seus) e pode ser recuperado da mesma forma. Você começa com uma magia adicional (para um total de 4) e, sempre que ganha acesso a um novo círculo de magias, aprende uma magia adicional daquele círculo. Seu atributo-chave para magias é Inteligência.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'mago_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'spell_key_attribute', 'op' => 'set', 'value' => 'int'],
                ['tag' => 'starting_spell_count', 'op' => 'set', 'value' => 4],
                ['tag' => 'spell_count_growth', 'op' => 'add_after_first', 'value' => 1, 'per_levels' => 1],
            ],
        ]);
    }
}
