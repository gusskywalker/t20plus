<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 14000 and 14999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
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
                ['trigger' => 'on_critical_strike', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 1],
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

        //TODO fix this when we add bardo
        Power::create([
            'id' => 18,
            'name' => 'Instrumento Musical - Matéria Vermelha',
            'description' => 'Aumenta em +1 a CD das habilidades de bardo (exceto magias) quando o usuário utiliza o instrumento.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'instrumento_musical_materia_vermelha_01.webp',
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
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

        Power::create([
            'id' => 317,
            'name' => 'Símbolo Sagrado',
            'description' => 'Se estiver vestindo ou empunhando o símbolo sagrado de um deus do qual é devoto, você recebe +1 em testes de resistência.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'simbolo_sagrado_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 318,
            'name' => 'Arco de Guerra (+FOR)',
            'description' => 'Como um arco longo, você aplica sua Força às rolagens de dano ao atacar com o arco de guerra.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arco_de_guerra_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg_attribute', 'op' => 'set', 'value' => 'attribute_str'],
            ],
        ]);

        // Same shape as Arco de Guerra's own +FOR grant above, but
        // permanent — Funda's own Força bonus isn't a self-reported
        // choice like the arco's own, it always applies.
        Power::create([
            'id' => 14000,
            'name' => 'Funda (+FOR)',
            'description' => 'Ao contrário de outras armas de disparo, você aplica seu modificador de Força a rolagens de dano com uma funda.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'hynne_funda_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg_attribute', 'op' => 'set', 'value' => 'attribute_str'],
            ],
        ]);

        Power::create([
            'id' => 348,
            'name' => 'Luneta',
            'description' => 'Este instrumento valioso consiste de um cilindro metálico com duas lentes. Fornece +5 em testes de Percepção para observar coisas em alcance longo ou além.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'luneta_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14002,
            'name' => 'Rede',
            'description' => 'A rede não causa dano nem ameaça crítico. Quando um ataque com ela acerta, o alvo fica enredado.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'rede_01.webp',
            'effects' => [
                ['trigger' => 'on_hit_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 20],
                ['tag' => 'remove_all_damage', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 14003,
            'name' => 'Corda',
            'description' => 'Pode ajudar a descer um buraco ou muro (+5 em testes de Atletismo nessas situações).',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'corda_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14004,
            'name' => 'Corda de Teia',
            'description' => 'Fornece +5 em testes de Destreza para atar nós e testes de Atletismo para escalar.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'corda_de_teia_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14005,
            'name' => 'Arpéu',
            'description' => 'Subir um muro com a ajuda de uma corda fornece +5 no teste de Atletismo.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arpeu_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14006,
            'name' => 'Barraca',
            'description' => 'Fornece +2 em testes de Sobrevivência para acampar.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'barraca_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14007,
            'name' => 'Tenda do Pântano',
            'description' => 'Fornece +2 em testes de Sobrevivência para acampar.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'tenda_do_pantano_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14008,
            'name' => 'Mapa',
            'description' => 'Fornece +5 em testes de Sobrevivência para orientar-se nessa região.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'mapa_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14009,
            'name' => 'Bússola',
            'description' => 'Quando faz um teste de Sobrevivência para orientar-se, você rola dois dados e usa o melhor resultado.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'bussola_01.webp',
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 28],
            ],
        ]);

        Power::create([
            'id' => 14010,
            'name' => 'Lupa',
            'description' => 'Quando faz um teste de Investigação para procurar usando uma lupa, você pode rolar dois dados e usar o melhor resultado.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'lupa_01.webp',
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 15],
            ],
        ]);

        Power::create([
            'id' => 14011,
            'name' => 'Mochila Discreta',
            'description' => 'Esses itens contam em sua capacidade de carga, mas você recebe +5 em testes de Ladinagem para ocultá-los (cumulativo com qualquer bônus concedido pelo próprio item).',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'mochila_discreta_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 18, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14012,
            'name' => 'Patuá',
            'description' => 'Se tiver uma devoção, você recebe resistência a magia +1.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'patua_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14013,
            'name' => 'Colar do Suplicante',
            'description' => 'Você pode gastar uma ação de movimento e uma das contas para recuperar 1 PM.',
            'source' => 'item_granted',
            'usability' => 'active',
            'icon_file_name' => 'colar_do_suplicante_01.webp',
            'action_cost' => 'movement',
            'effects' => [
                ['tag' => 'restore_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14014,
            'name' => 'Penalidade de Movimento (Armadura Pesada)',
            'description' => 'Sua armadura pesada reduz seu deslocamento em 3m.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'brunea_01.webp',
            'effects' => [
                ['tag' => 'heavy_armor_movement_penalty', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 14015,
            'name' => 'Arpão (Corpo a Corpo)',
            'description' => 'O arpão pode ser usado como arma corpo a corpo, mas com penalidade de –5 no teste de ataque.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arpao_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 14016,
            'name' => 'Tocha (Fogo)',
            'description' => 'A tocha causa 1 ponto de dano de fogo adicional.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'tocha_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 1, 'damage_type' => 'fire'],
            ],
        ]);

        Power::create([
            'id' => 14017,
            'name' => 'Matéria Vermelha (Penalidade)',
            'description' => 'Estes itens assustadores impõem ao usuário penalidade de –2 em perícias baseadas em Carisma (exceto Intimidação).',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'materia_vermelha_penalidade_01.webp',
            'effects' => [
                ['tag' => 'skill_group', 'op' => 'add', 'attribute' => 'car', 'value' => -2, 'exclude_skill_ids' => [14]],
            ],
        ]);

        Power::create([
            'id' => 14018,
            'name' => 'Condecoração Militar',
            'description' => 'No início de cada combate, você recebe uma quantidade de PV temporários, cumulativos com outros bônus de itens e com outras condecorações, igual a 3x o patamar em que a condecoração foi conquistada.',
            'source' => 'item_granted',
            'usability' => 'active',
            'icon_file_name' => 'condecoracao_militar_01.webp',
            'effects' => [
                ['tag' => 'temp_pv', 'op' => 'add', 'value' => 3],
                ['tag' => 'temp_pv', 'op' => 'add_per_patamar', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 14019,
            'name' => 'Leque',
            'description' => 'Uma vez por cena, quando faz um teste de Vontade, você pode se abanar para usar seu Carisma em vez de Sabedoria nesse teste.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'leque_01.webp',
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 29, 'value' => 'attribute_car'],
            ],
        ]);

        Power::create([
            'id' => 14020,
            'name' => 'Astrolábio',
            'description' => 'Você pode usar Conhecimento no lugar de Sobrevivência para orientar-se.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'astrolabio_01.webp',
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 28, 'value' => 'attribute_int'],
            ],
        ]);

        Power::create([
            'id' => 14021,
            'name' => 'Apanhador de Sonhos',
            'description' => 'Se estiver em posso de um apanhador de sonhos, recebe +2 em testes de resistência quando estiver dormindo.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'apanhador_de_sonhos_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14022,
            'name' => 'Emblema Religioso',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para receber os benefícios de ser treinado em Religião em um teste dessa perícia até o fim da cena.',
            'source' => 'item_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => 'emblema_religioso_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'trains', 'skill_id' => 27],
            ],
        ]);

        Power::create([
            'id' => 14023,
            'name' => 'Adaptável',
            'description' => 'Uma arma de uma mão com esta habilidade pode ser usada com as duas mãos para aumentar seu dano em um passo.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arma_adaptavel_01.webp',
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14024,
            'name' => 'Ágil',
            'description' => 'Pode ser usada com Acuidade com Arma, mesmo não sendo uma arma leve.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arma_agil_01.webp',
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 19, 'value' => 'attribute_dex'],
                ['tag' => 'mod_dmg_attribute', 'op' => 'set', 'value' => 'attribute_dex'],
            ],
        ]);

        Power::create([
            'id' => 14025,
            'name' => 'Desbalanceada',
            'description' => 'Impõe uma penalidade de -2 em testes de ataque.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arma_desbalanceada_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 14026,
            'name' => 'Ocultável',
            'description' => 'Fornece +5 em testes de Ladinagem para ocultá-la.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arma_ocultavel_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 18, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14027,
            'name' => 'Versátil',
            'description' => 'Fornece bônus em uma ou mais manobras (cumulativo com outros bônus de itens), conforme a arma.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arma_versatil_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14028,
            'name' => 'Machado de Lenha (Ignorar RD)',
            'description' => 'Sua lâmina projetada para cortar madeira rígida é capaz de ignorar 5 pontos de RD de objetos e construtos.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'machado_de_lenha_01.webp',
            'effects' => [
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14029,
            'name' => 'Azagaia (Corpo a Corpo)',
            'description' => 'Pode ser usada como arma corpo a corpo, mas você sofre uma penalidade de –5 no teste de ataque.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'azagaia_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 14030,
            'name' => 'Zarabatana (Venenos)',
            'description' => 'CD para resistir a um veneno aplicado via zarabatana aumenta em +2.',
            'source' => 'item_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'zarabatana_01.webp',
        ]);

        Power::create([
            'id' => 14031,
            'name' => 'Cinquedea (Dado Extra)',
            'description' => 'Contra uma criatura desprevenida ou que você esteja flanqueando, a cinquedea causa um dado de dano extra do mesmo tipo.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'cinquedea_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'weapon_die'],
            ],
        ]);

        Power::create([
            'id' => 14032,
            'name' => 'Garra-retrátil (Atletismo)',
            'description' => 'Essas garras fornecem +2 em testes de Atletismo para escalar.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'garra_retratil_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14033,
            'name' => 'Neko-te (Atletismo)',
            'description' => 'Essa luva com garras fornece +2 em testes de Atletismo para escalar.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'nekote_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14034,
            'name' => 'Espadim (Nobreza)',
            'description' => 'Se for treinado em Nobreza, você recebe +1 em testes de ataque e rolagens de dano com um espadim, cumulativo com outros efeitos de itens.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'espadim_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14035,
            'name' => 'Serrilheira (Diplomacia/Enganação)',
            'description' => 'A aparência agressiva da serrilheira, mesmo para uma arma, impõe –2 em Diplomacia e Enganação, cumulativo com outros efeitos de itens.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'serrilheira_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 14036,
            'name' => 'Espada de Execução (-5 Acerto)',
            'description' => 'Você sofre –5 em testes de ataque com esta arma, a menos que gaste uma ação de movimento para prepará-la (isso elimina essa penalidade em seu próximo ataque feito nesse turno).',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'espada_de_execucao_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 14037,
            'name' => 'Lança Montada (+2d8)',
            'description' => 'Quando usada numa investida montada, a lança montada causa +2d8 pontos de dano (não multiplicados em caso de acerto crítico).',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'lanca_montada_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d8'],
            ],
        ]);

        Power::create([
            'id' => 14038,
            'name' => 'Arco Longo (+FOR)',
            'description' => 'Por ter uma puxada pesada, o arco longo permite que você aplique seu modificador de Força às rolagens de dano.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arco_longo_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg_attribute', 'op' => 'set', 'value' => 'attribute_str'],
            ],
        ]);

        Power::create([
            'id' => 14039,
            'name' => 'Arco Montado (+2 Dano Montado)',
            'description' => 'Se você já possui uma habilidade que elimina a penalidade em ataques à distância devido ao balanço de sua montaria, o arco montado fornece +2 nas rolagens de dano enquanto você está montado.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arco_montado_01.webp',
            'applies_when' => ['power_id' => 11000],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14040,
            'name' => 'Açoite Finntroll (Inflige Condição)',
            'description' => 'Uma criatura atingida pelo açoite sofre –2 em testes e jogadas de dano por uma rodada (metabolismo).',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'acoite_fintroll_01.webp',
            'effects' => [
                ['trigger' => 'on_hit_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 30],
            ],
        ]);

        Power::create([
            'id' => 14041,
            'name' => 'Presa de Serpente (Crítico)',
            'description' => 'Em um acerto crítico, o dano da presa de serpente aumenta em um passo (antes de ser multiplicado).',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'presa_de_serpente_01.webp',
            'effects' => [
                ['trigger' => 'on_critical_strike', 'tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14042,
            'name' => 'Arco Élfico (+INT)',
            'description' => 'As runas do arco élfico permitem que você aplique sua Inteligência às rolagens de dano.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'arco_elfico_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg_attribute', 'op' => 'set', 'value' => 'attribute_int'],
            ],
        ]);

        Power::create([
            'id' => 14043,
            'name' => 'Balestra (+FOR)',
            'description' => 'Ao contrário de outras armas de disparo, você aplica sua Força às rolagens de dano com uma balestra.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'balestra_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg_attribute', 'op' => 'set', 'value' => 'attribute_str'],
            ],
        ]);

        Power::create([
            'id' => 14044,
            'name' => 'Bomba (Explosão 6d6)',
            'description' => 'A explosão da bomba causa 6d6 pontos de dano.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'bomba_municao_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '6d6'],
            ],
        ]);

        Power::create([
            'id' => 14045,
            'name' => 'Munição Pesada (-2 Acerto, Ignorar 5 RD)',
            'description' => 'Você sofre –2 em testes de ataque com esta munição, mas ignora 5 pontos da RD dos alvos.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'municao_pesada_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14046,
            'name' => 'Armadura Acolchoada (Fortitude)',
            'description' => 'A armadura acolchoada protege todo o corpo, fornecendo +2 em Fortitude.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_acolchoada_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14047,
            'name' => 'Armadura de Folhas (+2 PM)',
            'description' => 'Se for treinado em Sobrevivência, você recebe +2 PM com esta armadura (somente após 1 dia de uso), cumulativo com outros efeitos de itens.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_de_folhas_01.webp',
            'applies_when' => ['trained_skill_id' => 28],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14048,
            'name' => 'Armadura de Ossos (Intimidação)',
            'description' => 'Combinando um aspecto assustador e energias negativas das ossadas, essa armadura fornece +1 em Intimidação.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_de_ossos_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 1],
            ],
        ]);

        //TODO add all fear spells here when all spells are seeded
        Power::create([
            'id' => 14049,
            'name' => 'Armadura de Ossos (CD de Medo)',
            'description' => 'Combinando um aspecto assustador e energias negativas das ossadas, essa armadura fornece +1 na CD de seus efeitos de medo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_de_ossos_01.webp',
            'applies_when' => ['spell_ids' => [13, 3002]],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14050,
            'name' => 'Cota de Moedas (Diplomacia)',
            'description' => 'O cúmulo da ostentação, esta armadura fornece +2 em Diplomacia (cumulativo com melhorias da armadura).',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cota_de_moedas_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14051,
            'name' => 'Veste de Teia de Aranha (Furtividade)',
            'description' => 'Maleável e silenciosa, esta veste fornece +5 em Furtividade.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'veste_teia_de_aranha_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14052,
            'name' => 'Cota de Anéis (Deslocamento)',
            'description' => 'Esta armadura reduz seu deslocamento em –1,5m.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cota_de_aneis_01.webp',
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'add', 'value' => -1.5],
            ],
        ]);

        Power::create([
            'id' => 14053,
            'name' => 'Armadura de Chumbo (Resistência a Magia)',
            'description' => 'Esta armadura fornece resistência a magia +2, cumulativo com outros efeitos de itens.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'armadura_de_chumbo_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14054,
            'name' => 'Armadura de Justa (Luta)',
            'description' => 'A armadura de justa fornece +5 em testes para resistir a ser derrubado enquanto montado (cumulativo com outros efeitos de itens).',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'armadura_de_justa_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14055,
            'name' => 'Armadura de Justa (Desmontado)',
            'description' => 'Devido a seu peso e estrutura, a penalidade de armadura da armadura de justa aumenta em 2 se você não estiver montado.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'armadura_de_justa_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 1, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 18, 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 14056,
            'name' => 'Armadura de Pedra (Deslocamento)',
            'description' => 'Seu deslocamento é reduzido pela metade por esta armadura pesada, em vez de em 3m.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_de_pedra_01.webp',
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'multiply', 'value' => 0.5],
            ],
        ]);

        Power::create([
            'id' => 14057,
            'name' => 'Apito de Caça (Adestramento)',
            'description' => 'Este pequeno apito fornece +1 em Adestramento.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'apito_de_caca_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 2, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14058,
            'name' => 'Balança de Mercador (Diplomacia)',
            'description' => 'Usada por mercadores para avaliar objetos e medir quantidades, a balança de mercador fornece +2 em testes de Diplomacia para barganhar.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'balanca_do_mercador_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14059,
            'name' => 'Baralho Marcado (Jogatina)',
            'description' => 'Você recebe +2 em testes de Jogatina com cartas usando este baralho.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'baralho_marcado_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 17, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14060,
            'name' => 'Coleção de Livros (Conhecimento)',
            'description' => 'Esta coleção de tomos e tratados fornece +1 em Conhecimento.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'colecao_de_livros_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 6, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14061,
            'name' => 'Coleção de Livros (Guerra)',
            'description' => 'Esta coleção de tomos e tratados fornece +1 em Guerra.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'colecao_de_livros_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 12, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14062,
            'name' => 'Coleção de Livros (Misticismo)',
            'description' => 'Esta coleção de tomos e tratados fornece +1 em Misticismo.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'colecao_de_livros_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14063,
            'name' => 'Coleção de Livros (Nobreza)',
            'description' => 'Esta coleção de tomos e tratados fornece +1 em Nobreza.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'colecao_de_livros_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14064,
            'name' => 'Coleção de Livros (Religião)',
            'description' => 'Esta coleção de tomos e tratados fornece +1 em Religião.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'colecao_de_livros_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 27, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14065,
            'name' => 'Estandarte Portátil (Penalidade de Armadura)',
            'description' => 'Se estiver preso às costas, o estandarte portátil impõe uma penalidade de armadura de –2.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'estandarte_portatil_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 1, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => -2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 18, 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 14066,
            'name' => 'Sela Aprimorada Especial (Cavalgar)',
            'description' => 'Esta sela fornece +1 em Cavalgar.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sela_aprimorada_especial_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 5, 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14067,
            'name' => 'Flauta Sar-Allan (Atuação)',
            'description' => 'Concede +5 no teste de Atuação para usar uma Música de bardo, mas apenas contra criaturas reptilianas como cobras, nagahs, medusas, trogs e outras a critério do mestre.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'flauta_sar_allan_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 4, 'value' => 5],
            ],
        ]);
    }
}
