<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ItemGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 13,
            'name' => 'Farpada',
            'description' => 'Um acerto crítico causa a condição Sangrando no alvo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arma_farpada_01.webp',
            'effects' => [
                [
                    'tag' => 'on_critical_strike',
                    'op' => 'inflict',
                    'condition_id' => 1
                ],
            ],
        ]);

        Power::create([
            'id' => 14,
            'name' => 'Arma - Matéria Vermelha',
            'description' => 'Causa +1d6 de dano extra ao acertar, mas o usuário perde 1 ponto de vida.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arma_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => '1d6'],
                ['tag' => 'self_damage', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15,
            'name' => 'Armadura/Escudo Leve - Matéria Vermelha',
            'description' => 'Ataques contra o usuário têm 10% de chance de falhar automaticamente.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_leve_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 16,
            'name' => 'Armadura Pesada - Matéria Vermelha',
            'description' => 'Ataques contra o usuário têm 25% de chance de falhar automaticamente.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_pesada_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 25],
            ],
        ]);

        Power::create([
            'id' => 17,
            'name' => 'Esotérico - Matéria Vermelha (Portador)',
            'description' => 'O usuário sofre -2 em testes de resistência contra efeitos mágicos.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'esotericos_materia_vermelha_01.webp',
            'effects' => [

                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 19,
            'name' => 'Esotérico - Matéria Vermelha (Inimigos Próximos)',
            'description' => 'Inimigos a curto alcance do portador sofrem -2 em testes de resistência contra efeitos mágicos.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'esotericos_materia_vermelha_01.webp',
            'range' => 9,
            'effects' => [

                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 18,
            'name' => 'Instrumento Musical - Matéria Vermelha',
            'description' => 'Aumenta em +1 a CD das habilidades de bardo (exceto magias) quando o usuário utiliza o instrumento.',
            'source' => 'item_granted',

            'usability' => 'dc_active',
            'icon_file_name' => 'instrumento_musical_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'mod_dc', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 140,
            'name' => 'Certeira',
            'description' => 'Fabricada para ser mais precisa e balanceada, a arma fornece +1 nos testes de ataque.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'certeira_01.webp',
            'effects' => [

                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 1, 'stack_group' => 'pungente'],
            ],
        ]);

        Power::create([
            'id' => 141,
            'name' => 'Pungente',
            'description' => 'Temperada diversas vezes para adquirir o fio ou o equilíbrio perfeito, a arma fornece +2 nos testes de ataque.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'pungente_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2, 'stack_group' => 'pungente'],
            ],
        ]);

        Power::create([
            'id' => 142,
            'name' => 'Cruel',
            'description' => 'Rebarbas, espinhos e até mesmo lâminas adicionais compõem uma arma cruel. Ela fornece +1 nas rolagens de dano.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cruel_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 1, 'stack_group' => 'atroz'],
            ],
        ]);

        Power::create([
            'id' => 143,
            'name' => 'Atroz',
            'description' => 'A arma é um amontoado de pontas, ganchos e protuberâncias. É difícil empunhá-la sem se machucar, mas ela fornece +2 nas rolagens de dano.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'atroz_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2, 'stack_group' => 'atroz'],
            ],
        ]);

        Power::create([

            'id' => 144,
            'name' => 'Penetrante (Arma)',
            'description' => 'A arma ignora 5 pontos da redução de dano.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'penetrante_01.webp',
            'effects' => [
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 145,
            'name' => 'Equilibrada',
            'description' => 'Uma arma equilibrada é forjada com o balanço perfeito, o que facilita movimentos complexos. Ela fornece +2 em testes de manobras (desarmar, quebrar etc.).',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'equilibrada_01.webp',

            'effects' => [
                ['tag' => 'mod_maneuver', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 148,
            'name' => 'Guarda',
            'description' => 'A arma possui uma proteção elaborada próxima a sua empunhadura, que fornece +1 na Defesa.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'guarda_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 149,
            'name' => 'Guarda (Manobras)',
            'description' => 'A arma possui uma proteção elaborada próxima a sua empunhadura, que fornece +1 em testes contra manobras.',
            'source' => 'item_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'guarda_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 150,
            'name' => 'Harmonizada',
            'description' => 'Escolha uma habilidade ativada ao se fazer um ataque ou usar a ação agredir e que custe pontos de mana. Esta habilidade tem seu custo em PM reduzido em –1 se utilizada com esta arma. <br><br>No APP, essa habilidade vem marcada por padrão na tela de rolagem de ataque. Caso não esteja utilizando a habilidade definida, desmarque.',
            'source' => 'item_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'harmonizada_01.webp',
            'default_checked' => true,
            'pm_cost' => -1,
        ]);

        Power::create([
            'id' => 151,
            'name' => 'Injeção Alquímica',
            'description' => 'Um ataque que acerte causa seu dano normal e libera uma carga de ácido, fogo alquímico ou água benta, que atinge o alvo automaticamente. A modificação tem espaço para 2 cargas. Recarregá-la exige uma ação completa e o gasto dos itens alquímicos que você quiser inserir. <br><br>No APP, adicione manualmente o dano extra.',
            'source' => 'item_granted',

            'usability' => 'passive',
            'icon_file_name' => 'injecao_alquimica_01.webp',
        ]);

        Power::create([
            'id' => 152,
            'name' => 'Maciça',
            'description' => 'A arma é feita com material denso, fazendo com que seus golpes tenham impacto terrível. O multiplicador de crítico da arma aumenta em 1 ponto.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'maciça_01.webp',
            'effects' => [
                ['tag' => 'mod_multiplier', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 153,
            'name' => 'Precisa',
            'description' => 'Cuidado especial foi tomado ao temperar o aço desta arma, para que seu fio se mantenha sempre como uma navalha. A margem de ameaça aumenta em 1 ponto.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'precisa_01.webp',
            'effects' => [
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 154,
            'name' => 'Mira Telescópica',
            'description' => 'Aumenta o alcance da arma em uma categoria (de curto para médio, de médio para longo) e o alcance da habilidade Ataque Furtivo para médio.',
            'source' => 'item_granted',

            'usability' => 'passive',
            'icon_file_name' => 'mira_telescopica_01.webp',
        ]);

        Power::create([
            'id' => 155,
            'name' => 'Pressurizada',
            'description' => 'Você pode gastar uma ação completa para puxar o pistão e pressurizar a câmara. Quando faz um ataque com a arma, se ela estiver pressurizada, você pode descarregar a pressão para aumentar o impacto do golpe. Se fizer isso, você recebe +2 no teste de ataque e na rolagem de dano.',
            'source' => 'item_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'pressurizada_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 156,
            'name' => 'Arma - Aço-Rubi',
            'description' => 'A arma ignora 10 pontos de redução de dano, além de ignorar a imunidade a crítico de lefeu.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'aco_rubi_arma_01.webp',
            'effects' => [
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => 10],

                ['tag' => 'ignore_lefeu_critical_immunity', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 157,
            'name' => 'Armadura/Escudo Leve - Aço-Rubi',
            'description' => 'Fornece uma chance de ignorar o dano extra de acertos críticos e ataques furtivos: 25% (1 em 1d4).',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'aco_rubi_leve_escudo_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 158,
            'name' => 'Armadura Pesada - Aço-Rubi',
            'description' => 'Fornece uma chance de ignorar o dano extra de acertos críticos e ataques furtivos: 50% (qualquer valor par em qualquer dado), cumulativas com a de um escudo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'aco_rubi_pesada_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 159,
            'name' => 'Esotérico - Aço-Rubi',
            'description' => 'Quando lança uma magia que causa dano, ela ignora 10 pontos de redução de dano, além de ignorar as imunidades a dano de lefeu.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'aco_rubi_esoterico_01.webp',
            // TODO: no effects — needs a spellcasting system (damage

        ]);

        Power::create([
            'id' => 160,
            'name' => 'Arma - Adamante',
            'description' => 'Aumenta o dano da arma em um passo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adamante_arma_01.webp',
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 161,
            'name' => 'Armadura/Escudo Leve - Adamante',
            'description' => 'Fornece redução de dano 2.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adamante_leve_escudo_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 162,
            'name' => 'Armadura Pesada - Adamante',
            'description' => 'Fornece redução de dano 5.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adamante_pesada_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 163,
            'name' => 'Esotérico - Adamante',
            'description' => 'Quando lança uma magia que causa dano, você pode pagar +1 PM para rolar novamente qualquer resultado 1 na rolagem de dano dela.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adamante_esoterico_01.webp',
            // TODO: no effects — needs a spellcasting system that doesn't

        ]);

        Power::create([
            'id' => 164,
            'name' => 'Arma - Casco Monstruoso',
            'description' => 'Conta como uma arma primitiva para a magia Armamento da Natureza e efeitos semelhantes.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'casca_de_monstro_arma_01.webp',
            // TODO: no effects — needs a spellcasting system (Armamento da

        ]);

        Power::create([
            'id' => 165,
            'name' => 'Armadura/Escudo Leve - Casco Monstruoso',
            'description' => 'Penalidade de armadura diminuída em 1.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'casca_de_monstro_leve_e_escudo_01.webp',
            'effects' => [
                ['tag' => 'mod_armor_penalty', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 166,
            'name' => 'Armadura Pesada - Casco Monstruoso',
            'description' => 'Penalidade de armadura diminuída em 1. Fornece +1 na Defesa.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'casca_de_monstro_pesada_01.webp',
            'effects' => [
                ['tag' => 'mod_armor_penalty', 'op' => 'add', 'value' => -1],

                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 167,
            'name' => 'Arma - Couraça de Kaiju',
            'description' => 'O dano da arma aumenta um passo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'kaiju_arma_01.webp',
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 168,
            'name' => 'Arma - Couraça de Kaiju (Ignorar Redução)',
            'description' => 'Quando acerta um ataque com a arma, você pode gastar 2 PM para ignorar efeitos que reduzem o dano desse ataque (como as habilidades Durão e Escamas Supremas).',
            'source' => 'item_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'kaiju_arma_01.webp',
            'pm_cost' => 2,
            'effects' => [
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => '100%'],
            ],
        ]);

        Power::create([
            'id' => 169,
            'name' => 'Armadura/Escudo Leve - Couraça de Kaiju',
            'description' => 'Exige uma peça. Fornece redução de dano 10/mágico.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'kaiju_leve_escudo_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 170,
            'name' => 'Armadura Pesada - Couraça de Kaiju',
            'description' => 'Exige três peças. Fornece redução de dano 20/mágico.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'kaiju_pesada_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 171,
            'name' => 'Esotérico - Couraça de Kaiju',
            'description' => 'Exige uma peça. Quando lança uma magia que causa dano, você pode gastar 1 PM para ignorar efeitos dos alvos que reduzem o dano dessa magia, como Durão ou Evasão (exceto a redução de dano por passar no teste de resistência da magia).',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'kaiju_esoterico_01.webp',
            // TODO: no effects — needs a spellcasting system that doesn't

        ]);

        Power::create([
            'id' => 172,
            'name' => 'Armadura/Escudo Leve - Couro de Dragão',
            'description' => 'Exige uma peça. Fornece +1 na Defesa, redução de dano 10 contra o tipo de dano do sopro do dragão e +2 de resistência a magia.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'couro_dragao_leve_escudo_01.webp',

            // TODO: we'll add a damage reduction section later.
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 173,
            'name' => 'Armadura Pesada - Couro de Dragão',
            'description' => 'Exige três peças. Fornece +2 na Defesa, redução de dano 20 contra o tipo de dano do sopro do dragão e +5 de resistência a magia.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'couro_de_dragao_pesada_01.webp',

            // TODO: we'll add a damage reduction section later.
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 188,
            'name' => 'Armadura/Escudo Leve - Couro de Dragão (Resistência a Magia)',
            'description' => 'Fornece +2 de resistência a magia.',
            'source' => 'item_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'couro_dragao_leve_escudo_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 189,
            'name' => 'Armadura Pesada - Couro de Dragão (Resistência a Magia)',
            'description' => 'Fornece +5 de resistência a magia.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'couro_de_dragao_pesada_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 174,
            'name' => 'Esotérico - Couro de Dragão',
            'description' => 'Exige uma peça. Quando lança uma magia que cause dano do mesmo tipo do sopro do dragão, você pode gastar 1 PM para aumentar o dano da magia em +1 por dado.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'couro_dragao_esoterico_01.webp',
            // TODO: no effects — needs a spellcasting system that doesn't

        ]);

        Power::create([
            'id' => 175,
            'name' => 'Arma - Cristal de Sol',
            'description' => 'A arma causa +2 pontos de dano de fogo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cristal_de_sol_arma_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 176,
            'name' => 'Armadura - Cristal de Sol',
            'description' => 'Quando faz um teste de resistência contra um efeito de frio, você pode rolar dois dados e usar o melhor resultado.',
            'source' => 'item_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'cristal_de_sol_armadura_01.webp',
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 10],
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 26],
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 29],
            ],
        ]);

        Power::create([
            'id' => 177,
            'name' => 'Esotérico - Cristal de Sol',
            'description' => 'Quando lança uma magia de fogo, você pode gastar 1 PM. Se fizer isso, criaturas que falham em seu teste de resistência contra a magia ficam em chamas (cumulativo com condições causadas pela magia).',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cristal_de_sol_esotericos_01.webp',
            // TODO: no effects — needs a spellcasting system that doesn't

        ]);

        Power::create([
            'id' => 178,
            'name' => 'Arma - Gelo Eterno',
            'description' => 'A arma causa +2 pontos de dano de gelo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'gelo_eterno_armas_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 179,
            'name' => 'Armadura/Escudo Leve - Gelo Eterno',
            'description' => 'Fornece redução de fogo 5.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'gelo_eterno_leve_e_escudos_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 180,
            'name' => 'Armadura Pesada - Gelo Eterno',
            'description' => 'Fornece redução de fogo 10.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'gelo_eterno_pesada_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 181,
            'name' => 'Esotérico - Gelo Eterno',
            'description' => 'Quando lança uma magia de frio que causa dano, você pode rolar novamente qualquer resultado 1 na rolagem de dano dela.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'gelo_eterno_Esotericos_01.webp',
            // TODO: no effects — needs a spellcasting system that doesn't

        ]);

        Power::create([
            'id' => 182,
            'name' => 'Arma - Lanajuste',
            'description' => 'Ataques com a arma ignoram penalidades por combate submerso. Além disso, pode ser utilizada por devotos de Oceano sem violar suas obrigações e restrições.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'lanajuste_arma_01.webp',

        ]);

        Power::create([
            'id' => 183,
            'name' => 'Armadura/Escudo Leve - Lanajuste',
            'description' => 'Fornece redução de dano de corte 5.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'lanajuste_leves_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 184,
            'name' => 'Armadura Pesada - Lanajuste',
            'description' => 'Fornece redução de dano de corte 10.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'lanajuste_pesadas_01.webp',

            // TODO: we'll add a damage reduction section later.
        ]);

        Power::create([
            'id' => 185,
            'name' => 'Esotérico - Lanajuste',
            'description' => 'Quando lança uma magia que causa dano de corte, você pode rolar novamente qualquer resultado 1 na rolagem de dano.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'lanajuste_esoticos_01.webp',
            // TODO: no effects — needs a spellcasting system that doesn't

        ]);

        Power::create([
            'id' => 186,
            'name' => 'Arma - Madeira Tollon',
            'description' => 'Conta como mágica para vencer redução de dano. Além disso, habilidades ativadas ao se fazer um ataque ou usar a ação agredir têm seu custo em PM reduzido em –1.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'tollon_armas_01.webp',

            'effects' => [
                ['tag' => 'mod_pm_cost_each', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 187,
            'name' => 'Escudo/Esotérico - Madeira Tollon',
            'description' => 'Fornece +2 de resistência a magia.',
            'source' => 'item_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'tollon_esotericos_e_escudos_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 190,
            'name' => 'Arma - Mitral',
            'description' => 'Aumenta a margem de ameaça da arma em 1.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'mitral_armas_01.webp',
            'effects' => [
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 191,
            'name' => 'Armadura/Escudo Leve - Mitral',
            'description' => 'Tem sua penalidade de armadura diminuída em 2.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'mitral_leves_e_escudos_01.webp',
            'effects' => [
                ['tag' => 'mod_armor_penalty', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 192,
            'name' => 'Armadura Pesada - Mitral',
            'description' => 'Tem sua penalidade de armadura diminuída em 2. Fornece +2 na Defesa.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'mitral_pesadas_01.webp',
            'effects' => [
                ['tag' => 'mod_armor_penalty', 'op' => 'add', 'value' => -2],

                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 193,
            'name' => 'Esotérico - Mitral',
            'description' => 'Permite que você pague +2 PM ao lançar uma magia para aumentar a CD dela em +2.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'mitral_esotericos_01.webp',
            // TODO: no effects — needs a spellcasting system that doesn't

        ]);

        Power::create([
            'id' => 194,
            'name' => 'Arma - Prata',
            'description' => 'A arma causa +2 pontos de dano em espíritos e mortos-vivos. É considerada mágica para atacar criaturas incorpóreas.',
            'source' => 'item_granted',

            'usability' => 'roll_active',
            'icon_file_name' => 'prata_armas_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 195,
            'name' => 'Esotérico - Prata',
            'description' => 'Suas magias causam +2 pontos de dano em espíritos e mortos-vivos.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'prata_esotericos_01.webp',
            // TODO: no effects — needs a spellcasting system that doesn't

        ]);

        Power::create([
            'id' => 267,
            'name' => 'Pistola-Tambor',
            'description' => 'Um tambor giratório que armazena quatro munições.',
            'source' => 'item_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'pistola_tambor_01.webp',
        ]);

        Power::create([
            'id' => 269,
            'name' => 'Cano Duplo',
            'description' => 'Essa melhoria permite que a arma tenha dois canos, permitindo o carregamento de duas balas ao mesmo tempo. Cada cano requer uma ação de recarga separada. Esta melhoria só pode ser aplicada a armas que disparem balas.',
            'source' => 'item_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'dois_canos_01.webp',
        ]);

        Power::create([
            'id' => 270,
            'name' => 'Tambor',
            'description' => 'Essa evolução do cano duplo permite que até três tiros sejam armazenados em um tambor, que é girado conforme a arma dispara. Só pode ser aplicado a armas que disparem balas.',
            'source' => 'item_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'tambor_01.webp',
        ]);
    }
}
