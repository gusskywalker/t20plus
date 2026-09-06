<?php

namespace Database\Seeders;

use App\Models\ItemImprovement;
use Illuminate\Database\Seeder;

class ItemImprovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.
        ItemImprovement::create([
            'id' => 1,
            'name' => 'Farpada',
            'description' => 'Acertos críticos provocam sangramento.',
            'is_material' => false,
            'categories' => ['weapon'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 13], // Farpada (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 2,
            'name' => 'Matéria Vermelha',
            'description' => 'Qualquer material de origem lefeu — desde suas garras e carapaças, até minérios e partes de estruturas encontradas em áreas de Tormenta — apresenta propriedades parecidas, sendo conhecido como "matéria vermelha". Estes itens assustadores impõem ao usuário penalidade de –2 em perícias baseadas em Carisma (exceto Intimidação). Arma: causa +1d6 de dano extra, mas o usuário perde 1 ponto de vida ao acertar (Lefou e lefeu são imunes tanto ao dano extra quanto à perda de vida). Armadura e Escudo: chance de falha de 10% (escudos e armaduras leves) ou 25% (armaduras pesadas), cumulativas entre si (Lefeu ignoram este efeito). Esotérico: você e inimigos em alcance curto sofrem -2 em testes de resistência contra efeitos mágicos. Instrumentos Musicais: +1 CD das habilidades de bardo, exceto magias.',
            'is_material' => true,
            'categories' => ['weapon', 'armor', 'shield', 'esoteric', 'tool'],
            'effects' => [
                ['tag' => 'skill_group', 'op' => 'add', 'attribute' => 'car', 'value' => -2, 'exclude_skill_id' => 14], // Intimidação excluded
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14, 'when_category' => 'weapon'], // Arma - Matéria Vermelha
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17, 'when_category' => 'esoteric'], // Esotérico (Portador)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 19, 'when_category' => 'esoteric'], // Esotérico (Inimigos Próximos)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 18, 'when_category' => 'tool'], // Instrumento Musical (no tool catalog table yet)
            ],
        ]);

        ItemImprovement::create([
            'id' => 3,
            'name' => 'Certeira',
            'description' => 'Fabricada para ser mais precisa e balanceada, a arma fornece +1 nos testes de ataque.',
            'is_material' => false,
            'categories' => ['weapon'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 140], // Certeira (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 4,
            'name' => 'Pungente',
            'description' => 'Temperada diversas vezes para adquirir o fio ou o equilíbrio perfeito, a arma fornece +2 nos testes de ataque.',
            'is_material' => false,
            'categories' => ['weapon'],
            'prerequisites' => [3], // Certeira
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 141], // Pungente (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 5,
            'name' => 'Conduíte',
            'description' => 'A empunhadura da arma é ornamentada com uma medalha ou outro objeto minúsculo de significado religioso que a torna mais propícia para receber bênçãos. O custo para usar o poder Abençoar Arma nela é reduzido em –1 PM.',
            'is_material' => false,
            'categories' => ['weapon'],
            // TODO: no effects — needs Clérigo's Abençoar Arma power (which
            // doesn't exist yet) to reduce the PM cost of.
        ]);

        ItemImprovement::create([
            'id' => 6,
            'name' => 'Cruel',
            'description' => 'Rebarbas, espinhos e até mesmo lâminas adicionais compõem uma arma cruel. Ela fornece +1 nas rolagens de dano.',
            'is_material' => false,
            'categories' => ['weapon'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 142], // Cruel (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 7,
            'name' => 'Atroz',
            'description' => 'A arma é um amontoado de pontas, ganchos e protuberâncias. É difícil empunhá-la sem se machucar, mas ela fornece +2 nas rolagens de dano.',
            'is_material' => false,
            'categories' => ['weapon'],
            'prerequisites' => [6], // Cruel
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 143], // Atroz (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 8,
            'name' => 'Penetrante (Arma)',
            'description' => 'A arma ignora 5 pontos da redução de dano.',
            'is_material' => false,
            'categories' => ['weapon'],
            'prerequisites' => [6], // Cruel
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 144], // Penetrante (Arma) (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 9,
            'name' => 'Equilibrada',
            'description' => 'Uma arma equilibrada é forjada com o balanço perfeito, o que facilita movimentos complexos. Ela fornece +2 em testes de manobras (desarmar, quebrar etc.).',
            'is_material' => false,
            'categories' => ['weapon'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 145], // Equilibrada (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 10,
            'name' => 'Estampada',
            'description' => 'Essa melhoria só pode ser aplicada a armas de duas mãos e escudos, devido à superfície necessária. Quando aplicada a uma arma estampada, ela concede um bônus de +2 em testes de Enganação para fintar. Em Moreania, uma arma estampada concede +2 em testes baseados em Carisma com outros aventureiros.',
            'is_material' => false,
            'categories' => ['weapon', 'shield'],
            'restrictions' => ['grip' => 'two_hand'], // weapon only — shields have no grip
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 146, 'when_category' => 'weapon'], // Estampada (Armas)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 147, 'when_category' => 'shield'], // Estampada (Escudos)
            ],
        ]);

        ItemImprovement::create([
            'id' => 11,
            'name' => 'Guarda',
            'description' => 'A arma possui uma proteção elaborada próxima a sua empunhadura, que fornece +1 na Defesa e em testes contra manobras. Só pode ser aplicada em armas corpo a corpo.',
            'is_material' => false,
            'categories' => ['weapon'],
            'restrictions' => ['purpose' => 'melee'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 148], // Guarda (item_granted)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 149], // Guarda (Manobras) (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 12,
            'name' => 'Harmonizada (Arma)',
            'description' => 'A arma foi banhada em óleos alquímicos que a deixaram sintonizada com a aura de seu usuário. Escolha uma habilidade ativada ao se fazer um ataque ou usar a ação agredir e que custe pontos de mana. Esta habilidade tem seu custo em PM reduzido em –1 se utilizada com esta arma.',
            'is_material' => false,
            'categories' => ['weapon'],
            // "Pré-requisito: outra melhoria qualquer" — requires ANY other
            // improvement already on the item, not a specific one, so it
            // doesn't fit the plain-id-list prerequisites shape. The only
            // improvement worded this way so far — self-reported rather
            // than adding a whole "any" mechanism for one occurrence.
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 150], // Harmonizada (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 13,
            'name' => 'Injeção Alquímica',
            'description' => 'Um minúsculo frasco de cerâmica ou vidro é inserido ao longo da arma, junto com um mecanismo injetor ativado por impacto. Um ataque que acerte causa seu dano normal e libera uma carga de ácido, fogo alquímico ou água benta, que atinge o alvo automaticamente. A modificação tem espaço para 2 cargas. Recarregá-la exige uma ação completa e o gasto dos itens alquímicos que você quiser inserir. Carregar uma arma com 2 cargas de ácido, por exemplo, custa T$ 20.',
            'is_material' => false,
            'categories' => ['general_item'],
            'restrictions' => ['type' => 'ammunition'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 151], // Injeção Alquímica (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 14,
            'name' => 'Maciça',
            'description' => 'A arma é feita com material denso, fazendo com que seus golpes tenham impacto terrível. O multiplicador de crítico da arma aumenta em 1 ponto. Uma arma não pode ser maciça e precisa.',
            'is_material' => false,
            'categories' => ['weapon'],
            'incompatible_ids' => [15], // Precisa
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 152], // Maciça (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 15,
            'name' => 'Precisa',
            'description' => 'Cuidado especial foi tomado ao temperar o aço desta arma, para que seu fio se mantenha sempre como uma navalha. A margem de ameaça aumenta em 1 ponto. Uma arma não pode ser precisa e maciça.',
            'is_material' => false,
            'categories' => ['weapon'],
            'incompatible_ids' => [14], // Maciça
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 153], // Precisa (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 16,
            'name' => 'Mira Telescópica',
            'description' => 'Aumenta o alcance da arma em uma categoria (de curto para médio, de médio para longo) e o alcance da habilidade Ataque Furtivo para médio. Esta melhoria só pode ser aplicada em armas de disparo (exceto fundas).',
            'is_material' => false,
            'categories' => ['weapon'],
            'restrictions' => ['purpose' => 'fired'], // fundas exception not modeled
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 154], // Mira Telescópica (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 17,
            'name' => 'Pressurizada (Corpo a Corpo)',
            'description' => 'A arma possui um mecanismo articulado em seu interior, formado por um pistão e uma câmara de ar. Você pode gastar uma ação completa para puxar o pistão e pressurizar a câmara. Quando faz um ataque com a arma, se ela estiver pressurizada, você pode descarregar a pressão para aumentar o impacto do golpe. Se fizer isso, você recebe +2 no teste de ataque e na rolagem de dano. Esta melhoria só pode ser aplicada a armas corpo a corpo de impacto e armas de fogo.',
            'is_material' => false,
            'categories' => ['weapon'],
            'restrictions' => ['purpose' => 'melee', 'damage_type' => 'bludgeoning'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 155], // Pressurizada (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 18,
            'name' => 'Pressurizada (Arma de Fogo)',
            'description' => 'A arma possui um mecanismo articulado em seu interior, formado por um pistão e uma câmara de ar. Você pode gastar uma ação completa para puxar o pistão e pressurizar a câmara. Quando faz um ataque com a arma, se ela estiver pressurizada, você pode descarregar a pressão para aumentar o impacto do golpe. Se fizer isso, você recebe +2 no teste de ataque e na rolagem de dano. Esta melhoria só pode ser aplicada a armas corpo a corpo de impacto e armas de fogo.',
            'is_material' => false,
            'categories' => ['weapon'],
            'restrictions' => ['purpose' => 'fired', 'is_firearm' => true],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 155], // Pressurizada (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 19,
            'name' => 'Aço-Rubi',
            'description' => 'Este metal tem a aparência de vidro avermelhado, mas é duro como aço. Aço-rubi é caríssimo e comercializado apenas por uma guilda de ferreiros de Doherimm. Os anões mantém a origem deste material em segredo, mas suspeita-se de que ele seja minerado das profundezas de uma área de Tormenta.',
            'is_material' => true,
            'categories' => ['weapon', 'armor', 'shield', 'esoteric'],
            'extra_cost' => [
                'weapons' => 6000,
                'light_armors' => 3000,
                'heavy_armors' => 6000,
                'shields' => 3000,
                'exoterics' => 6000,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 156, 'when_category' => 'weapon'], // Arma - Aço-Rubi
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 157, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 157, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 158, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 159, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 20,
            'name' => 'Adamante',
            'description' => 'Encontrado apenas em meteoritos (e por isso também chamado de "ferro-meteórico"), o adamante é um metal escuro, fosco e mais denso que o aço.',
            'is_material' => true,
            'categories' => ['weapon', 'armor', 'shield', 'esoteric'],
            'extra_cost' => [
                'weapons' => 3000,
                'light_armors' => 6000,
                'heavy_armors' => 18000,
                'shields' => 6000,
                'exoterics' => 3000,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 160, 'when_category' => 'weapon'], // Arma - Adamante
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 161, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 161, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 162, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 163, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 21,
            'name' => 'Casco Monstruoso',
            'description' => 'Os cascos protetores de certos monstros podem substituir aço e outros materiais rígidos, embora sejam mais frágeis; um item de casco não mágico sofre 1 ponto de dano adicional por dado de dano de impacto. Por sua origem natural, podem ser usados por devotos de Allihanna sem contrariar suas obrigações e restrições.',
            'is_material' => true,
            'categories' => ['weapon', 'armor', 'shield'],
            'extra_cost' => [
                'weapons' => 750,
                'light_armors' => 750,
                'heavy_armors' => 6000,
                'shields' => 750,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 164, 'when_category' => 'weapon'], // Arma - Casco Monstruoso
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 165, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 165, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 166, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
            ],
        ]);

        ItemImprovement::create([
            'id' => 22,
            'name' => 'Couraça de Kaiju',
            'description' => 'Ainda mais raro que couro dracônico, este material pode ser empregado para fabricar itens superiores de grande poder. Jamais encontrado à venda, sendo obtido apenas a partir de um kaiju abatido.',
            'is_material' => true,
            'categories' => ['weapon', 'armor', 'shield', 'esoteric'],
            'extra_cost' => [
                'weapons' => 0,
                'light_armors' => 0,
                'heavy_armors' => 0,
                'shields' => 0,
                'exoterics' => 0,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 167, 'when_category' => 'weapon'], // Arma - Couraça de Kaiju
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 168, 'when_category' => 'weapon'], // Arma - Couraça de Kaiju (Ignorar Redução)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 169, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 169, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 170, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 171, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 23,
            'name' => 'Couro de Dragão',
            'description' => 'Cobiçado por artesãos em toda Arton, o couro escamado de um dragão verdadeiro é um material especial que pode ser empregado para fabricar certos itens superiores. Tanto o couro bruto quanto itens de couro de dragão não existem à venda; a única maneira de obter esse material é derrotar um dragão.',
            'is_material' => true,
            'categories' => ['armor', 'shield', 'esoteric'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 172, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 172, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 188, 'when_category' => 'shield'], // Armadura/Escudo Leve (Resistência a Magia)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 188, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve (Resistência a Magia)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 173, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 189, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada (Resistência a Magia)
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 174, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 24,
            'name' => 'Cristal de Sol (Corte)',
            'description' => 'Utilizados pelos ursos das neves para armazenar calor, estes cristais perdem suas propriedades quando não utilizados em algumas semanas, tornando difícil encontrá-los à venda. Exige uma peça. Só pode ser aplicado a armas de corte ou perfuração.',
            'is_material' => true,
            'categories' => ['weapon'],
            'restrictions' => ['damage_type' => 'slashing'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 175], // Arma - Cristal de Sol (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 25,
            'name' => 'Cristal de Sol (Perfuração)',
            'description' => 'Utilizados pelos ursos das neves para armazenar calor, estes cristais perdem suas propriedades quando não utilizados em algumas semanas, tornando difícil encontrá-los à venda. Exige uma peça. Só pode ser aplicado a armas de corte ou perfuração.',
            'is_material' => true,
            'categories' => ['weapon'],
            'restrictions' => ['damage_type' => 'piercing'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 175], // Arma - Cristal de Sol (item_granted)
            ],
        ]);

        ItemImprovement::create([
            'id' => 26,
            'name' => 'Cristal de Sol',
            'description' => 'Utilizados pelos ursos das neves para armazenar calor, estes cristais perdem suas propriedades quando não utilizados em algumas semanas, tornando difícil encontrá-los à venda.',
            'is_material' => true,
            'categories' => ['armor', 'esoteric'],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 176, 'when_category' => 'armor'], // Armadura - Cristal de Sol
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 177, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 27,
            'name' => 'Gelo Eterno',
            'description' => 'As gélidas Montanhas Uivantes produzem gelo que nunca derrete. Expedições de aventureiros exploram essa região glacial perigosa à caça deste material fantástico.',
            'is_material' => true,
            'categories' => ['weapon', 'armor', 'shield', 'esoteric'],
            'extra_cost' => [
                'weapons' => 600,
                'light_armors' => 1500,
                'heavy_armors' => 3000,
                'shields' => 1500,
                'exoterics' => 3000,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 178, 'when_category' => 'weapon'], // Arma - Gelo Eterno
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 179, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 179, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 180, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 181, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 28,
            'name' => 'Lanajuste',
            'description' => 'Também chamado de coral-de-ferro, é encontrado em paredões afiados banhados pelo mar em Khubar. Mais conhecido por seu uso em lâminas afiadas, o lanajuste também é empregado em outros itens.',
            'is_material' => true,
            'categories' => ['weapon', 'armor', 'shield', 'esoteric'],
            'extra_cost' => [
                'weapons' => 600,
                'light_armors' => 1500,
                'heavy_armors' => 3000,
                'shields' => 1500,
                'exoterics' => 3000,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 182, 'when_category' => 'weapon'], // Arma - Lanajuste
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 183, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 183, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 184, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 185, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 29,
            'name' => 'Madeira Tollon',
            'description' => 'A Floresta de Tollon produz um tipo de madeira negra, dura como aço e dotada de propriedades mágicas. Apenas armas de madeira — arcos, bordões, clavas, lanças, piques e tacapes —, escudos leves e esotéricos podem ser feitos com madeira Tollon.',
            'is_material' => true,
            'categories' => ['weapon', 'shield', 'esoteric'],
            // Which specific weapons count as "armas de madeira" isn't
            // checkable — no per-weapon material/composition data exists.
            // Self-reported; restrictions only narrows shield to light.
            'restrictions' => ['type' => 'light'],
            'extra_cost' => [
                'weapons' => 1500,
                'shields' => 1500,
                'exoterics' => 1500,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 186, 'when_category' => 'weapon'], // Arma - Madeira Tollon
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 187, 'when_category' => 'shield'], // Escudo/Esotérico
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 187, 'when_category' => 'esoteric'], // Escudo/Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 30,
            'name' => 'Mitral',
            'description' => 'Metal raro e valioso, o mitral é prateado, brilhante e mais leve que aço. Itens de mitral ocupam –1 espaço (mínimo 1).',
            'is_material' => true,
            'categories' => ['weapon', 'armor', 'shield', 'esoteric'],
            // "-1 espaço (mínimo 1)" isn't checkable — no tag modifies a
            // specific item's own slots. Self-reported.
            'extra_cost' => [
                'weapons' => 1500,
                'light_armors' => 1500,
                'heavy_armors' => 12000,
                'shields' => 1500,
                'exoterics' => 3000,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 190, 'when_category' => 'weapon'], // Arma - Mitral
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 191, 'when_category' => 'shield'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 191, 'when_category' => 'armor', 'when_type' => 'light'], // Armadura/Escudo Leve
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 192, 'when_category' => 'armor', 'when_type' => 'heavy'], // Armadura Pesada
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 193, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);

        ItemImprovement::create([
            'id' => 31,
            'name' => 'Prata',
            'description' => 'Embora seja muito maleável, a prata pode ser usada para revestir objetos e conceder propriedades quase mágicas. Por ser usada como revestimento, prata pode ser combinada com um segundo material especial (cada um contando como uma melhoria separada).',
            // Coded as a non-material — its own text exempts it from the
            // "only one is_material improvement" rule, and is_material's
            // only purpose here is that exclusivity, so simplest is to just
            // not flag it as one at all.
            'is_material' => false,
            'categories' => ['weapon', 'esoteric'],
            'extra_cost' => [
                'weapons' => 600,
                'exoterics' => 400,
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 194, 'when_category' => 'weapon'], // Arma - Prata
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 195, 'when_category' => 'esoteric'], // Esotérico
            ],
        ]);
    }
}
