<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 2000 and 2999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
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

        Power::create([
            'id' => 2000,
            'name' => 'Arcano de Batalha',
            'description' => 'Quando lança uma magia, você soma seu atributo-chave na rolagem de dano.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'arcano_de_batalha_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_spell_dmg', 'op' => 'add', 'value' => 'key_attribute'],
            ],
        ]);
        //TODO fix this when we add ofícios
        Power::create([
            'id' => 2001,
            'name' => 'Caldeirão do Bruxo',
            'description' => 'Você pode criar poções, como se tivesse o poder geral Preparar Poção. Se tiver ambos, pode criar poções de até 5º círculo.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'caldeirao_do_bruxo_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 328],
                ['type' => 'skill_trained', 'skill_id' => 22],
            ],
        ]);

        Power::create([
            'id' => 2002,
            'name' => 'Conhecimento Mágico',
            'description' => 'Você aprende duas magias de qualquer círculo que possa lançar. Você pode escolher este poder quantas vezes quiser. <br><br>No APP, adicione as magias manualmente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'conhecimento_magico_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 2003,
            'name' => 'Envolto em Mistério',
            'description' => 'Sua aparência e postura assombrosas o permitem manipular e assustar pessoas ignorantes ou supersticiosas. O mestre define o que exatamente você pode fazer e quem se encaixa nessa descrição. Como regra geral, você recebe +5 em Enganação e Intimidação contra pessoas não treinadas em Conhecimento ou Misticismo.',
            'source' => 'class',
            'usability' => 'roll_active',
            'icon_file_name' => 'envolto_em_misterio_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'skill', 'skill_id' => 9, 'op' => 'add', 'value' => 5],
                ['tag' => 'skill', 'skill_id' => 14, 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 2004,
            'name' => 'Contramágica Aprimorada',
            'description' => 'Uma vez por rodada, você pode fazer uma contramágica como uma reação.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'contramagica_parimorada_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 2005,
            'name' => 'Escriba Arcano',
            'description' => 'Você pode aprender magias copiando os textos de pergaminhos e grimórios de outros magos. Aprender uma magia dessa forma exige um dia de trabalho e T$ 250 em matérias-primas por PM necessário para lançar a magia. Assim, aprender uma magia de 3º círculo (6 PM) exige 6 dias de trabalho e o gasto de T$ 1.500. <br><br>No APP, desconte os Tibares manualmente quando finalizar o processo.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'escriba_arcano_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 330],
                ['type' => 'skill_trained', 'skill_id' => 22],
            ],
        ]);

        // Especialista em Escola — one power per school instead of a single
        // power with an open player choice, same "no bespoke picker"
        // reasoning as Aumentar Atributo's own per-attribute split.
        Power::create([
            'id' => 2006,
            'name' => 'Especialista em Escola (Abjuração)',
            'description' => 'A CD para resistir a suas magias de Abjuração aumenta em +2.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especialista_abjuracao_01.webp',
            'applies_when' => ['spell_schools' => ['abjuracao']],
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [328, 330]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2007,
            'name' => 'Especialista em Escola (Adivinhação)',
            'description' => 'A CD para resistir a suas magias de Adivinhação aumenta em +2.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especialista_adivinhacao_01.webp',
            'applies_when' => ['spell_schools' => ['adivinhacao']],
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [328, 330]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2008,
            'name' => 'Especialista em Escola (Convocação)',
            'description' => 'A CD para resistir a suas magias de Convocação aumenta em +2.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especialista_convocacao_01.webp',
            'applies_when' => ['spell_schools' => ['convocacao']],
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [328, 330]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2009,
            'name' => 'Especialista em Escola (Encantamento)',
            'description' => 'A CD para resistir a suas magias de Encantamento aumenta em +2.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especialista_encantamento_01.webp',
            'applies_when' => ['spell_schools' => ['encantamento']],
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [328, 330]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2010,
            'name' => 'Especialista em Escola (Evocação)',
            'description' => 'A CD para resistir a suas magias de Evocação aumenta em +2.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especialista_evocacao_01.webp',
            'applies_when' => ['spell_schools' => ['evocacao']],
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [328, 330]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2011,
            'name' => 'Especialista em Escola (Ilusão)',
            'description' => 'A CD para resistir a suas magias de Ilusão aumenta em +2.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especialista_ilusao_01.webp',
            'applies_when' => ['spell_schools' => ['ilusao']],
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [328, 330]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2012,
            'name' => 'Especialista em Escola (Necromancia)',
            'description' => 'A CD para resistir a suas magias de Necromancia aumenta em +2.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especialista_necromancia_01.webp',
            'applies_when' => ['spell_schools' => ['necromancia']],
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [328, 330]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2013,
            'name' => 'Especialista em Escola (Transmutação)',
            'description' => 'A CD para resistir a suas magias de Transmutação aumenta em +2.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'especialista_transmutacao_01.webp',
            'applies_when' => ['spell_schools' => ['transmutacao']],
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [328, 330]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        // Familiar — one power per familiar option, same "no bespoke
        // picker" reasoning as Aumentar Atributo/Especialista em Escola.
        Power::create([
            'id' => 2014,
            'name' => "Familiar (Aquin'ne)",
            'description' => "Um aquin'ne familiar concede deslocamento de natação 9m e permite lançar magias e respirar debaixo d'água.",
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_aquinne_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 2015,
            'name' => 'Familiar (Asa-Assassina)',
            'description' => 'Permite que você gaste 1 PM quando causa dano de corte ou perfuração a uma criatura para deixá-la sangrando. <br><br>No APP, use o poder (gasta seu PM) e informe o mestre da condição causada.',
            'source' => 'class',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'familiar_asa_de_aco_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 2016,
            'name' => 'Familiar (Borboleta)',
            'description' => 'A CD dos testes de Vontade para resistir a suas magias aumenta em +1.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_borboleta_01.webp',
            'applies_when' => ['spell_resistances' => ['vontade']],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 2017,
            'name' => 'Familiar (Chibi-Kabuto)',
            'description' => 'Aumenta em +1 o bônus na Defesa que você recebe por suas magias.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_chibi_kabuto_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_spell_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 2018,
            'name' => 'Familiar (Cobra)',
            'description' => 'A CD dos testes de Fortitude para resistir a suas magias aumenta em +1.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_cobra_01.webp',
            'applies_when' => ['spell_resistances' => ['fortitude']],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 2019,
            'name' => 'Familiar (Coruja)',
            'description' => 'Quando lança uma magia com alcance de toque, você pode pagar 1 PM para aumentar seu alcance para curto.',
            'source' => 'class',
            'usability' => 'spell_enhancement',
            'pm_cost' => 1,
            'icon_file_name' => 'familiar_coruja_01.webp',
            'applies_when' => ['spell_ranges' => ['toque']],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 2020,
            'name' => 'Familiar (Diabrete)',
            'description' => 'Um diabrete fornece +1 PM para gastar em aprimoramentos sempre que você lança uma magia de ilusão ou veneno.',
            'source' => 'class',
            'usability' => 'vessel',
            'icon_file_name' => 'familiar_diabrete_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 2021],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 2022],
            ],
        ]);

        Power::create([
            'id' => 2021,
            'name' => 'Familiar (Diabrete) (Ilusão)',
            'description' => 'Redução de custo em PM de Familiar (Diabrete) — magias de ilusão.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_diabrete_01.webp',
            'applies_when' => ['spell_schools' => ['ilusao']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 2022,
            'name' => 'Familiar (Diabrete) (Veneno)',
            'description' => 'Redução de custo em PM de Familiar (Diabrete) — magias de veneno.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_diabrete_01.webp',
            'applies_when' => ['spell_damage_types' => ['poison']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 2023,
            'name' => 'Familiar (Dragão)',
            'description' => 'Suas magias que compartilhem o tipo de dano do sopro do dragão têm a CD aumentada em +2 e custam -1 PM (cumulativo com outras reduções).',
            'source' => 'class',
            'usability' => 'spell_enhancement',
            'pm_cost' => -1,
            'icon_file_name' => 'familiar_dragao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2024,
            'name' => 'Familiar (Falcão)',
            'description' => 'Você não pode ser surpreendido e nunca fica desprevenido.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_falcao_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'blocks_condition', 'op' => 'grant', 'condition_id' => 21],
                ['tag' => 'blocks_condition', 'op' => 'grant', 'condition_id' => 3],
            ],
        ]);

        Power::create([
            'id' => 2025,
            'name' => 'Familiar (Fuinha)',
            'description' => 'Você recebe +2 em Iniciativa e Investigação.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_fuinha_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2026,
            'name' => 'Familiar (Gato)',
            'description' => 'Você recebe visão no escuro e +2 em Furtividade.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_gato_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 2027,
            'name' => 'Familiar (Homúnculo)',
            'description' => 'Fornece +1 PM para gastar em aprimoramentos sempre que você lança uma magia de transmutação ou veneno.',
            'source' => 'class',
            'usability' => 'vessel',
            'icon_file_name' => 'familiar_homunculo_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 2028],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 2029],
            ],
        ]);

        Power::create([
            'id' => 2028,
            'name' => 'Familiar (Homúnculo) (Transmutação)',
            'description' => 'Redução de custo em PM de Familiar (Homúnculo) — magias de transmutação.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_homunculo_01.webp',
            'applies_when' => ['spell_schools' => ['transmutacao']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 2029,
            'name' => 'Familiar (Homúnculo) (Veneno)',
            'description' => 'Redução de custo em PM de Familiar (Homúnculo) — magias de veneno.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_homunculo_01.webp',
            'applies_when' => ['spell_damage_types' => ['poison']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 2030,
            'name' => 'Familiar (Lagarto)',
            'description' => 'A CD dos testes de Reflexos para resistir a suas magias aumenta em +1.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_lagarato_01.webp',
            'applies_when' => ['spell_resistances' => ['reflexos']],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 2031,
            'name' => 'Familiar (Macaco)',
            'description' => 'Uma vez por rodada, você pode usar seu familiar para sacar ou guardar um item, ou para pegar um item solto Pequeno ou menor (1 espaço ou menos) em alcance curto e que ele consiga alcançar.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_macaco_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 2032,
            'name' => 'Familiar (Morcego)',
            'description' => 'Você adquire percepção às cegas em alcance curto.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'famliar_morcego_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 2033,
            'name' => 'Familiar (Pakk)',
            'description' => 'Permite que você lance Explosão de Chamas. Caso aprenda essa magia, seu custo diminui em -1 PM.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_pakk_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'add_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 19],
            ],
        ]);

        Power::create([
            'id' => 2034,
            'name' => 'Familiar (Papagaio Zumbi)',
            'description' => 'Aumenta em +1 a CD dos testes para resistir a suas magias de necromancia e concede +2 em Pilotagem.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_schools' => ['necromancia']],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [3], 'min_level' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 24, 'value' => 2],
            ],
        ]);
    }
}
