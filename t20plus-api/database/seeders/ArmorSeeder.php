<?php

namespace Database\Seeders;

use App\Models\Armor;
use Illuminate\Database\Seeder;

class ArmorSeeder extends Seeder
{

    public function run(): void
    {

        Armor::create([
            'id' => 1,
            'name' => 'Traje de Sacerdote',
            'description' => 'O traje de sacerdote em é um item inicial de interpretação (roleplay) recebido por personagens com a origem Acólito.',
            'type' => 'vestment',
            'mod_def' => 0,
            'armor_penalty' => 0,
            'cost' => -1,
            'slots' => 1,
            'icon_file_name' => 'traje_de_sacerdote_01.webp',
        ]);

        Armor::create([
            'id' => 2,
            'name' => 'Armadura de Couro',
            'description' => 'O peitoral desta armadura é feito de couro curtido em óleo fervente, para ficar mais rígido, enquanto as demais partes são feitas de couro flexível.',
            'type' => 'light',
            'mod_def' => 2,
            'armor_penalty' => 0,
            'cost' => 20,
            'slots' => 2,
            'icon_file_name' => 'armadura_de_couro_01.webp',
        ]);

        Armor::create([
            'id' => 3,
            'name' => 'Couro Batido',
            'description' => 'Versão mais pesada da armadura de couro, reforçada com rebites de metal.',
            'type' => 'light',
            'mod_def' => 3,
            'armor_penalty' => 1,
            'cost' => 35,
            'slots' => 2,
            'icon_file_name' => 'armadura_couro_batido_01.webp',
        ]);

        Armor::create([
            'id' => 4,
            'name' => 'Gibão de Peles',
            'description' => 'Usada principalmente por bárbaros e selvagens, esta armadura é formada por várias camadas de peles e couro de animais.',
            'type' => 'light',
            'mod_def' => 4,
            'armor_penalty' => 3,
            'cost' => 25,
            'slots' => 2,
            'icon_file_name' => 'gibao_de_peles_01.webp',
        ]);

        Armor::create([
            'id' => 5,
            'name' => 'Brunea',
            'description' => 'Colete de couro coberto com plaquetas de metal sobrepostas, como escamas de um peixe. Por ser barata de produzir, é a armadura mais utilizada no Reinado por soldados de infantaria e guardas de castelo.',
            'type' => 'heavy',
            'mod_def' => 5,
            'armor_penalty' => 2,
            'cost' => 50,
            'slots' => 5,
            'icon_file_name' => 'brunea_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);

        Armor::create([
            'id' => 6,
            'name' => 'Traje de Plebeu',
            'description' => 'Roupas típicas de aldeão, incluem uma camisa larga e calças soltas, ou saia e vestido. Não inclui calçados — os mais pobres andam descalços.',
            'type' => 'vestment',
            'mod_def' => 0,
            'armor_penalty' => 0,
            'cost' => 1,
            'slots' => 1,
            'icon_file_name' => 'traje_de_plebeu_01.webp',
        ]);

        Armor::create([
            'id' => 7,
            'name' => 'Armadura Acolchoada',
            'description' => 'Uma túnica almofadada feita em linho ou lã. É a armadura mais leve, mas protege todo o corpo, fornecendo +2 em Fortitude.',
            'type' => 'light',
            'mod_def' => 1,
            'armor_penalty' => 0,
            'cost' => 5,
            'slots' => 2,
            'icon_file_name' => 'armadura_acolchoada_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14046],
            ],
        ]);

        Armor::create([
            'id' => 8,
            'name' => 'Armadura Sensual',
            'description' => 'As histórias de heróis estão repletas de guerreiros que vestem armaduras pouco práticas — feitas para revelar quase tudo do corpo do usuário, ressaltando seus músculos e curvas, rendendo imagens provocantes para capas de livros e iluminuras em pergaminhos. Nunca foram pensadas para uso na vida real... Mas alguém, em algum momento, achou que isso seria boa ideia. Uma armadura sensual é feita de pedaços (minúsculos) de cota de malha, (pouquíssimas) escamas ou tiras (extremamente finas) de metal. A armadura sensual não oferece muita proteção, mas permite usar o poder Atraente. Se você já tiver esse poder, em vez disso o bônus fornecido por ele aumenta para +5. Note que, em muitos lugares de Arton, uma armadura sensual não é um traje apropriado para bailes, reuniões diplomáticas ou mesmo para andar na rua! O usuário pode ser barrado desses lugares ou o bônus da armadura pode ser transformado em penalidade, a critério do mestre. As únicas ocasiões em que esses bônus nunca são anulados são combates.<br><br>No APP, se já tiver o poder, adicione os +3 para as perícias manualmente na rolagem.',
            'type' => 'light',
            'mod_def' => 1,
            'armor_penalty' => 0,
            'cost' => 55,
            'slots' => 2,
            'icon_file_name' => 'armadura_sensual_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11034],
            ],
        ]);

        Armor::create([
            'id' => 9,
            'name' => 'Armadura de Folhas',
            'description' => 'Esta armadura é um traje de tecido leve sobre o qual são costuradas várias camadas de folhas especialmente preparadas com óleos naturais. É uma armadura leve que fornece pouca defesa, mas permite que o usuário se mantenha conectado com as forças da natureza. Se for treinado em Sobrevivência, você recebe +2 PM com esta armadura (somente após 1 dia de uso), cumulativo com outros efeitos de itens.',
            'type' => 'light',
            'mod_def' => 2,
            'armor_penalty' => 0,
            'cost' => 75,
            'slots' => 2,
            'icon_file_name' => 'armadura_de_folhas_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14047],
            ],
        ]);

        Armor::create([
            'id' => 10,
            'name' => 'Armadura de Ossos',
            'description' => 'Os nezumi e outros povos são conhecidos por produzir estas armaduras sinistras. Combinando um aspecto assustador e energias negativas das ossadas, essa armadura fornece +1 em Intimidação e CD de seus efeitos de medo.',
            'type' => 'light',
            'mod_def' => 3,
            'armor_penalty' => 2,
            'cost' => 120,
            'slots' => 2,
            'icon_file_name' => 'armadura_de_ossos_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14048],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14049],
            ],
        ]);

        Armor::create([
            'id' => 11,
            'name' => 'Cota de Moedas',
            'description' => 'O cúmulo da ostentação, esta armadura se assemelha a uma brunea, com um diferencial — em vez de discos metálicos, a malha é formada por tibares entrelaçados! Você recebe +2 em Diplomacia (cumulativo com melhorias da armadura). O mestre pode mudar o bônus para uma penalidade de –2 contra pessoas que desprezam ostentação.',
            'type' => 'light',
            'mod_def' => 4,
            'armor_penalty' => 3,
            'cost' => 350,
            'slots' => 2,
            'icon_file_name' => 'cota_de_moedas_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14050],
            ],
        ]);

        Armor::create([
            'id' => 12,
            'name' => 'Veste de Teia de Aranha',
            'description' => 'Feita com teia de aranha gigante, esta veste é cinza-escura, maleável e silenciosa, mas também forte como aço. Fornece +5 em Furtividade, mas não pode receber a melhoria material especial.',
            'type' => 'light',
            'mod_def' => 4,
            'armor_penalty' => 0,
            'cost' => 3000,
            'slots' => 2,
            'icon_file_name' => 'veste_teia_de_aranha_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14051],
            ],
        ]);

        Armor::create([
            'id' => 13,
            'name' => 'Cota de Anéis',
            'description' => 'Esta armadura consiste de uma série de aneis metálicos costurados em uma base de couro. Reduz seu deslocamento em –1,5m.',
            'type' => 'light',
            'mod_def' => 5,
            'armor_penalty' => 2,
            'cost' => 250,
            'slots' => 2,
            'icon_file_name' => 'cota_de_aneis_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14052],
            ],
        ]);

        Armor::create([
            'id' => 14,
            'name' => 'Colete Fora da Lei',
            'description' => 'Diz-se que este colete metálico único foi desenvolvido por uma quadrilha de Smokestone — um bando com muita criatividade e pouco caráter. A forma arredondada da armadura e as ranhuras em sua superfície redirecionam projéteis, tornando-a ideal para proteger o usuário contra balas e flechas. Muito útil quando todos ao seu redor levam pistolas na cintura! Contra armas de disparo, o bônus na Defesa de um colete fora da lei aumenta em +2.<br><br>Adicione o bônus em defesa manualmente caso seja alvo de um disparo.',
            'type' => 'light',
            'mod_def' => 5,
            'armor_penalty' => 5,
            'cost' => 750,
            'slots' => 2,
            'icon_file_name' => 'colete_fora_da_lei_01.webp',
        ]);

        Armor::create([
            'id' => 15,
            'name' => 'Couraça',
            'description' => 'A mais robusta das armaduras leves, formada por uma placa metálica que protege o peito e as costas, presa sobre um casaco de couro.',
            'type' => 'light',
            'mod_def' => 5,
            'armor_penalty' => 4,
            'cost' => 500,
            'slots' => 2,
            'icon_file_name' => 'couraca_01.webp',
        ]);

        Armor::create([
            'id' => 16,
            'name' => 'Cota de Malha',
            'description' => 'Longa veste de anéis metálicos interligados, formando uma malha flexível e resistente, que vai até os joelhos.',
            'type' => 'heavy',
            'mod_def' => 6,
            'armor_penalty' => 2,
            'cost' => 150,
            'slots' => 5,
            'icon_file_name' => 'cota_de_malha_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);

        Armor::create([
            'id' => 17,
            'name' => 'Brigantina',
            'description' => 'Também chamada de “brigandina”, esta armadura é uma opção para combatentes que não podem comprar armaduras de placas. À primeira vista, a brigantina só tem vantagens — feita de uma vestimenta de couro com pequenas placas metálicas rebitadas, é mais flexível que armaduras metálicas comuns, ainda fornecendo a proteção do aço. Contudo, a brigantina tem um defeito: embora as múltiplas pequenas placas protejam bem contra armas de corte e impacto, é fácil para uma arma de perfuração penetrar entre elas. Contra armas de perfuração, o bônus na Defesa de uma brigantina diminui em –2.<br><br>Se for alvo de um ataque de perfuração, reduza sua defesa em -2 manualmente.',
            'type' => 'heavy',
            'mod_def' => 6,
            'armor_penalty' => 0,
            'cost' => 75,
            'slots' => 5,
            'icon_file_name' => 'brigantina_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);

        Armor::create([
            'id' => 18,
            'name' => 'Armadura de Chumbo',
            'description' => 'Esta armadura extremamente pesada e desajeitada faz o usuário parecer mais uma estátua do que um cavaleiro... No entanto, é a preferida de alguns infelizes que já sofreram nas mãos de arcanistas. Uma armadura de chumbo é mais pesada e incômoda do que uma armadura completa, sem oferecer a mesma proteção. No entanto, fornece redução de dano 2/mundano e resistência a magia +2, cumulativo com outros efeitos de itens.',
            'type' => 'heavy',
            'mod_def' => 7,
            'armor_penalty' => 5,
            'cost' => 750,
            'slots' => 5,
            'icon_file_name' => 'armadura_de_chumbo_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14053],
            ],
        ]);

        Armor::create([
            'id' => 19,
            'name' => 'Armadura de Justa',
            'description' => 'Esta meia armadura é feita especialmente para ser usada por cavaleiros em justas. Assim, é toda feita de placas de metal, bastante adornada, e tem aparência aristocrática e tradicional. Seu grande diferencial, contudo, é a proteção extra no lado do peito do usuário que provavelmente será atingido pela lança do oponente — o lado em que ele próprio não empunha sua lança. A armadura de justa fornece +5 em testes para resistir a ser derrubado enquanto montado (cumulativo com outros efeitos de itens) e, contra armas de perfuração, seu bônus na Defesa aumenta em +2. Contudo, devido a seu peso e estrutura, sua penalidade de armadura aumenta em 2 se você não estiver montado.',
            'type' => 'heavy',
            'mod_def' => 9,
            'armor_penalty' => 5,
            'cost' => 1200,
            'slots' => 5,
            'icon_file_name' => 'armadura_de_justa_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14054],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14055],
            ],
        ]);

        Armor::create([
            'id' => 20,
            'name' => 'Armadura de Hussardo Alado',
            'description' => 'Alguns dos reinos mais avançados (e menos tradicionalistas) de Arton começaram a equipar seus cavaleiros com estas armaduras vistosas e impressionantes. Uma armadura de hussardo alado é uma armadura completa com um adicional: duas hastes longas nas costas, cobertas de penas, que parecem asas! Quando você avança a galope, as “asas” zunem ao vento, criando um som desconcertante e amedrontador. Quando faz uma investida montada, você pode fazer um teste de Intimidação para assustar o alvo de sua investida. Esse efeito só funciona uma vez por cena com cada oponente.',
            'type' => 'heavy',
            'mod_def' => 10,
            'armor_penalty' => 6,
            'cost' => 4500,
            'slots' => 5,
            'icon_file_name' => 'armadura_hussardo_alado_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);

        Armor::create([
            'id' => 21,
            'name' => 'Armadura de Pedra',
            'description' => 'Esta armadura completa foi originalmente projetada por facções tradicionalistas da Guilda dos Armeiros de Doherimm, apenas em tamanho anão. Recentemente, anões rebeldes e interessados em lucro começaram a fabricar versões utilizáveis pelas demais raças artonianas. Além de sua defesa elevada, uma armadura de pedra fornece redução de dano 2 (cumulativo com outros efeitos de itens). Contudo, toda essa proteção tem um custo: seu deslocamento é reduzido pela metade por essa armadura pesada, em vez de em 3m. A armadura de pedra não pode receber a melhoria material especial.',
            'type' => 'heavy',
            'mod_def' => 12,
            'armor_penalty' => 5,
            'cost' => 5500,
            'slots' => 5,
            'icon_file_name' => 'armadura_de_pedra_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14056],
            ],
        ]);

        Armor::create([
            'id' => 22,
            'name' => 'Cota de Talas',
            'description' => 'Longas tiras de aço — as talas — rebitadas e costuradas em um forro de tecido.',
            'type' => 'heavy',
            'mod_def' => 7,
            'armor_penalty' => 3,
            'cost' => 250,
            'slots' => 5,
            'icon_file_name' => 'cota_de_talas_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);

        Armor::create([
            'id' => 23,
            'name' => 'Loriga Segmentada',
            'description' => 'Composta por tiras horizontais de metal, esta armadura pesada é muito utilizada por legionários do Império de Tauron.',
            'type' => 'heavy',
            'mod_def' => 7,
            'armor_penalty' => 3,
            'cost' => 250,
            'slots' => 5,
            'icon_file_name' => 'loriga_segmentada_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);

        Armor::create([
            'id' => 24,
            'name' => 'Armadura de Quitina',
            'description' => 'Feita com carapaças selecionadas de grandes insetos, esta armadura dos povos-trovão é mais leve que suas contrapartes metálicas. Embora seja uma armadura pesada, não reduz o deslocamento do usuário.',
            'type' => 'heavy',
            'mod_def' => 7,
            'armor_penalty' => 3,
            'cost' => 350,
            'slots' => 5,
            'icon_file_name' => 'armadura_quitina_01.webp',
        ]);

        Armor::create([
            'id' => 25,
            'name' => 'Meia Armadura',
            'description' => 'Uma cota de malha reforçada com placas de metal.',
            'type' => 'heavy',
            'mod_def' => 8,
            'armor_penalty' => 4,
            'cost' => 600,
            'slots' => 5,
            'icon_file_name' => 'meia_armadura_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);

        Armor::create([
            'id' => 26,
            'name' => 'Escafandro Kliren',
            'description' => 'O escafandro kliren é uma armadura própria para a sobrevivência no éter divino. A armadura cobre todo o corpo do usuário, protegendo-o do frio e da descompressão, além de armazenar uma reserva de ar respirável. Um escafandro fornece redução de frio 5 e permite respirar por até 1 hora em ambientes inóspitos como debaixo d\'água, no vácuo e em nuvens de gás ou fumaça. Após este período, você deve passar pelo menos 10 minutos em um ambiente com ar respirável para recarregar a reserva de ar da armadura.',
            'type' => 'heavy',
            'mod_def' => 8,
            'armor_penalty' => 6,
            'cost' => 1200,
            'slots' => 5,
            'icon_file_name' => 'escafandro_kliren_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);

        Armor::create([
            'id' => 27,
            'name' => 'Armadura Completa',
            'description' => 'A mais forte e pesada das armaduras, formada por placas de metal forjadas e encaixadas de modo a cobrir o corpo inteiro. Inclui uma túnica acolchoada para ser usada sob as placas. Correias e fivelas distribuem o peso da armadura pelo corpo inteiro. Esta armadura precisa ser feita sob medida para cada usuário; um ferreiro cobra T$ 200 para adaptar uma armadura completa a um novo usuário.',
            'type' => 'heavy',
            'mod_def' => 10,
            'armor_penalty' => 5,
            'cost' => 3000,
            'slots' => 5,
            'icon_file_name' => 'armadura_completa_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14014],
            ],
        ]);
    }
}
