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
            'effects' => null,
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

        GeneralItem::create([
            'id' => 10,
            'name' => 'Ábaco',
            'description' => 'Este instrumento desenvolvido pelos sábios sar-allan fornece a seu usuário a capacidade de fazer operações matemáticas complexas em pouco tempo. Muito útil para determinar custos e margens de lucro! Para usar um ábaco você precisa ser treinado em Conhecimento. Se você fizer um teste de Ofício para sustento, recebe TO, em vez de T$. Além disso, se possuir Poder Monetário, o limite de TO que você pode gastar por dia aumenta em +2.',
            'type' => 'tools',
            'cost' => 45,
            'slots' => 1,
            'icon_file_name' => 'abaco_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 11,
            'name' => 'Alforje',
            'description' => 'Sacos de couro feitos para serem presos em uma sela. Permitem que um parceiro montaria carregue até 10 espaços de item para você.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 0,
            'icon_file_name' => 'alforje_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 12,
            'name' => 'Algemas',
            'description' => 'Um par de algemas para criaturas Médias. Prender uma criatura que não esteja indefesa exige empunhar a algema, agarrar o alvo (veja Manobras de Combate) e vencer um novo teste de agarrar contra ela. Você pode prender os dois pulsos da pessoa (–5 em testes que exijam o uso das mãos, impede conjuração) ou um dos pulsos dela em um objeto imóvel adjacente, caso haja, para impedir que ela se mova. Escapar das algemas exige uma ação completa e um teste de Acrobacia contra CD 30 ou de Força contra CD 25 — ou ter as chaves...',
            'type' => 'tools',
            'cost' => 15,
            'slots' => 1,
            'icon_file_name' => 'algemas_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 13,
            'name' => 'Ampulheta',
            'description' => 'Um objeto delicado, usado para medir o tempo. Um ciclo da ampulheta normalmente equivale a uma hora. Usada por nobres para manter controle de seus compromissos e por alquimistas para medir o tempo de certas reações. Quando escolhe 10 em um teste de Ofício (alquimista), você considera o resultado do d20 como um 12 automático.',
            'type' => 'tools',
            'cost' => 45,
            'slots' => 1,
            'icon_file_name' => 'ampulheta_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 14,
            'name' => 'Apanhador de Sonhos',
            'description' => 'Muitos artonianos acreditam que ficam mais vulneráveis enquanto estão dormindo — não apenas a ataques físicos, mas também a espíritos inquietos, tentações demoníacas e, é claro, pesadelos. Este item é uma pequena estrutura de madeira, cordões, contas e penas feita por um sacerdote para proteger o descanso de seus fiéis. Se estiver em posso de um apanhador de sonhos, recebe +2 em testes de resistência quando estiver dormindo.',
            'type' => 'tools',
            'cost' => 40,
            'slots' => 1,
            'icon_file_name' => 'apanhador_de_sonhos_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 15,
            'name' => 'Aparelho de Chá',
            'description' => 'Um conjunto de xícaras delicadas, pires e um bule. Qualquer um que se depare com um aparelho de chá fica automaticamente mais predisposto a conversar. Uma vez por cena, você recebe +1d4 em um teste de Diplomacia ou Enganação feito com alguém que esteja tomando chá com você.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 1,
            'icon_file_name' => 'aparelho_de_cha_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 16,
            'name' => 'Arpéu',
            'description' => 'Um gancho de aço amarrado na ponta de uma corda para se fixar em muros, janelas, parapeitos de prédios... Prender um arpéu exige um teste de Pontaria (CD 15). Subir um muro com a ajuda de uma corda fornece +5 no teste de Atletismo.',
            'type' => 'tools',
            'cost' => 5,
            'slots' => 1,
            'icon_file_name' => 'arpeu_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14005],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 17,
            'name' => 'Asas do Texugo',
            'description' => 'Criada por uma serelepe tribo de kobolds, este implemento deixou inúmeros engenhoqueiros goblins se perguntando: “Por que não pensei nisso antes?”. Consiste em uma mochila vestida às costas, da qual se projetam duas asas retráteis. Pedais, manivelas e roldanas permitem o “bater” das asas, sustentando um voo um tanto irregular, mas controlável. Para voar com as asas do texugo, é necessário gastar uma ação de movimento para estender as asas (quando não estão em uso, elas ficam dobradas dentro da “mochila”). Isso faz com que você passe a ocupar o espaço de uma criatura uma categoria de tamanho maior que a sua. Para sair do chão é necessário fazer um teste de Atletismo para saltar (CD 15) para pegar impulso. Se passar, você recebe deslocamento de voo 12m por 1 rodada, +1 rodada para cada 5 pontos pelos quais o resultado superar a CD. Saltar de um ponto elevado, como do telhado de um celeiro, fornece +5 nesse teste. Ao fim do tempo de voo, você plana rumo ao chão (como no efeito básico da magia Queda Suave). Enquanto está voando, você fica vulnerável e em condição ruim para lançar magias (você ignora essas penalidades se for treinado em Pilotagem).',
            'type' => 'tools',
            'cost' => 200,
            'slots' => 2,
            'icon_file_name' => 'asas_do_texugo_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 18,
            'name' => 'Astrolábio',
            'description' => 'Um avançado instrumento de navegação, que usa a posição relativa das estrelas para guiar seu usuário. Sua complexidade, no entanto, limita seu uso a pessoas que receberam educação avançada. Você pode usar Conhecimento no lugar de Sobrevivência para orientar-se.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 1,
            'icon_file_name' => 'astrolabio_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 19,
            'name' => 'Bainha Adornada',
            'description' => 'Coberta de fios de ouro, gemas e outros ornamentos, esta bainha permite que você use armas como parte de um traje da corte. Com isso você pode participar de bailes, festas e encontros da nobreza devidamente armado, sem chamar atenção.',
            'type' => 'tools',
            'cost' => 100,
            'slots' => 1,
            'icon_file_name' => 'bainha_adornada_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 20,
            'name' => 'Barraca',
            'description' => 'Esta barraca de lona conta como um saco de dormir para duas pessoas e fornece +2 em testes de Sobrevivência para acampar.',
            'type' => 'tools',
            'cost' => 10,
            'slots' => 1,
            'icon_file_name' => 'barraca_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14006],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 21,
            'name' => 'Bússola',
            'description' => 'Este avançadíssimo instrumento de navegação funciona por princípios simples, mas depende de materiais raros. Por meio de um ímã, a bússola sempre aponta para o norte. Quando faz um teste de Sobrevivência para orientar-se, você rola dois dados e usa o melhor resultado.',
            'type' => 'tools',
            'cost' => 45,
            'slots' => 1,
            'icon_file_name' => 'bussola_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14009],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 22,
            'name' => 'Caixa de Voz',
            'description' => 'Este complexo mecanismo tem 1d4 cargas: quando usa uma habilidade de bardo ou nobre com alcance curto ou médio, você pode gastar uma carga para aumentar seu alcance em um passo. Segredo de engenharia da Supremacia Purista, este item não pode ser fabricado, sendo encontrado apenas nos destroços de corcéis e carruagens de comando.',
            'type' => 'tools',
            'cost' => 50,
            'slots' => 1,
            'icon_file_name' => 'caixa_de_voz_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 23,
            'name' => 'Cajado de Pastor',
            'description' => 'Um cajado comprido, com uma extremidade reta e a outra em semicírculo. Usado originalmente para guiar ovelhas, é símbolo de algumas religiões pacíficas e campestres. Se você rezar uma Missa usando um cajado de pastor, cada participante recebe 5 PV e 1 PM temporários (cumulativos com os efeitos da missa).',
            'type' => 'tools',
            'cost' => 12,
            'slots' => 2,
            'icon_file_name' => 'cajado_de_pastor_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 24,
            'name' => 'Cálice Consagrado',
            'description' => 'Um cálice santificado feito de um material relacionado ao deus (ouro para Azgher, madeira para Allihanna, obsidiana para Tenebra...). Se você for um devoto da divindade, pode usar o cálice para beber uma poção ou para aplicá-la em um aliado. Se fizer isso, o efeito da poção aumenta em +1 dado do mesmo tipo. Para usar o cálice você precisa gastar uma ação de movimento derramando a poção em seu interior, além do tempo necessário para consumi-la ou aplicá-la. A critério do mestre, esse efeito pode não funcionar com devotos de um deus inimigo (por exemplo, Azgher e Tenebra).',
            'type' => 'tools',
            'cost' => 300,
            'slots' => 1,
            'icon_file_name' => 'calice_consagrado_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 25,
            'name' => 'Cinto de Utilidades',
            'description' => 'Um cinturão de couro cheio de bolsos e fivelas, com espaço para os vários equipamentos esquisitos de um inventor. Se você estiver vestindo um cinto de utilidades, pode sacar e guardar engenhocas como uma ação livre.',
            'type' => 'tools',
            'cost' => 50,
            'slots' => 1,
            'icon_file_name' => 'cinto_de_utilidades_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 26,
            'name' => 'Colar do Suplicante',
            'description' => 'Este colar simples possui 10 contas de um material ligado à divindade em questão, como jade para Lin-Wu ou cristal para Wynna. Se você estiver vestindo um colar do suplicante de um deus do qual é devoto, uma vez por dia você pode gastar uma ação de movimento e uma das contas para recuperar 1 PM. Uma vez que as contas sejam gastas, o colar perde seu efeito.',
            'type' => 'tools',
            'cost' => 100,
            'slots' => 1,
            'icon_file_name' => 'colar_do_suplicante_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 27,
            'name' => 'Condecoração Militar',
            'description' => 'Condecorações não são compradas, são conquistadas! Uma condecoração militar é concedida por um reino ou igreja após uma missão bem-sucedida. Se estiver ostentando sua condecoração, no início de cada combate, você recebe uma quantidade de PV temporários, cumulativos com outros bônus de itens e com outras condecorações, igual a 3x o patamar em que a condecoração foi conquistada (por exemplo, 6 PV para uma condecoração obtida em patamar veterano).',
            'type' => 'tools',
            'cost' => -1,
            'slots' => 1,
            'icon_file_name' => 'condecoracao_militar_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 28,
            'name' => 'Corda',
            'description' => 'Um rolo com 10 metros de corda de cânhamo, o mesmo tipo usado em navios. Possui diversas utilidades: pode ajudar a descer um buraco ou muro (+5 em testes de Atletismo nessas situações), amarrar pessoas etc. Dar um nó firme ou especial (por exemplo, capaz de deslizar, se desfazer com um puxão etc.) exige um teste de Destreza (CD 15). Arrebentar a corda exige 2 pontos de dano de corte ou uma ação padrão e um teste de Força (CD 20).',
            'type' => 'tools',
            'cost' => 1,
            'slots' => 1,
            'icon_file_name' => 'corda_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14003],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 29,
            'name' => 'Corda de Teia',
            'description' => 'Feita com teia de aranha gigante, é mais resistente que uma corda normal. Fornece +5 em testes de Destreza para atar nós e testes de Atletismo para escalar. Arrebentar a corda exige 5 pontos de dano de corte ou um teste de Força (CD 28).',
            'type' => 'tools',
            'cost' => 100,
            'slots' => 1,
            'icon_file_name' => 'corda_de_teia_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14004],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 30,
            'name' => 'Dente Falso',
            'description' => 'Um minúsculo frasco disfarçado como um dente, dentro de sua boca. Permite que você carregue um preparado alquímico consumível ou uma poção sem ocupar espaços. Além disso, permite ingerir esse item como uma ação de movimento. É preciso quebrar o dente falso para ingerir seu conteúdo: uma vez que você faça isso, o dente falso é perdido. Você só pode ter um dente falso instalado em sua boca por vez (a menos que, de alguma forma, possua mais de uma boca…).',
            'type' => 'tools',
            'cost' => 300,
            'slots' => 0,
            'icon_file_name' => 'dente_falso_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        //TODO fix this when we add ladino
        GeneralItem::create([
            'id' => 31,
            'name' => 'Diagrama Anatômico',
            'description' => 'Este pergaminho com esquemas detalhados do corpo das principais raças humanoides à primeira vista parece feito para auxiliar curandeiros... No entanto, basta um olhar mais atento para perceber que ele mostra as regiões mais vulneráveis desses corpos, além de explicar as melhores maneiras de atacá-las. Quando usa Ataque Furtivo, você recebe +1 na margem de ameaça.',
            'type' => 'tools',
            'cost' => 75,
            'slots' => 1,
            'icon_file_name' => 'diagrama_anatomico_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 32,
            'name' => 'Emblema Religioso',
            'description' => 'Esta chapa fina de cobre maleável é gravada com a imagem de uma figura religiosa importante e vendida ou distribuída para peregrinos e fiéis em igrejas e templos. É um lembrete dos ensinamentos religiosos que até mesmo os devotos mais humildes ou ignorantes recebem. Existem emblemas religiosos específicos para cada deus e eles só funcionam com devotos da divindade específica. Você pode gastar uma ação de movimento e 1 PM para receber os benefícios de ser treinado em Religião em um teste dessa perícia até o fim da cena.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 1,
            'icon_file_name' => 'emblema_religioso_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 33,
            'name' => 'Espelho',
            'description' => 'Este pequeno espelho possui diversas utilidades: observar cantos, fazer sinais de luz e, claro, garantir que você esteja apresentável.',
            'type' => 'tools',
            'cost' => 10,
            'slots' => 1,
            'icon_file_name' => 'espelho_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 34,
            'name' => 'Espelho Refletor',
            'description' => 'Este pequeno espelho pode ser usado para refletir qualquer luz nos olhos de um inimigo, cegando-o temporariamente. Você pode gastar uma ação de movimento para fazer um teste de Ladinagem oposto a um teste de Reflexos de uma criatura em alcance curto. Se você vencer o teste oposto, ela fica desprevenida contra seu próximo ataque realizado até o fim do seu próximo turno. Pode ser usado apenas uma vez por cena — após isso, os inimigos sabem que devem se proteger contra o truque.',
            'type' => 'tools',
            'cost' => 45,
            'slots' => 1,
            'icon_file_name' => 'espelho_refletor_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 35,
            'name' => 'Estetoscópio',
            'description' => 'Médicos de Salistick usam este aparato sofisticado para auscultar o coração de pacientes. No entanto, criminosos costumam usá-lo para ouvir os movimentos de mecanismos internos de fechaduras e armadilhas, facilitando seu trabalho ardiloso. Você pode gastar uma ação padrão para fazer um teste de Investigação para ajudar a si mesmo e aplicar o bônus a um teste de Cura, ou de Ladinagem para abrir fechadura ou sabotar, que você realize até o fim do seu próximo turno.',
            'type' => 'tools',
            'cost' => 60,
            'slots' => 1,
            'icon_file_name' => 'estetoscopio_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 36,
            'name' => 'Estrepes (bolsa para 3m)',
            'description' => 'Pequenas peças de metal com pontas afiadas. Você pode gastar uma ação padrão para cobrir com estrepes um quadrado de até 3m de lado adjacente a você. Eles causam 1d4 pontos de dano de perfuração em qualquer criatura que pise na área. Uma criatura que sofra dano dos estrepes fica lenta até o fim do próximo turno dela.',
            'type' => 'tools',
            'cost' => 5,
            'slots' => 1,
            'icon_file_name' => 'estrepes_01.webp',
            'effects' => null,
            'consumable' => true,
        ]);

        GeneralItem::create([
            'id' => 37,
            'name' => 'Férula',
            'description' => 'Um cajado luxuoso (feito de madeira de boa qualidade ou de um metal precioso) com um símbolo sagrado no topo. Usado em procissões, na leitura de textos sagrados e na administração de sacramentos. O efeito de sua habilidade Canalizar Energia aumenta em +1d6. Uma férula pode ser usada como arma, com as estatísticas de uma maça.',
            'type' => 'tools',
            'cost' => 100,
            'slots' => 1,
            'icon_file_name' => 'ferula_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 38,
            'name' => 'Gema de Força',
            'description' => 'Parte dos complexos de certos engenhos da Supremacia, esta gema tem 1d4 cargas. Empunhando a gema, você pode gastar uma ação padrão e uma carga para produzir um domo semelhante ao efeito básico da magia Campo de Força. Alternativamente, quando lança essa magia, você pode usar uma carga para reduzir seu custo em –2 PM. Encontrada nos destroços de corcéis e carruagens de comando, essa gema não pode ser fabricada.',
            'type' => 'tools',
            'cost' => 100,
            'slots' => 1,
            'icon_file_name' => 'gema_de_forca_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 39,
            'name' => 'Lampião',
            'description' => 'Um cilindro com uma alça e duas portinholas. Uma chama alimentada por óleo é acesa dentro do cilindro e uma das portinholas aberta deixa a luz sair. Acender um lampião é uma ação padrão e sua luz ilumina um raio com 15m. Carregar um lampião com óleo é uma ação padrão e ele dura uma cena.',
            'type' => 'tools',
            'cost' => 7,
            'slots' => 1,
            'icon_file_name' => 'lampiao_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 40,
            'name' => 'Lampião de Foco',
            'description' => 'Esta lanterna sofisticada funciona como um lampião, mas possui uma cobertura e pequenos espelhos que concentram a luz em um cone de 24m.',
            'type' => 'tools',
            'cost' => 15,
            'slots' => 1,
            'icon_file_name' => 'lampiao_de_foco_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 41,
            'name' => 'Leque',
            'description' => 'Permite que damas e cavalheiros se abanem ou escondam a boca, para fofocar em paz. Muitos usuários se abanam como uma verdadeira mania, principalmente após ouvir algo ultrajante. Uma vez por cena, quando faz um teste de Vontade, você pode se abanar para usar seu Carisma em vez de Sabedoria nesse teste.',
            'type' => 'tools',
            'cost' => 3,
            'slots' => 1,
            'icon_file_name' => 'leque_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 42,
            'name' => 'Livro de Métodos Anti-Nimb',
            'description' => 'Jogos de azar recebem esse nome porque dependem do acaso. Os melhores jogadores são capazes de usar seu magnetismo natural, frieza e capacidade de blefar para garantir vitória em quase qualquer mesa. Este livro, contudo, contém métodos que permitem trocar toda essa aleatoriedade e malandragem por racionalidade: cálculos de estatísticas e probabilidades, truques para contar cartas, gráficos com projeções de ganhos e perdas... Você pode gastar 1 minuto consultando o livro e 1 PM para substituir testes de Jogatina por testes de Conhecimento até o fim da cena. Certos cassinos, tavernas e grupos de jogadores que notem alguém consultando um destes livros podem bani-lo do jogo (ou fazer coisa bem pior).',
            'type' => 'tools',
            'cost' => 100,
            'slots' => 1,
            'icon_file_name' => 'livro_metodos_anti_nimb_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 43,
            'name' => 'Lupa',
            'description' => 'Uma lente de aumento em uma armação metálica, com cabo de madeira. Equipamento indispensável para detetives. Quando faz um teste de Investigação para procurar usando uma lupa, você pode rolar dois dados e usar o melhor resultado.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 1,
            'icon_file_name' => 'lupa_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14010],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 44,
            'name' => 'Mapa',
            'description' => 'Um mapa de uma região (determinada pelo mestre ao ser adquirido). Fornece +5 em testes de Sobrevivência para orientar-se nessa região.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 1,
            'icon_file_name' => 'mapa_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14008],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 46,
            'name' => 'Mochila',
            'description' => 'Uma bolsa de lona com tiras para ser carregada nas costas. Não conta como item vestido.',
            'type' => 'tools',
            'cost' => 2,
            'slots' => 0,
            'icon_file_name' => 'mochila_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 47,
            'name' => 'Mochila Discreta',
            'description' => 'Como uma mochila normal, mas tem um compartimento oculto onde o usuário pode esconder objetos equivalentes a 1 espaço. Esses itens contam em sua capacidade de carga, mas você recebe +5 em testes de Ladinagem para ocultá-los (cumulativo com qualquer bônus concedido pelo próprio item). Uma mochila discreta deve ser vestida, mas não ocupa espaço de carga do personagem.',
            'type' => 'tools',
            'cost' => 20,
            'slots' => 1,
            'icon_file_name' => 'mochila_discreta_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 48,
            'name' => 'Organizador de Pergaminhos',
            'description' => 'Um estojo de madeira ou couro rígido. Se você estiver vestindo um organizador de pergaminhos, pode sacar pergaminhos como uma ação livre.',
            'type' => 'tools',
            'cost' => 25,
            'slots' => 1,
            'icon_file_name' => 'organizador_de_pergaminhos_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 49,
            'name' => 'Panfleto de Aforismos',
            'description' => 'Às vezes as religiões artonianas contam com tomos de fácil leitura, sem conteúdo pesado — apenas citações motivadoras, conselhos simples, dicas de exercícios de respiração e pequenos excertos que ajudam o fiel a relaxar. Cada panfleto de aforismos é específico de um deus e só funciona com seus devotos. Se estiver debilitado, enjoado, exausto, fatigado, fraco ou vulnerável, você pode gastar uma ação completa e 1 PM para remover uma dessas condições.',
            'type' => 'tools',
            'cost' => 60,
            'slots' => 1,
            'icon_file_name' => 'panfleto_de_aforismos_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 50,
            'name' => 'Patuá',
            'description' => 'Vários deuses artonianos ensinam seus devotos a fazer pequenos sacos contendo ervas, substâncias sagradas, pedaços de pergaminhos com orações e outros objetos minúsculos. Esses saquinhos são vestidos como amuletos, pendurados no pescoço, e protegem o devoto de todos os males. Se tiver uma devoção, você recebe redução de dano 2/mundano e resistência a magia +1.',
            'type' => 'tools',
            'cost' => 50,
            'slots' => 1,
            'icon_file_name' => 'patua_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 51,
            'name' => 'Prancheta',
            'description' => 'Uma simples tabuleta de madeira com um pergaminho, no qual o usuário pode fingir escrever alguma coisa. Alguém que carrega uma prancheta é imediatamente identificado como uma figura de autoridade — talvez um inspetor de alguma guilda, ou um enviado de um nobre. Você pode fazer testes de Nobreza para etiqueta mesmo sem ser treinado.',
            'type' => 'tools',
            'cost' => 5,
            'slots' => 1,
            'icon_file_name' => 'prancheta_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 52,
            'name' => 'Pé de Cabra',
            'description' => 'Esta barra de ferro fornece +5 em testes de Força para abrir portas, janelas e baús fechados. Um pé de cabra pode ser usado como arma, com as estatísticas de uma clava.',
            'type' => 'tools',
            'cost' => 2,
            'slots' => 1,
            'icon_file_name' => 'pe_de_cabra_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 53,
            'name' => 'Saco de Dormir',
            'description' => 'Um colchão com uma coberta fina o bastante para ser enrolada e amarrada, é especialmente útil para aventureiros, que nunca sabem onde vão passar a noite. Dormir ao relento sem um acampamento e um saco de dormir diminui sua recuperação de PV e PM.',
            'type' => 'tools',
            'cost' => 1,
            'slots' => 1,
            'icon_file_name' => 'saco_de_dormir_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 54,
            'name' => 'Sinete',
            'description' => 'Um estojo de madeira ou metal contendo cera, papéis e um anel ou carimbo com o símbolo pessoal do usuário. Reduz em –1 PM o custo das habilidades Autoridade Feudal e Favor e de outras habilidades em que, a critério do mestre, seu símbolo pessoal tenha algum peso.',
            'type' => 'tools',
            'cost' => 50,
            'slots' => 1,
            'icon_file_name' => 'sinete_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 55,
            'name' => 'Tenda do Pântano',
            'description' => 'Fabricada com partes de sapo atroz, essa tenda leve e compacta conta como um saco de dormir para duas pessoas e fornece +2 em testes de Sobrevivência para acampar.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 1,
            'icon_file_name' => 'tenda_do_pantano_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14007],
            ],
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 57,
            'name' => 'Tocha',
            'description' => 'Um bastão de madeira com algum combustível na ponta (geralmente trapos embebidos em parafina). Acender uma tocha é uma ação padrão. Ela ilumina um raio de 9m e dura uma cena. Pode ser usada como uma arma simples leve (dano 1d4 de impacto mais 1 de fogo, crítico x2).',
            'type' => 'tools',
            'cost' => 1,
            'slots' => 1,
            'icon_file_name' => 'tocha_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 59,
            'name' => 'Água Benta',
            'description' => 'Produzida com a magia Abençoar Alimentos, esta água sagrada é um poderoso recurso na luta contra o mal. Para usar a água benta, você gasta uma ação padrão e escolhe um morto-vivo, demônio ou diabo em alcance curto (a água benta é inofensiva contra outras criaturas). O alvo sofre 2d10 pontos de dano de luz (Reflexos CD Sab reduz à metade).',
            'type' => 'alchemic',
            'cost' => 10,
            'slots' => 0.5,
            'icon_file_name' => 'agua_benta_01.webp',
            'effects' => null,
            'consumable' => true,
        ]);

        GeneralItem::create([
            'id' => 60,
            'name' => 'Água Benta Concentrada',
            'description' => 'Criada com os mais puros ingredientes, esta água funciona como água benta, mas causa 4d10 pontos de dano. É fabricada com o mesmo aprimoramento de Abençoar Alimentos, mas seu componente material custa T$ 30.',
            'type' => 'alchemic',
            'cost' => 60,
            'slots' => 0.5,
            'icon_file_name' => 'agua_benta_concentrada_01.webp',
            'effects' => null,
            'consumable' => true,
        ]);

        GeneralItem::create([
            'id' => 61,
            'name' => 'Aspersório',
            'description' => 'Este item parece uma maça, mas sua cabeça é um reservatório de água benta feito de metal perfurado. Você pode gastar uma ação padrão para dispersar uma dose de água benta (normal ou concentrada) em um cone de 4,5m; a CD para resistir à água benta aumenta em +2. Um aspersório pode armazenar até 3 doses de água benta. Carregá-lo exige uma ação completa e o gasto das doses.',
            'type' => 'tools',
            'cost' => 50,
            'slots' => 1,
            'icon_file_name' => 'aspersorio_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 62,
            'name' => 'Amuleto de Khalmyr',
            'description' => 'Este amuleto em formato de balança não é usado apenas por devotos de Khalmyr — todos os artonianos podem se beneficiar dele. Contudo, por não ser mágico, dizem que apenas os chatos mais previsíveis o utilizam... Quando faz um teste de perícia, você pode fazer um teste de Sabedoria (CD 10). Se passar, pode escolher 10 para esse teste de perícia (em vez de usar o valor rolado no d20). Uma vez que você use este efeito em um teste, o amuleto perde seus “poderes”, tornando-se inútil.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 1,
            'icon_file_name' => 'amuleto_de_khalmyr_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 63,
            'name' => 'Amuleto de Nimb',
            'description' => 'Este amuleto em formato de dado não é usado apenas por devotos de Nimb — todos os artonianos podem se beneficiar dele. Contudo, por não ser mágico, dizem que apenas os crédulos o utilizam... Quando faz um teste de perícia, você pode fazer um teste de Inteligência (CD 10). Se fizer o teste de Inteligência e falhar nele, pode rolar novamente o teste de perícia e usar o melhor resultado. Uma vez que você use este efeito em um teste, o amuleto perde seus “poderes”, tornando-se inútil.',
            'type' => 'tools',
            'cost' => 30,
            'slots' => 1,
            'icon_file_name' => 'amuleto_de_nimb_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 64,
            'name' => 'Dente de Wisphago',
            'description' => 'Um dente de wisphago pode ser vestido como um amuleto. Quando faz um teste de resistência contra uma magia arcana, você pode gastar o amuleto para rolar novamente esse teste. Uma vez ativado, o amuleto se desfaz. Um dente de wisphago não pode ser fabricado.',
            'type' => 'tools',
            'cost' => 100,
            'slots' => 1,
            'icon_file_name' => 'dente_de_wisphago_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 65,
            'name' => 'Óleo',
            'description' => 'Um frasco com óleo inflamável para Lampião. Você pode atirar o frasco em uma criatura em alcance curto com uma ação padrão. Se ela sofrer dano de fogo até o fim do seu próximo turno, sofre 1d6 pontos de dano extra e fica em chamas.',
            'type' => 'tools',
            'cost' => 1,
            'slots' => 0.5,
            'icon_file_name' => 'oleo_01.webp',
            'effects' => null,
            'consumable' => true,
        ]);

        GeneralItem::create([
            'id' => 58,
            'name' => 'Vara de Madeira (3m)',
            'description' => 'Uma haste com 3m de comprimento. Útil para alcançar pontos distantes, mas frágil demais para servir como arma.',
            'type' => 'tools',
            'cost' => 1,
            'slots' => 1,
            'icon_file_name' => 'vara_de_madeira_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);

        GeneralItem::create([
            'id' => 56,
            'name' => 'Texto Sagrado',
            'description' => 'Todas as religiões de Arton têm um ou mais textos sagrados — escrituras, parábolas, relatos históricos ou até mesmo diagramas que contêm os maiores valores da divindade e oferecem alento a seus fiéis em momentos de necessidade. Cada texto sagrado é específico de um deus e só funciona com seus devotos. Se estiver abalado, alquebrado, apavorado, esmorecido ou frustrado, você pode gastar uma ação completa e 1 PM para remover uma dessas condições.',
            'type' => 'tools',
            'cost' => 60,
            'slots' => 1,
            'icon_file_name' => 'texto_sagrado_01.webp',
            'effects' => null,
            'consumable' => false,
        ]);
    }
}
