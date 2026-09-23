<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 16000 and 16999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class RaceGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 271,
            'name' => 'Entre Dois Mundos',
            'description' => 'Você recebe +1 em perícias baseadas em Carisma.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'entre_dois_mundos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [22]],
            ],
            'effects' => [
                ['tag' => 'skill_group', 'op' => 'add', 'attribute' => 'car', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 272,
            'name' => 'Sangue Élfico',
            'description' => 'Você recebe visão na penumbra e +1 ponto de mana a cada nível ímpar (incluindo o 1º). Além disso, é considerado um elfo para efeitos relacionados a raça.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sangue_elfico_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [22]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add_per_level', 'value' => 1, 'per_character_level' => 2],
            ],
        ]);

        Power::create([
            'id' => 273,
            'name' => 'Ambição Herdada',
            'description' => 'Você recebe um poder geral ou poder único de origem a sua escolha.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'ambicao_herdada_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [22]],
            ],
        ]);

        Power::create([
            'id' => 345,
            'name' => 'Multiplicidade',
            'description' => 'Você é capaz de assumir diferentes formas e alterar a sua própria aparência. Isso inclui altura, peso, tom de pele, cor de cabelo, timbre de voz etc. Você não recebe novas habilidades (você pode ficar parecido com outra raça, mas não ganhará as habilidades dela), nem é capaz de alterar a aparência dos equipamentos que esteja vestindo ou carregando. Você recebe +2 em Diplomacia e Enganação.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'multiplicidade_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [56]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2, 'stack_group' => 'duplo_social'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 2, 'stack_group' => 'duplo_social'],
            ],
        ]);

        Power::create([
            'id' => 346,
            'name' => 'Arte do Disfarce',
            'description' => 'Você pode alterar-se para formas específicas a fim de conseguir vantagens pontuais, como impressionar alguém, assumir outra identidade ou se mesclar na multidão. Você pode gastar 2 PM para receber +10 em testes de Diplomacia para mudar atitude, em testes de Enganação para disfarce ou testes de Furtividade para esconder-se até o final da cena (esse bônus não se acumula com o bônus oferecido pelo poder Multiplicidade).',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'pm_cost' => 2,
            'icon_file_name' => 'arte_do_disfarce_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [56]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 10, 'stack_group' => 'duplo_social'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 10, 'stack_group' => 'duplo_social'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 347,
            'name' => 'Natureza Flexível',
            'description' => 'Você recebe +5 em testes de resistência contra efeitos de paralisia.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'natureza_flexivel_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [56]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5],
            ],
        ]);

        // Shared across many races directly via race_ids (any one
        // qualifies) 
        Power::create([
            'id' => 16000,
            'name' => 'Visão no Escuro',
            'description' => 'Você enxerga no escuro total perfeitamente dentro do alcance padrão (geralmente enxergando formas e cores atenuadas, tratado como luz fraca/penumbra).',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'visao_no_escuro_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1,12,21,42,48,49,52,58,2,9,13,14,41,28,35,59,51,55,23]],
            ],
        ]);

        Power::create([
            'id' => 16011,
            'name' => 'Visão na Penumbra',
            'description' => 'Você enxerga em escuridão leve em alcance curto (exceto mágica). Ignora camuflagem leve por esse tipo de escuridão.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'visao_na_penumbra_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [22,7,47,8,34,27,30,32,37,38,39,40,43,50,53,54,6,45]],
            ],
        ]);

        Power::create([
            'id' => 16012,
            'name' => 'Faro',
            'description' => 'Contra inimigos que não possa ver, a criatura não fica desprevenida e camuflagem total lhe causa apenas 20% de chance de falha em alcance curto.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'faro_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25,2,11,24,31,34,26,33,36,39,53,54]],
            ],
        ]);

        Power::create([
            'id' => 16002,
            'name' => 'Conhecimento das Rochas',
            'description' => 'Você recebe visão no escuro e +2 em testes de Percepção e Sobrevivência realizados no subterrâneo.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'conhecimento_das_rochas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16003,
            'name' => 'Devagar e Sempre',
            'description' => 'Seu deslocamento é 6m (em vez de 9m). Porém, seu deslocamento nunca é reduzido por uso de armadura ou excesso de carga.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'devagar_e_sempre_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'waive_heavy_armor_movement_penalty', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 16004,
            'name' => 'Duro como Pedra',
            'description' => 'Você recebe +3 pontos de vida no 1º nível e +1 por nível seguinte.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'duro_como_pedra_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pv', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_max_pv', 'op' => 'add_per_level', 'value' => 1, 'per_character_level' => 1],
            ],
        ]);

        //TODO add all weapon ids for machados/martelos/marretas/picaretas when items are fully added
        Power::create([
            'id' => 16005,
            'name' => 'Tradição de Heredrimm',
            'description' => 'Você é perito nas armas tradicionais anãs, seja por ter treinado com elas, seja por usá-las como ferramentas de ofício. Para você, todos os machados, martelos, marretas e picaretas são armas simples. Você recebe +2 em ataques com essas armas.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'tradicao_heredrimm_01.webp',
            'applies_when' => ['weapon_ids' => [3]],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'waive_weapon_proficiency', 'op' => 'grant', 'weapon_ids' => [3]],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16006,
            'name' => 'Amiga das Plantas',
            'description' => 'Você pode lançar a magia Controlar Plantas (atributo-chave Sabedoria). Caso aprenda novamente essa magia, seu custo diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'amiga_das_plantas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [5]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 1000],
            ],
        ]);

        Power::create([
            'id' => 16007,
            'name' => 'Armadura de Allihanna',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para transformar sua pele em casca de árvore, recebendo +2 na Defesa até o fim da cena.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => 'armadura_de_alihanna_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [5]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16008,
            'name' => 'Empatia Selvagem',
            'description' => 'Você pode se comunicar com animais através de linguagem corporal e vocalizações. Você pode usar Adestramento para mudar atitude e persuasão com animais. Caso receba esta habilidade novamente, recebe +2 em testes de Adestramento.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'empatia_selvagem_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [5]],
            ],
            'effects' => [
                ['trigger' => 'on_other_sources_satisfied', 'tag' => 'skill', 'op' => 'add', 'skill_id' => 2, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16009,
            'name' => 'Graça de Glórienn',
            'description' => 'Seu deslocamento é 12m (em vez de 9m).',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'graca_de_glorienn_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [7]],
            ],
        ]);

        Power::create([
            'id' => 16010,
            'name' => 'Sangue Mágico',
            'description' => 'Você recebe +1 ponto de mana por nível.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sangue_magico_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [7]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add_per_level', 'value' => 1, 'per_character_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 16013,
            'name' => 'Sentidos Élficos',
            'description' => 'Você recebe visão na penumbra e +2 em Misticismo e Percepção.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sentidos_elficos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [7]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16014,
            'name' => 'Engenhoso',
            'description' => 'Você não sofre penalidades em testes de perícia por não usar ferramentas. Se usar as ferramentas necessárias, recebe +2 no teste de perícia.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'engenhoso_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
            'effects' => [
                ['tag' => 'waive_tool_absent_penalty', 'op' => 'grant'],
                ['tag' => 'tool_present', 'op' => 'add', 'skill_id' => 22, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16015,
            'name' => 'Espelunqueiro',
            'description' => 'Você recebe visão no escuro e deslocamento de escalada igual ao seu deslocamento terrestre.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'espelunqueiro_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
        ]);

        Power::create([
            'id' => 16016,
            'name' => 'Peste Esguia',
            'description' => 'Seu tamanho é Pequeno, mas seu deslocamento se mantém 9m.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'peste_esguia_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
        ]);

        Power::create([
            'id' => 16017,
            'name' => 'Rato das Ruas',
            'description' => 'Você recebe +2 em Fortitude e sua recuperação de PV e PM nunca é inferior ao seu nível.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'rato_das_ruas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16018],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16019],
            ],
        ]);

        Power::create([
            'id' => 16018,
            'name' => 'Rato das Ruas (Fortitude)',
            'description' => 'Você recebe +2 em Fortitude.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'rato_das_ruas_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16019,
            'name' => 'Rato das Ruas (Descanso)',
            'description' => 'Sua recuperação de PV e PM nunca é inferior ao seu nível.',
            'source' => 'power_granted',
            'usability' => 'resting',
            'icon_file_name' => 'rato_das_ruas_descanso_01.webp',
            'effects' => [
                ['tag' => 'resting_floor_pv', 'op' => 'set', 'value' => 'character_level'],
                ['tag' => 'resting_floor_pm', 'op' => 'set', 'value' => 'character_level'],
            ],
        ]);

        Power::create([
            'id' => 16020,
            'name' => 'Versátil',
            'description' => 'Você se torna treinado em duas perícias a sua escolha (não precisam ser da sua classe). Você pode trocar uma dessas perícias por um poder geral a sua escolha.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'humano_versatil_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [15]],
            ],
        ]);

        // weapon_any (purpose 'thrown' OR weapon_id 11/Funda) — Funda is
        // its own purpose 'fired', same category as bows/firearms, so
        // 'fired' alone would wrongly include those too. See tag-library.md.
        Power::create([
            'id' => 16021,
            'name' => 'Arremessador',
            'description' => 'Quando faz um ataque à distância com uma funda ou uma arma de arremesso, seu dano aumenta em um passo.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arremessador_01.webp',
            'applies_when' => [
                'weapon_any' => [
                    ['purpose' => 'thrown'],
                    ['weapon_id' => 11],
                ],
            ],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [16]],
            ],
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
        ]);

        // The Pequeno size / 6m deslocamento are already Hynne's own
        // base_size/base_movement (RaceSeeder) — nothing to hook up for
        // that clause. Vessel since the other two clauses need different
        // usability (passive skill bonus vs. a toggleable attribute swap).
        Power::create([
            'id' => 16022,
            'name' => 'Pequeno e Rechonchudo',
            'description' => 'Seu tamanho é Pequeno e seu deslocamento é 6m. Você recebe +2 em Enganação e pode usar Destreza como atributo-chave de Atletismo (em vez de Força).',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'pequeno_e_rechonchudo_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [16]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16023],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16024],
            ],
        ]);

        Power::create([
            'id' => 16023,
            'name' => 'Pequeno e Rechonchudo (Enganação)',
            'description' => 'Você recebe +2 em Enganação.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'pequeno_e_rechonchudo_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16024,
            'name' => 'Pequeno e Rechonchudo (Atletismo)',
            'description' => 'Você pode usar Destreza como atributo-chave de Atletismo (em vez de Força). <br><br>No APP, ative quando quiser realizar a troca. Desative quando quiser voltar ao normal.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => 'pequeno_e_rechonchudo_01.webp',
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 3, 'value' => 'dex'],
            ],
        ]);

        Power::create([
            'id' => 16025,
            'name' => 'Sorte Salvadora',
            'description' => 'Quando faz um teste de resistência, você pode gastar 1 PM para rolar este teste novamente. <br><br>No APP, use esse poder para gastar o PM. Role a resistência normalmente.',
            'source' => 'race_granted',
            'usability' => 'active',
            'pm_cost' => 1,
            'icon_file_name' => 'sorte_salvadora_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [16]],
            ],
        ]);

        Power::create([
            'id' => 16026,
            'name' => 'Híbrido',
            'description' => 'Você se torna treinado em uma perícia a sua escolha (não precisa ser da sua classe).',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'hibrido_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [19]],
            ],
            'effects' => [
                ['tag' => 'free_skills_choice', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16027,
            'name' => 'Engenhosidade',
            'description' => 'Quando faz um teste de perícia, você pode gastar 2 PM para somar sua Inteligência no teste. Você não pode usar esta habilidade em testes de ataque. Caso receba esta habilidade novamente, seu custo é reduzido em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'pm_cost' => 2,
            'icon_file_name' => 'engenhosidade_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [19]],
            ],
            'effects' => [
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 'int'],
                ['trigger' => 'on_other_sources_satisfied', 'tag' => 'mod_own_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 16028,
            'name' => 'Ossos Frágeis',
            'description' => 'Você sofre 1 ponto de dano adicional por dado de dano de impacto. Por exemplo, se for atingido por uma clava (dano 1d6), sofre 1d6+1 pontos de dano. Se cair de 3m de altura (dano 2d6), sofre 2d6+2 pontos de dano.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'ossos_frageis_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [19]],
            ],
        ]);

        // TODO: fix this when we implement oficios
        Power::create([
            'id' => 16029,
            'name' => 'Vanguardista',
            'description' => 'Você recebe proficiência em armas de fogo e +2 em Ofício (um qualquer, a sua escolha).',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'vanguardista_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [19]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 41],
            ],
        ]);

        Power::create([
            'id' => 16030,
            'name' => 'Cria da Tormenta',
            'description' => 'Você é uma criatura do tipo monstro e recebe +5 em testes de resistência contra efeitos causados por lefeu e pela Tormenta.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'cria_da_tormenta_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [20, 17]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 16031,
            'name' => 'Deformidade',
            'description' => 'Você recebe +2 em duas perícias a sua escolha. Cada um desses bônus conta como um poder da Tormenta (Exceto para perda de Carisma). Você pode trocar um desses bônus por um poder da Tormenta a sua escolha (ele também não conta para perda de Carisma).',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'deformidade_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [20]],
            ],
        ]);

        Power::create([
            'id' => 16032,
            'name' => 'Cria de Megalokk',
            'description' => 'Você é uma criatura do tipo monstro e recebe visão no escuro.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cria_de_megalokk_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [21]],
            ],
        ]);

        Power::create([
            'id' => 16033,
            'name' => 'Natureza Venenosa',
            'description' => 'Você recebe resistência a veneno +5 e pode gastar uma ação de movimento e 1 PM para envenenar uma arma que esteja usando. A arma causa perda de 1d12 pontos de vida. O veneno dura até você acertar um ataque ou até o fim da cena (o que acontecer primeiro).',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'natureza_venenosa_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [21]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16034],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16035],
            ],
        ]);

        Power::create([
            'id' => 16034,
            'name' => 'Natureza Venenosa (Resistência a Veneno)',
            'description' => 'Você recebe resistência a veneno +5.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'natureza_venenosa_01.webp',
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'poison'],
            ],
        ]);

        Power::create([
            'id' => 16035,
            'name' => 'Natureza Venenosa',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para envenenar uma arma que esteja usando. A arma causa perda de 1d12 pontos de vida. O veneno dura até você acertar um ataque ou até o fim da cena (o que acontecer primeiro). <br><br>No APP, selecione a arma e aplique o veneno. Se a cena acabar, remova-o manualmente no mesmo lugar onde aplicou.',
            'source' => 'power_granted',
            'usability' => 'item_enhancer',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => 'natureza_venenosa_01.webp',
            'applies_when' => ['categories' => ['weapon']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d12', 'damage_type' => 'poison'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16036,
            'name' => 'Olhar Atordoante',
            'description' => 'Você pode lançar a magia Olhar Atordoante (atributo-chave Carisma).',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'olhar_atordoante_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [21]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 3000],
            ],
        ]);

        Power::create([
            'id' => 16037,
            'name' => 'Chifres',
            'description' => 'Você possui uma arma natural de chifres (dano 1d6, crítico x2, perfuração). Uma vez por rodada, quando usa a ação agredir para atacar com outra arma, pode gastar 1 PM para fazer um ataque corpo a corpo extra com os chifres.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'chifre_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25,4,26]],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1000],
            ],
        ]);

        Power::create([
            'id' => 16038,
            'name' => 'Couro Rígido',
            'description' => 'Você recebe +1 na Defesa.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'couro_rigido_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16039,
            'name' => 'Medo de Altura',
            'description' => 'Se estiver adjacente a uma queda de 3m ou mais de altura (como um buraco ou penhasco) você fica abalado.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'medo_de_altura_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25,3,4]],
            ],
        ]);

        Power::create([
            'id' => 16040,
            'name' => 'Armadura Óssea',
            'description' => 'Você recebe resistência a corte, frio e perfuração 5.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'armadura_ossea_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [42]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'slashing'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'cold'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'piercing'],
            ],
        ]);

        Power::create([
            'id' => 16041,
            'name' => 'Memória Póstuma',
            'description' => 'Você se torna treinado em uma perícia (não precisa ser da sua classe) ou recebe um poder geral a sua escolha. Como alternativa, você pode ser um osteon de outra raça humanoide que não humano. Neste caso, você ganha uma habilidade dessa raça a sua escolha. Se a raça era de tamanho diferente de Médio, você também possui sua categoria de tamanho.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'memoria_postuma_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [42]],
            ],
        ]);

        Power::create([
            'id' => 16042,
            'name' => 'Natureza Esquelética',
            'description' => 'Você é uma criatura do tipo morto-vivo. Recebe visão no escuro e imunidade a efeitos de cansaço, metabólicos, de trevas e de veneno. Além disso, não precisa respirar, alimentar-se ou dormir. Por fim, habilidades mágicas de cura causam dano a você e você não se beneficia de itens da categoria alimentação, mas dano de trevas recupera seus PV.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'natureza_esqueletica_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [42, 57]],
            ],
            'effects' => [
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'poison'],
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'darkness'],
                ['tag' => 'condition_type_immunity', 'op' => 'grant', 'value' => 'tired'],
                ['tag' => 'condition_type_immunity', 'op' => 'grant', 'value' => 'metabolism'],
                ['tag' => 'change_heal_to_damage', 'op' => 'grant'],
                ['tag' => 'change_damage_to_heal', 'op' => 'grant', 'value' => 'darkness'],
            ],
        ]);

        Power::create([
            'id' => 16043,
            'name' => 'Preço da Não Vida',
            'description' => 'Você precisa passar oito horas sob a luz de estrelas ou no subterrâneo. Se fizer isso, recupera PV e PM por descanso em condições normais (osteon não são afetados por condições boas ou ruins de descanso). Caso contrário, sofre os efeitos de fome.',
            'source' => 'race_granted',
            'usability' => 'resting',
            'icon_file_name' => 'preco_da_nao_vida_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [42, 57]],
            ],
            'effects' => [
                ['tag' => 'resting', 'op' => 'set', 'value' => 0],
            ],
        ]);

        Power::create([
            'id' => 16044,
            'name' => 'Abraço Gélido',
            'description' => 'Você recebe +2 em testes para agarrar. Além disso, seus ataques desarmados e com armas naturais causam 2 pontos de dano de frio extras.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'abraco_gelido_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [57]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16045],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16046],
            ],
        ]);

        Power::create([
            'id' => 16045,
            'name' => 'Abraço Gélido (Agarrar)',
            'description' => 'Você recebe +2 em testes para agarrar.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'abraco_gelido_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16046,
            'name' => 'Abraço Gélido (Dano)',
            'description' => 'Seus ataques desarmados e com armas naturais causam 2 pontos de dano de frio extras.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'abraco_gelido_01.webp',
            'applies_when' => [
                'weapon_any' => [
                    ['weapon_id' => 4],
                    ['grip' => 'natural'],
                ],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2, 'damage_type' => 'cold'],
            ],
        ]);

        Power::create([
            'id' => 16047,
            'name' => 'Esquife de Gelo',
            'description' => 'Você recebe redução de corte e perfuração 5 e redução de frio 10. Entretanto, você sofre 1 ponto de dano adicional por dado de dano de fogo.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'esquife_de_gelo_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [57]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'slashing'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'piercing'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'cold'],
                ['tag' => 'damage_reduction', 'op' => 'per_die', 'value' => -1, 'damage_reduction_type' => 'fire'],
            ],
        ]);

        Power::create([
            'id' => 16048,
            'name' => 'Desejos',
            'description' => 'Se lançar uma magia que alguém tenha pedido desde seu último turno, o custo da magia diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'spell_enhancement',
            'pm_cost' => -1,
            'icon_file_name' => 'desejos_qareen_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
        ]);

        Power::create([
            'id' => 16049,
            'name' => 'Qareen de Água',
            'description' => 'Você recebe redução de frio 10.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'qareen_agua_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'cold'],
            ],
        ]);

        Power::create([
            'id' => 16050,
            'name' => 'Qareen do Ar',
            'description' => 'Você recebe redução de eletricidade 10.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'qareen_ar_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'electricity'],
            ],
        ]);

        Power::create([
            'id' => 16051,
            'name' => 'Qareen de Fogo',
            'description' => 'Você recebe redução de fogo 10.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'qareen_fogo_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'fire'],
            ],
        ]);

        Power::create([
            'id' => 16052,
            'name' => 'Qareen da Terra',
            'description' => 'Você recebe redução de ácido 10.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'qareen_terra_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'acid'],
            ],
        ]);

        Power::create([
            'id' => 16053,
            'name' => 'Qareen da Luz',
            'description' => 'Você recebe redução de luz 10.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'qareen_luz_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'light'],
            ],
        ]);

        Power::create([
            'id' => 16054,
            'name' => 'Qareen das Trevas',
            'description' => 'Você recebe redução de trevas 10.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'qareen_trevas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'darkness'],
            ],
        ]);

        Power::create([
            'id' => 16055,
            'name' => 'Tatuagem Mística',
            'description' => 'Você pode lançar uma magia de 1º círculo a sua escolha (atributo-chave Carisma). Caso aprenda novamente essa magia, seu custo diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'tatuagem_mistica_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 16056,
            'name' => 'Canção dos Mares',
            'description' => 'Você pode lançar duas das magias a seguir: Amedrontar, Comando, Despedaçar, Enfeitiçar, Hipnotismo ou Sono (atributo-chave Carisma). Caso aprenda novamente uma dessas magias, seu custo diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cancoes_dos_mares_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [46]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);
        // TODO: add azagaia/lança weapon ids to applies_when.weapon_ids once they're seeded — only tridente (12) exists so far.
        Power::create([
            'id' => 16057,
            'name' => 'Mestre do Tridente',
            'description' => 'Para você, o tridente é uma arma simples. Além disso, você recebe +2 em rolagens de dano com azagaias, lanças e tridentes.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'mestre_do_tridente_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [46]],
            ],
            'applies_when' => ['weapon_ids' => [12]],
            'effects' => [
                ['tag' => 'waive_weapon_proficiency', 'op' => 'grant', 'weapon_ids' => [12]],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16058,
            'name' => 'Transformação Anfíbia',
            'description' => 'Você pode respirar debaixo d\'água e possui uma cauda que fornece deslocamento de natação 12m. Quando fora d\'água, sua cauda desaparece e dá lugar a pernas (deslocamento 9m). Se permanecer mais de um dia sem contato com água, você não recupera PM com descanso até voltar para a água (ou, pelo menos, tomar um bom banho!).',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'transformacao_anfibia_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [46]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16059],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16060],
            ],
        ]);

        Power::create([
            'id' => 16059,
            'name' => 'Transformação Anfíbia',
            'description' => 'Você pode respirar debaixo d\'água e possui uma cauda que fornece deslocamento de natação 12m. Quando fora d\'água, sua cauda desaparece e dá lugar a pernas (deslocamento 9m).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'transformacao_anfibia_01.webp',
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 12],
            ],
        ]);

        Power::create([
            'id' => 16060,
            'name' => 'Transformação Anfíbia (Descanso)',
            'description' => 'Se permanecer mais de um dia sem contato com água, você não recupera PM com descanso até voltar para a água (ou, pelo menos, tomar um bom banho!).',
            'source' => 'power_granted',
            'usability' => 'resting',
            'icon_file_name' => 'anfibio_descanso_01.webp',
            'effects' => [
                ['tag' => 'resting_pm_recovery', 'op' => 'set', 'value' => 0],
            ],
        ]);

        Power::create([
            'id' => 16061,
            'name' => 'Asas de Borboleta',
            'description' => 'Seu tamanho é Minúsculo. Você pode flutuar a 1,5m do chão com deslocamento 9m. Isso permite que você ignore terreno difícil e o torna imune a dano por queda (a menos que esteja inconsciente). Você pode gastar 1 PM por rodada para voar com deslocamento de 12m.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'turn',
            'pm_cost' => 1,
            'icon_file_name' => 'asas_de_borobleta_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [47]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 12],
            ],
        ]);

        Power::create([
            'id' => 16062,
            'name' => 'Espírito da Natureza',
            'description' => 'Você é uma criatura do tipo espírito, recebe visão na penumbra e pode falar com animais livremente.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'espirito_da_natureza_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [47]],
            ],
        ]);

        Power::create([
            'id' => 16063,
            'name' => 'Magia das Fadas',
            'description' => 'Você pode lançar duas das magias a seguir (atributo-chave Carisma): Criar Ilusão, Enfeitiçar, Luz (como uma magia arcana) e Sono. Caso aprenda novamente uma dessas magias, seu custo diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'magia_das_fadas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [47]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 16064,
            'name' => 'Herança Divina',
            'description' => 'Você é uma criatura do tipo espírito e recebe visão no escuro.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'heranca_divina_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48,49]],
            ],
        ]);

        Power::create([
            'id' => 16065,
            'name' => 'Luz Sagrada',
            'description' => 'Você recebe +2 em Diplomacia e Intuição. Além disso, pode lançar Luz (como uma magia divina; atributo-chave Carisma). Caso aprenda novamente esta magia, o custo para lançá-la diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'luz_sagrada_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 2002],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 16066,
            'name' => 'Sombras Profanas',
            'description' => 'Você recebe +2 em Enganação e Furtividade. Além disso, pode lançar a magia Escuridão (como uma magia divina, atributo-chave Inteligência). Caso aprenda novamente esta magia, o custo para lançá-la diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sombras_profanas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 2003],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 16067,
            'name' => 'Mau Cheiro',
            'description' => 'Você pode lançar a magia Mau Cheiro.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'mau_cheiro_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [52,58]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 3001],
            ],
        ]);

        Power::create([
            'id' => 16068,
            'name' => 'Mordida',
            'description' => 'Você possui uma arma natural de mordida (dano 1d6, crítico x2, perfuração). Uma vez por rodada, quando usa a ação agredir para atacar com outra arma, pode gastar 1 PM para fazer um ataque corpo a corpo extra com a mordida.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'mordida_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [52,58,11,31,29,32,33,36,39]],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1001],
            ],
        ]);

        Power::create([
            'id' => 16069,
            'name' => 'Reptiliano',
            'description' => 'Você é uma criatura do tipo monstro e recebe visão no escuro, +1 na Defesa e, se estiver sem armadura ou roupas pesadas, +5 em Furtividade.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'reptiliano_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [52]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16070],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16071],
            ],
        ]);

        Power::create([
            'id' => 16070,
            'name' => 'Reptiliano',
            'description' => 'Você é uma criatura do tipo monstro e recebe visão no escuro e +1 na Defesa.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'reptiliano_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16071,
            'name' => 'Reptiliano (Furtividade)',
            'description' => 'Se estiver sem armadura ou roupas pesadas, recebe +5 em Furtividade.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'reptiliano_furtividade_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 16072,
            'name' => 'Sangue Frio',
            'description' => 'Você sofre 1 ponto de dano adicional por cada dado de dano de frio.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sangue_frio_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [52,58]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'per_die', 'value' => -1, 'damage_reduction_type' => 'cold'],
            ],
        ]);

        Power::create([
            'id' => 16073,
            'name' => 'Quase Anão',
            'description' => 'Você é uma criatura do tipo monstro e recebe visão no escuro e +1 PV por nível. Além disso, seu deslocamento é 6m (em vez de 9m), mas não é reduzido por uso de armadura ou excesso de carga.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'quase_anao_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [58]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pv', 'op' => 'add_per_level', 'value' => 1, 'per_character_level' => 1],
                ['tag' => 'waive_heavy_armor_movement_penalty', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 16074,
            'name' => 'Bugbear',
            'description' => 'Você recebe o poder geral Empunhadura Poderosa.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'bugbear_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 261],
            ],
        ]);

        Power::create([
            'id' => 16075,
            'name' => 'Saborear Pavor',
            'description' => 'Você pode usar Força como atributo-chave de Intimidação (em vez de Carisma). Além disso, se estiver em alcance curto de uma criatura abalada ou apavorada, você recebe um bônus em testes de ataque igual à penalidade causada pela condição.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'saborear_pavor_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [2]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 14, 'value' => 'str'],
            ],
        ]);

        Power::create([
            'id' => 16076,
            'name' => 'Cascos',
            'description' => 'Você possui uma arma natural de cascos (dano 1d8, crítico x2, impacto). Uma vez por rodada, quando usa a ação agredir para atacar com outra arma, pode gastar 1 PM para fazer um ataque corpo a corpo extra com os cascos.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cascos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [3]],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1002],
            ],
        ]);

        Power::create([
            'id' => 16077,
            'name' => 'Ginete Natural',
            'description' => 'Você é considerado montado para efeito de fazer investidas e para benefícios das armas que empunha, e pode escolher o poder Carga de Cavalaria mesmo sem cumprir seus pré-requisitos. Entretanto, não pode se beneficiar de uma montaria e, se estiver carregando um cavaleiro, sofre -2 em testes (além das penalidades de sobrecarga, se houver) e é considerado em condição ruim para lançar magias.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'ginete_natural_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [3]],
            ],
            'effects' => [
                ['tag' => 'waive_prerequisites', 'op' => 'grant', 'power_ids' => [11001]],
            ],
        ]);

        Power::create([
            'id' => 16078,
            'name' => 'Paquidérmico',
            'description' => 'Seu tamanho é Grande. Você recebe +1 na Defesa e pode usar Força como atributo-chave de Intimidação (em vez de Carisma).',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'paquidermico_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [4]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16127],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16128],
            ],
        ]);

        Power::create([
            'id' => 16127,
            'name' => 'Paquidérmico (Defesa)',
            'description' => 'Você recebe +1 na Defesa.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'paquidermico_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16128,
            'name' => 'Paquidérmico (Intimidação)',
            'description' => 'Você pode usar Força como atributo-chave de Intimidação (em vez de Carisma).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'paquidermico_01.webp',
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 14, 'value' => 'str'],
            ],
        ]);

        Power::create([
            'id' => 16079,
            'name' => 'Papel Tribal',
            'description' => 'Você é treinado em uma perícia a sua escolha entre Cura, Intimidação, Ofício ou Sobrevivência.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'papel_tribal_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [4]],
            ],
            'effects' => [
                ['tag' => 'free_skills_choice', 'op' => 'grant', 'value' => 1, 'skill_ids' => [7,14,22,28]],
            ],
        ]);

        Power::create([
            'id' => 16080,
            'name' => 'Arsenal do Oceano',
            'description' => 'Você recebe proficiência em arpão, rede e tridente e +2 em testes de ataque com essas armas. Se receber proficiência em uma dessas armas novamente, passa a considerá-la como uma arma leve.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arsenal_do_oceano_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [8]],
            ],
            'applies_when' => ['weapon_ids' => [12,13,14,15]],
            'effects' => [
                ['tag' => 'waive_weapon_proficiency', 'op' => 'grant', 'weapon_ids' => [12,13,14,15]],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16092],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16093],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16094],
            ],
        ]);

        Power::create([
            'id' => 16081,
            'name' => 'Cria das Águas',
            'description' => 'Você possui deslocamento de natação igual a seu deslocamento em terra e visão na penumbra. Quando dentro d\'água, você recebe percepção às cegas 18m e +2 na Defesa e em Furtividade e Sobrevivência.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'cria_das_aguas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [8]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16082,
            'name' => 'Dependência de Água',
            'description' => 'Se permanecer mais de um dia sem contato com água, você não recupera PM com descanso até voltar para a água (ou, pelo menos, tomar um bom banho!).',
            'source' => 'race_granted',
            'usability' => 'resting',
            'icon_file_name' => 'dependencia_de_agua_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [8]],
            ],
            'effects' => [
                ['tag' => 'resting_pm_recovery', 'op' => 'set', 'value' => 0],
            ],
        ]);

        Power::create([
            'id' => 16083,
            'name' => 'Natureza Vegetal',
            'description' => 'A criatura é um vegetal sensciente, ou possui traços vegetais em sua fisiologia. Ela é imune a atordoamento e metamorfose, mas é afetada por efeitos que afetem especificamente plantas. No caso de magias sem teste de resistência, ela tem direito a um teste de Fortitude (CD da magia) para evitar o efeito.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'natureza_vegetal_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [9]],
            ],
            'effects' => [
                ['tag' => 'block_condition', 'op' => 'grant', 'condition_id' => 2],
                ['tag' => 'block_spell', 'op' => 'grant', 'spell_id' => 30],
            ],
        ]);

        Power::create([
            'id' => 16084,
            'name' => 'Corpo Vegetal',
            'description' => 'Você é uma criatura do tipo monstro e recebe natureza vegetal e visão no escuro.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'corpo_vegetal_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [9]],
            ],
        ]);

        Power::create([
            'id' => 16085,
            'name' => 'Presença Arcana',
            'description' => 'Você recebe +2 em Misticismo e resistência a magia +2.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'presenca_arcana_misticismo_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [9]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16086],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16087],
            ],
        ]);

        Power::create([
            'id' => 16086,
            'name' => 'Presença Arcana (Misticismo)',
            'description' => 'Você recebe +2 em Misticismo.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'presenca_arcana_misticismo_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16087,
            'name' => 'Presença Arcana (Resistência a Magia)',
            'description' => 'Você recebe +2 em testes de resistência contra magia.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'presenca_arcana_resistencia_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16088,
            'name' => 'Regeneração Vegetal',
            'description' => 'Uma vez por rodada, você pode gastar 1 PM para recuperar 5 PV. Essa habilidade não cura dano de ácido ou fogo.',
            'source' => 'race_granted',
            'usability' => 'active',
            'pm_cost' => 1,
            'icon_file_name' => 'regeneracao_vegetal_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [9]],
            ],
            'effects' => [
                ['tag' => 'restore_pv', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 16089,
            'name' => 'Intolerância a Luz',
            'description' => 'Você possui sensibilidade a luz e, quando exposto a luz do sol ou similar, não consegue ativar sua Regeneração Vegetal.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'intolerancia_a_luz_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [9]],
            ],
        ]);

        Power::create([
            'id' => 16090,
            'name' => 'Oportunista',
            'description' => 'Você recebe +2 nas rolagens de dano contra criaturas que tenham sofrido dano de outras criaturas desde seu último turno.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'gnoll_oportunista_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [11]],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16091,
            'name' => 'Rendição',
            'description' => 'Quando um inimigo se rende, você recebe 1d4 PM temporários cumulativos. Da mesma forma, quando é reduzido a um quarto de seus PV ou menos, seu instinto é se render. Caso continue lutando, fica alquebrado.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'gnoll_rendicao_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [11]],
            ],
        ]);

        // Arsenal do Oceano's own hidden "grip upgrade" children — always
        // granted alongside 16080, never shown (character-main.ts's
        // hiddenFromPowersListIds), never picked directly. Each only
        // upgrades its weapon(s) to grip 'light' once the character ALSO
        // separately has that weapon's own real proficiency power granted
        // (applies_when.power_id) — see resolve-effective-weapon-grip.ts.
        Power::create([
            'id' => 16092,
            'name' => 'Arsenal do Oceano (Tridente)',
            'description' => 'Se receber proficiência em armas marciais, passa a considerar o tridente como uma arma leve.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arsenal_oceano_tridente_01.webp',
            'applies_when' => ['power_id' => 40],
            'effects' => [
                ['tag' => 'mod_weapon_grip', 'op' => 'set', 'value' => 'light', 'weapon_ids' => [12]],
            ],
        ]);

        Power::create([
            'id' => 16093,
            'name' => 'Arsenal do Oceano (Arpão)',
            'description' => 'Se receber proficiência em arpão, passa a considerá-lo como uma arma leve.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arsenal_oceano_arpao_01.webp',
            'applies_when' => ['power_id' => 19000],
            'effects' => [
                ['tag' => 'mod_weapon_grip', 'op' => 'set', 'value' => 'light', 'weapon_ids' => [13,14]],
            ],
        ]);

        Power::create([
            'id' => 16094,
            'name' => 'Arsenal do Oceano (Rede)',
            'description' => 'Se receber proficiência em rede, passa a considerá-la como uma arma leve.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arsenal_oceano_rede_01.webp',
            'applies_when' => ['power_id' => 19001],
            'effects' => [
                ['tag' => 'mod_weapon_grip', 'op' => 'set', 'value' => 'light', 'weapon_ids' => [15]],
            ],
        ]);

        Power::create([
            'id' => 16095,
            'name' => 'Asas de Abutre',
            'description' => 'Você possui asas no lugar dos braços e mãos. Você pode pairar a 1,5m do chão com deslocamento 12m. Isso permite que você ignore terreno difícil e o torna imune a dano por queda (a menos que esteja inconsciente). Se não estiver usando armadura pesada, você pode gastar 1 PM por rodada para voar com deslocamento de 18m.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'turn',
            'pm_cost' => 1,
            'icon_file_name' => 'asas_de_abutre_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [13]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 18],
            ],
        ]);

        Power::create([
            'id' => 16096,
            'name' => 'Cria de Masmorra',
            'description' => 'Você é uma criatura do tipo monstro e recebe visão no escuro e +2 em Intimidação e Sobrevivência.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cria_de_masmorra_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [13]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16097,
            'name' => 'Grito Aterrorizante',
            'description' => 'Você pode lançar a magia Grito Aterrorizante (atributo-chave Carisma).',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'grito_aterrorizante_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [13]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 3002],
            ],
        ]);

        Power::create([
            'id' => 16098,
            'name' => 'Pés Rapinantes',
            'description' => 'Seus pés podem ser usados como mãos ou como duas armas naturais de garras (dano 1d6 cada, crítico x2, corte). Uma vez por rodada, quando usa a ação agredir para atacar com uma arma, você pode gastar 1 PM para fazer um ataque corpo a corpo extra com uma das garras, desde que ela esteja livre e não tenha sido usada para atacar neste turno. Como alternativa, se tiver habilidades que exijam uma arma secundária (como Estilo de Duas Armas), você pode usá-las com suas garras.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'pes_rapinantes_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [13,43]],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1003],
            ],
        ]);

        //TODO add all weapon ids for armas marciais when items are fully added
        Power::create([
            'id' => 16099,
            'name' => 'Arte da Guerra',
            'description' => 'Você é treinado em Guerra e recebe proficiência em armas marciais. Se receber proficiência em armas marciais novamente, em vez disso recebe +2 em rolagens de dano com essas armas.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arte_de_guerra_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [14]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'trains', 'skill_id' => 12],
                ['tag' => 'waive_weapon_proficiency', 'op' => 'grant', 'weapon_ids' => [2,3,5,12]],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16100],
            ],
        ]);

        // TODO add all martial weapon ids
        Power::create([
            'id' => 16100,
            'name' => 'Arte da Guerra (Armas Marciais)',
            'description' => 'Se receber proficiência em armas marciais, em vez da proficiência, recebe +2 em rolagens de dano com essas armas.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arte_de_guerra_armas_marciais_01.webp',
            'applies_when' => ['power_id' => 40, 'weapon_ids' => [2,3,5,12]],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        //TODO fix this when we add oficios
        Power::create([
            'id' => 16101,
            'name' => 'Metalurgia Hobgoblin',
            'description' => 'Você recebe +2 em Ofício (armeiro) e, se for treinado nesta perícia, pode fabricar armas e armaduras superiores com uma melhoria. Se aprender a fabricar itens superiores desses tipos por outra habilidade, gasta apenas ¼ do preço para aplicar melhorias (ao invés de 1/3).',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'metalurgia_hobgoblin_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [14]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 22, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16102,
            'name' => 'Táticas de Guerrilha',
            'description' => 'Você recebe visão no escuro e +2 em Furtividade.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'taticas_de_guerrilha_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [14]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
            ],
        ]);

        //TODO fix this one when we implement tormenta stuff
        Power::create([
            'id' => 16104,
            'name' => 'Couraça Rubra',
            'description' => 'Você recebe redução de dano 2. Sua couraça conta como um poder da Tormenta, exceto para perda de Carisma.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'couraca_rubra_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [17]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 2],
            ],
        ]);

        //TODO fix this one when we implement tormenta stuff
        Power::create([
            'id' => 16105,
            'name' => 'Disforme',
            'description' => 'Por sua anatomia anômala, você não pode empunhar ou vestir itens mundanos, a menos que sejam especialmente adaptados para você (isso demora um dia e custa 50% do valor do item, sem contar melhorias). Os itens recebidos por sua origem ou habilidade são adaptados para você. Essa habilidade conta como um poder da Tormenta, exceto para perda de Carisma.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'disforme_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [17]],
            ],
        ]);

        //TODO fix this one when we implement tormenta stuff
        Power::create([
            'id' => 16106,
            'name' => 'Terror Vivo',
            'description' => 'Você pode usar Força como atributo-chave de Intimidação (em vez de Carisma) e recebe um poder da Tormenta à sua escolha, que não conta para perda de Carisma.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => 'terror_vivo_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [17]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 14, 'value' => 'str'],
            ],
        ]);

        Power::create([
            'id' => 16107,
            'name' => 'Alma da Água',
            'description' => 'Você é uma criatura do tipo espírito e tem deslocamento de natação igual ao seu deslocamento terrestre.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'alma_da_agua_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [18]],
            ],
        ]);

        Power::create([
            'id' => 16108,
            'name' => 'Adaptável',
            'description' => 'Você recebe +2 em Intimidação e se torna treinado em uma perícia a sua escolha.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'adaptavel_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [23]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 2],
                ['tag' => 'free_skills_choice', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16109,
            'name' => 'Criatura das Profundezas',
            'description' => 'Você recebe visão no escuro e +2 em testes de Percepção e Sobrevivência realizados no subterrâneo.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'criatura_das_profundezas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [23]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16110,
            'name' => 'Sangue Orc',
            'description' => 'Você recebe +1 em rolagens de dano com armas corpo a corpo e de arremesso e é considerado um orc para efeitos relacionados a raça.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sangue_orc_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee', 'thrown']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [23]],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16111,
            'name' => 'Mente Aberta',
            'description' => 'Você recebe +2 em Diplomacia e Investigação.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'mente_aberta_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [24]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16112,
            'name' => 'Plurivalente',
            'description' => 'Você recebe um poder geral à sua escolha.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'plurivalente_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [24]],
            ],
            'effects' => [
                ['tag' => 'general_power_choice', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 16113,
            'name' => 'Espreitador',
            'description' => 'Você recebe visão no escuro e +2 em Percepção e Vontade.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'espreitador_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [28]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16114,
            'name' => 'Garras',
            'description' => 'Você tem duas armas naturais de garra (dano 1d6, crítico x2, corte), uma em cada mão. Uma vez por rodada, quando usa a ação agredir para atacar com uma arma, você pode gastar 1 PM para fazer um ataque corpo a corpo extra com uma das garras, desde que ela esteja livre e não tenha sido usada para atacar neste turno. Como alternativa, se tiver habilidades que exijam uma arma secundária (como Estilo de Duas Armas), você pode usá-las com suas garras.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'garra_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [28,30,54]],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1003],
            ],
        ]);

        Power::create([
            'id' => 16115,
            'name' => 'Sapiência',
            'description' => 'Você aprende e pode lançar uma magia de 1º círculo de adivinhação (atributo-chave Sabedoria). Caso aprenda novamente essa magia, seu custo diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sapiencia_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [28]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'limit_spell_choices', 'op' => 'set', 'spell_circle' => 1, 'spell_school' => 'adivinhacao'],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'knw'],
            ],
        ]);

        Power::create([
            'id' => 16116,
            'name' => 'Destemor',
            'description' => 'Você recebe +2 em rolagens de dano e em testes de resistência contra criaturas maiores que você.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'destemor_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [31]],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16117,
            'name' => 'Agarra-me se Puderes',
            'description' => 'Seu deslocamento é 12m (em vez de 9m) e você tem visão na penumbra.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'agarrame_se_puderes_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [34]],
            ],
        ]);

        Power::create([
            'id' => 16118,
            'name' => 'Esperteza Vulpina',
            'description' => 'Você recebe +2 em duas perícias originalmente baseadas em Inteligência ou Carisma, a sua escolha.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'esperteza_vulpina_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [34]],
            ],
            'effects' => [
                ['tag' => 'choice_bonus_to_skills', 'op' => 'add', 'value' => 2, 'bonus' => 2, 'skill_ids' => [6,12,15,20,21,22,2,4,8,9,14,17]],
            ],
        ]);

        Power::create([
            'id' => 16119,
            'name' => 'Arborícola',
            'description' => 'Você recebe deslocamento de escalada 6m e +2 em Furtividade.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'arboricola_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [35]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16120,
            'name' => 'Constritor',
            'description' => 'Você recebe +2 em testes para agarrar e em rolagens de dano contra criaturas que estiver agarrando.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'constritor_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [35]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16121,
            'name' => 'Instintos Traiçoeiros',
            'description' => 'Você recebe visão no escuro, +2 em Diplomacia e na CD de seus efeitos mentais.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'instintos_traicoeiros_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [35]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16122],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16123],
            ],
        ]);

        Power::create([
            'id' => 16122,
            'name' => 'Instintos Traiçoeiros (Diplomacia)',
            'description' => 'Você recebe +2 em Diplomacia.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'instintos_traicoeiros_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16123,
            'name' => 'Instintos Traiçoeiros (CD)',
            'description' => 'Você recebe +2 na CD de seus efeitos mentais.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'instintos_traicoeiros_01.webp',
            'applies_when' => ['spell_resistances' => ['vontade']],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16124,
            'name' => 'Marrada Impressionante',
            'description' => 'Você recebe +2 em ataques em investida e em testes para empurrar, e pode usar Força como atributo-chave de Intimidação (em vez de Carisma).',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'marrada_impressionante_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [26]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16125],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16126],
            ],
        ]);

        Power::create([
            'id' => 16125,
            'name' => 'Marrada Impressionante',
            'description' => 'Você recebe +2 em ataques em investida e em testes para empurrar.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'marrada_impressionante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16126,
            'name' => 'Marrada Impressionante (Intimidação)',
            'description' => 'Você pode usar Força como atributo-chave de Intimidação (em vez de Carisma).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => 'marrada_impressionante_01.webp',
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 14, 'value' => 'str'],
            ],
        ]);

        Power::create([
            'id' => 16129,
            'name' => 'Patas Ligeiras',
            'description' => 'Seu deslocamento é 12m (em vez de 9m) e, quando faz um teste de Atletismo para correr, você não precisa percorrer uma linha reta.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'patas_ligeiras_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [27]],
            ],
        ]);

        Power::create([
            'id' => 16130,
            'name' => 'Pé de Coelho',
            'description' => 'Quando faz um teste de uma perícia baseada em Destreza (exceto testes de ataque), você pode gastar 1 PM para rolar dois dados e usar o melhor resultado.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => 'pata_de_coelho_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [27]],
            ],
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'attribute' => 'dex', 'exclude_skill_ids' => [25]],
            ],
        ]);

        Power::create([
            'id' => 16131,
            'name' => 'Senso de Preservação',
            'description' => 'Você recebe visão na penumbra e +2 em Percepção e Reflexos.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'senso_de_preservacao_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [27]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16132,
            'name' => 'Mordida Poderosa',
            'description' => 'Você possui uma arma natural de mordida (dano 1d6, crítico x2, perfuração), com a qual recebe +2 em testes de agarrar. Uma vez por rodada, quando usa a ação agredir para atacar com outra arma, pode gastar 1 PM para fazer um ataque corpo a corpo extra com a mordida.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'mordida_poderosa_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [29]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16133,
            'name' => 'Predador Aquático',
            'description' => 'Você tem deslocamento de natação 6m e recebe +1 na Defesa e +2 em Furtividade.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'predador_aquatico_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [29]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16134,
            'name' => 'Surto Reptiliano',
            'description' => 'Uma vez por cena, você pode gastar 1 PM para realizar uma ação de movimento adicional em seu turno.',
            'source' => 'race_granted',
            'usability' => 'active',
            'pm_cost' => 1,
            'icon_file_name' => 'surto_reptiliano_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [29]],
            ],
        ]);

        Power::create([
            'id' => 16135,
            'name' => 'As Muitas Vidas de Um Gato',
            'description' => 'Você soma seu Carisma em testes de Constituição para estabilizar sangramento e em Acrobacia e, se estiver consciente em uma queda, reduz o dano dela em 3d6.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'as_muitas_vidas_de_um_gato_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [30]],
            ],
        ]);

        Power::create([
            'id' => 16136,
            'name' => 'Sentidos Felinos',
            'description' => 'Você recebe visão na penumbra e +2 em Furtividade e Percepção.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sentidos_felinos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [30]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16137,
            'name' => 'Rugido Imponente',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para emitir um rugido assustador. Todos os inimigos em alcance curto sofrem -2 em rolagens de dano por 1 rodada. Medo.',
            'source' => 'race_granted',
            'usability' => 'active',
            'pm_cost' => 1,
            'action_cost' => 'movement',
            'icon_file_name' => 'rugido_imponente_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [32]],
            ],
        ]);

        Power::create([
            'id' => 16138,
            'name' => 'Sentidos da Realeza',
            'description' => 'Você recebe visão na penumbra e +2 em Intimidação e Percepção.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sentidos_da_realeza_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [32]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16139,
            'name' => 'Táticas de Matilha',
            'description' => 'Você recebe +2 nas rolagens de dano e na margem de ameaça em ataques contra oponentes que esteja flanqueando.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'taticas_da_matilha_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [33]],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 16140,
            'name' => 'Asas de Morcego',
            'description' => 'Você pode pairar a 1,5m do chão com deslocamento 9m. Isso permite que você ignore terreno difícil e o torna imune a dano por queda (a menos que esteja inconsciente). Se não estiver usando armadura pesada, você pode gastar 1 PM por rodada para voar com deslocamento de 12m. Você precisa de espaço para abrir suas asas; quando paira ou voa, ocupa o espaço de uma criatura de uma categoria de tamanho maior que a sua.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'pm_cost' => 1,
            'icon_file_name' => 'asas_de_morcego_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [59]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 12],
            ],
        ]);

        Power::create([
            'id' => 16141,
            'name' => 'Criatura da Noite',
            'description' => 'Você recebe visão no escuro e +2 em Furtividade e Percepção.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'criatura_da_noite_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [59]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16142,
            'name' => 'Ecolocalização',
            'description' => 'Você pode gastar 1 PM para receber percepção às cegas em alcance médio por 1 rodada.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'turn',
            'pm_cost' => 1,
            'icon_file_name' => 'ecolocalizacao_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [59]],
            ],
        ]);

        Power::create([
            'id' => 16143,
            'name' => 'Abraço de Urso',
            'description' => 'Você é Grande e pode usar Constituição para Intimidação (em vez de Carisma).',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => 'abraco_de_urso_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [36]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 14, 'value' => 'con'],
            ],
        ]);

        Power::create([
            'id' => 16144,
            'name' => 'Cauda',
            'description' => 'Você possui uma arma natural de cauda (dano 1d6, crítico x2, impacto). Uma vez por rodada, quando usa a ação agredir para atacar com outra arma, pode gastar 1 PM para fazer um ataque corpo a corpo extra com a cauda.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'cauda_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [37,38]],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1004],
            ],
        ]);

        Power::create([
            'id' => 16145,
            'name' => 'Inocência Dissimulada',
            'description' => 'Você recebe +2 em Enganação, e, uma vez por cena, pode pagar 2 PM para substituir um teste de perícia por um teste de Enganação.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'inocencia_dissimulada_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [37,38]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16146],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16147],
            ],
        ]);

        Power::create([
            'id' => 16146,
            'name' => 'Inocência Dissimulada (Enganação)',
            'description' => 'Você recebe +2 em Enganação.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'inocencia_dissimulada_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16147,
            'name' => 'Inocência Dissimulada (Substituir Teste)',
            'description' => 'Uma vez por cena, você pode pagar 2 PM para substituir um teste de perícia por um teste de Enganação.',
            'source' => 'power_granted',
            'usability' => 'active',
            'pm_cost' => 2,
            'icon_file_name' => 'inocencia_dissimulada_01.webp',
        ]);

        Power::create([
            'id' => 16148,
            'name' => 'Presentes de Sszzaas',
            'description' => 'Você recebe visão na penumbra, +1 na Defesa e resistência a veneno +5.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'presente_szass_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [37,38]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16149],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16150],
            ],
        ]);

        Power::create([
            'id' => 16149,
            'name' => 'Presentes de Sszzaas',
            'description' => 'Você recebe visão no escuro e +1 na Defesa.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'presente_szass_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16150,
            'name' => 'Presentes de Sszzaas (Resistência)',
            'description' => 'Você recebe resistência a veneno +5.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'presente_szass_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 16151,
            'name' => 'Fraquezas Ofídias',
            'description' => 'Você sofre 1 ponto de dano adicional para cada dado de dano de frio e –5 em testes de resistência contra Músicas de Bardo.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'fraquezas_ofidicas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [37,38]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16152],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16153],
            ],
        ]);

        Power::create([
            'id' => 16152,
            'name' => 'Fraquezas Ofídias (Frio)',
            'description' => 'Você sofre 1 ponto de dano adicional para cada dado de dano de frio.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'fraquezas_ofidicas_01.webp',
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'per_die', 'value' => -1, 'damage_reduction_type' => 'cold'],
            ],
        ]);

        Power::create([
            'id' => 16153,
            'name' => 'Fraquezas Ofídias',
            'description' => 'Você sofre –5 em testes de resistência contra Músicas de Bardo.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'fraquezas_ofidicas_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => -5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => -5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 16154,
            'name' => 'Nezumi',
            'description' => 'Você recebe o poder geral Empunhadura Poderosa.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'nezumi_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [39]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 261],
            ],
        ]);

        Power::create([
            'id' => 16155,
            'name' => 'Pequeno, Mas Não Metade',
            'description' => 'Seu tamanho é Pequeno, mas seu deslocamento se mantém 9m e você recebe resistência a medo +5 contra criaturas maiores que você e +2 em Intimidação.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'pequeno_mas_nao_metade_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [39]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16156],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16157],
            ],
        ]);

        Power::create([
            'id' => 16156,
            'name' => 'Pequeno, Mas Não Metade',
            'description' => 'Você recebe resistência a medo +5 contra criaturas maiores que você.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'pequeno_mas_nao_metade_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 16157,
            'name' => 'Pequeno, Mas Não Metade (Intimidação)',
            'description' => 'Você recebe +2 em Intimidação.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'pequeno_mas_nao_metade_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16158,
            'name' => 'Quanto Maior o Tamanho…',
            'description' => 'Você é um humanoide do subtipo gigante; seu tamanho é Grande e você recebe visão na penumbra.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'quanto_maior_o_tamanho_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [40]],
            ],
        ]);

        Power::create([
            'id' => 16159,
            'name' => '… Maior a Porrada!',
            'description' => 'Quando faz um ataque corpo a corpo, você pode gastar 1 PM para causar +1d8 pontos de dano do mesmo tipo, se acertar.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => 'maior_a_porrada_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [40]],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 16160,
            'name' => 'Camada de Ingenuidade',
            'description' => 'Você sofre –5 em Intuição e Vontade.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'camada_de_ingenuidade_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [40]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => -5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 16161,
            'name' => 'Feroz',
            'description' => 'Você recebe +2 em rolagens de dano com armas corpo a corpo e de arremesso. Quando sofre dano de um inimigo, esse bônus se torna +4 até o fim de seu próximo turno.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'orc_feroz_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [41]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16162],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16163],
            ],
        ]);

        Power::create([
            'id' => 16162,
            'name' => 'Feroz (Dano)',
            'description' => 'Você recebe +2 em rolagens de dano com armas corpo a corpo e de arremesso.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'orc_feroz_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee', 'thrown']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16163,
            'name' => 'Feroz (Sofreu Dano)',
            'description' => 'Quando sofre dano de um inimigo, o bônus de dano se torna +4 até o fim de seu próximo turno.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'orc_feroz_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee', 'thrown']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16164,
            'name' => 'Habitante das Cavernas',
            'description' => 'Você recebe visão no escuro e +2 em testes de Percepção e Sobrevivência realizados no subterrâneo. Entretanto, tem sensibilidade a luz.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'habitante_das_cavernas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [41]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16165,
            'name' => 'Vigor Brutal',
            'description' => 'Você recebe +2 em Fortitude e soma sua Força em seu total de pontos de vida.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'vigor_brutal_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [41]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'mod_max_pv', 'op' => 'add', 'value' => 'str'],
            ],
        ]);

        Power::create([
            'id' => 16166,
            'name' => 'Ligação Natural',
            'description' => 'Você possui uma ligação mental com uma criatura inteligente (Int –3 ou mais). Vocês podem se comunicar mentalmente em alcance longo e sempre sabem em que direção e distância podem encontrar o outro. Você pode trocar a criatura com a qual mantém o vínculo no início de cada aventura.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'ligacao_natural_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [43]],
            ],
        ]);

        Power::create([
            'id' => 16167,
            'name' => 'Mãos Rudimentares',
            'description' => 'Suas mãos não permitem que você empunhe itens, a menos que sejam mágicos ou especialmente adaptados para você (o que demora um dia e custa 50% do preço do item, sem contar melhorias). Seus itens iniciais, e aqueles recebidos por sua origem ou habilidades, são adaptados para você.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'maos_rudimentares_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [43]],
            ],
        ]);

        Power::create([
            'id' => 16168,
            'name' => 'Senhor dos Céus',
            'description' => 'Você pode pairar a 1,5m do chão com deslocamento 9m. Isso permite que você ignore terreno difícil e o torna imune a dano por queda (a menos que esteja inconsciente). Se não estiver usando armadura pesada, você pode gastar 1 PM por rodada para voar com deslocamento de 12m. Quando abre suas asas para pairar ou voar, você ocupa o espaço de uma criatura de uma categoria de tamanho maior que a sua.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'pm_cost' => 1,
            'icon_file_name' => 'senhor_dos_ceus_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [43]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 12],
            ],
        ]);

        Power::create([
            'id' => 16169,
            'name' => 'Sentidos Rapinantes',
            'description' => 'Você recebe visão na penumbra e +2 em Percepção e Sobrevivência.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sentidos_rapinantes_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [43]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16170,
            'name' => 'Batráquio',
            'description' => 'Você recebe visão na penumbra e deslocamento de natação igual ao seu deslocamento terrestre.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'batraquio_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [50]],
            ],
        ]);

        Power::create([
            'id' => 16171,
            'name' => 'Linguarudo',
            'description' => 'Sua língua é uma arma natural que pode atacar inimigos a até 3m (dano 1d4, crítico x2, impacto). Ela é uma arma versátil, fornecendo +2 em testes para desarmar e derrubar. Uma vez por rodada, quando usa a ação agredir com outra arma, você pode gastar 1 PM para fazer um ataque corpo a corpo extra com a língua.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'linguarudo_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [50]],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1005],
            ],
        ]);

        Power::create([
            'id' => 16172,
            'name' => 'Saltador',
            'description' => 'Você recebe +10 em testes de Atletismo para saltar.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'saltador_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [50]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 16173,
            'name' => 'Asas Desorientadoras',
            'description' => 'Quando estão livres, suas asas podem ser usadas para distrair seus oponentes. Se não estiver voando, você recebe os benefícios de Finta Aprimorada. Se tiver esse poder, em vez disso, o bônus em Enganação para fintar aumenta para +5.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'asas_desorientadoras_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [51]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11002],
            ],
        ]);

        Power::create([
            'id' => 16174,
            'name' => 'Caminhante do Céu',
            'description' => 'Você pode pairar a 1,5m do chão com deslocamento 9m. Isso permite que você ignore terreno difícil e o torna imune a dano por queda (a menos que esteja inconsciente). Você pode gastar 1 PM por rodada para voar com deslocamento de 12m. Você precisa de espaço para abrir suas asas; quando paira ou voa, ocupa o espaço de uma criatura de uma categoria de tamanho maior que a sua.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'pm_cost' => 1,
            'icon_file_name' => 'caminhante_do_ceu_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [51]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 12],
            ],
        ]);

        Power::create([
            'id' => 16175,
            'name' => 'Sentidos Corvinos',
            'description' => 'Você é uma criatura do tipo espírito e recebe visão no escuro e +2 em Percepção.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sentidos_corvinos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [51]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16176,
            'name' => 'Através de Espinheiros',
            'description' => 'Você recebe resistência a corte e perfuração 2 e não sofre redução em seu deslocamento por terreno difícil natural.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'atraves_de_espinheiros_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [53]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 2, 'damage_reduction_type' => 'slashing'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 2, 'damage_reduction_type' => 'piercing'],
            ],
        ]);

        Power::create([
            'id' => 16177,
            'name' => 'Sentidos Selvagens',
            'description' => 'Você recebe +2 em Sobrevivência, visão na penumbra e faro.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sentidos_selvagens_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [53,54]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16178,
            'name' => 'Velocista da Planície',
            'description' => 'Seu deslocamento é 12m. Você pode usar Destreza como atributo-chave de Atletismo (em vez de Força) e, quando faz testes de Atletismo para correr ou saltar, pode rolar dois dados e usar o melhor resultado.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'velocista_da_planice_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [53]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16179],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16180],
            ],
        ]);

        Power::create([
            'id' => 16179,
            'name' => 'Velocista da Planície (Atributo Chave)',
            'description' => 'Você pode usar Destreza como atributo-chave de Atletismo (em vez de Força).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => 'velocista_da_planice_01.webp',
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 3, 'value' => 'dex'],
            ],
        ]);

        Power::create([
            'id' => 16180,
            'name' => 'Velocista da Planície (Correr e Saltar)',
            'description' => 'Quando faz testes de Atletismo para correr ou saltar, você pode rolar dois dados e usar o melhor resultado.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'velocista_da_planice_01.webp',
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 3],
            ],
        ]);

        Power::create([
            'id' => 16181,
            'name' => 'Rainha da Selva',
            'description' => 'Você recebe deslocamento de escalada 9m, +2 em Atletismo e recupera +1 PV por nível quando descansa.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'rainha_da_selva_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [54]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 3, 'value' => 2],
                ['tag' => 'resting_bonus_pv', 'op' => 'add_per_level', 'value' => 1, 'per_character_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 16182,
            'name' => 'Híbrido Mecânico',
            'description' => 'Você é uma criatura do tipo construto. Recebe visão no escuro e imunidade a cansaço, efeitos metabólicos e veneno. Além disso, não precisa respirar, alimentar-se ou dormir, mas não se beneficia de itens da categoria alimentação e efeitos de cura mundana são reduzidos pela metade em você. Você precisa ficar inerte por 8 horas por dia para recarregar suas forças. Se fizer isso, recupera PV e PM por descanso em condições normais (yidishan não são afetados por condições boas ou ruins de descanso).',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'hibrido_mecanico_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [55]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16183],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16184],
            ],
        ]);

        Power::create([
            'id' => 16183,
            'name' => 'Híbrido Mecânico (Construto)',
            'description' => 'Você é uma criatura do tipo construto. Recebe visão no escuro e imunidade a cansaço, efeitos metabólicos e veneno. Além disso, não precisa respirar, alimentar-se ou dormir, mas não se beneficia de itens da categoria alimentação e efeitos de cura mundana são reduzidos pela metade em você.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'hibrido_mecanico_01.webp',
            'effects' => [
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'poison'],
                ['tag' => 'condition_type_immunity', 'op' => 'grant', 'value' => 'tired'],
                ['tag' => 'condition_type_immunity', 'op' => 'grant', 'value' => 'metabolism'],
            ],
        ]);

        Power::create([
            'id' => 16184,
            'name' => 'Híbrido Mecânico (Recarga)',
            'description' => 'Você precisa ficar inerte por 8 horas por dia para recarregar suas forças. Se fizer isso, recupera PV e PM por descanso em condições normais (yidishan não são afetados por condições boas ou ruins de descanso).',
            'source' => 'power_granted',
            'usability' => 'resting',
            'icon_file_name' => 'hibrido_mecanico_01.webp',
            'effects' => [
                ['tag' => 'resting', 'op' => 'set', 'value' => 0],
            ],
        ]);

        Power::create([
            'id' => 16185,
            'name' => 'Natureza Orgânica',
            'description' => 'Você se torna treinado em uma perícia (não precisa ser da sua classe) ou recebe um poder geral a sua escolha. Como alternativa, você pode ser um yidishan de outra raça humanoide que não humano. Neste caso, você ganha uma habilidade dessa raça a sua escolha. Se a raça era de tamanho diferente de Médio, você também possui sua categoria de tamanho.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'natureza_organica_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [55]],
            ],
        ]);

        Power::create([
            'id' => 16186,
            'name' => 'Peças Metálicas',
            'description' => 'As partes mecânicas que complementam seu corpo fornecem +2 na Defesa, mas impõem uma penalidade de armadura de –2',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'pecas_metalicas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [55]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_armor_penalty', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16187,
            'name' => 'Duende (Animal)',
            'description' => 'Você é feito de carne e osso. Seu corpo é humanoide, mas sua aparência varia: pode ser algo similar a um elfo ou sílfide, um animal que anda sobre duas patas ou uma mistura dessas possibilidades. Você recebe +1 em um atributo a sua escolha.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'duende_animal_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 16188,
            'name' => 'Duende (Vegetal)',
            'description' => 'Você é feito de folhas, vinhas, cortiça ou madeira. Você recebe as habilidades Natureza Vegetal (é imune a atordoamento e metamorfose, mas é afetado por efeitos que afetam plantas monstruosas — se o efeito não tiver um teste de resistência, você tem direito a um teste de Fortitude) e Florescer Feérico (pode gastar uma quantidade de PM limitada pela sua Constituição para curar 2d8 PV por PM gasto no início do seu próximo turno).',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => 'duende_vegetal_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16083],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16189],
            ],
        ]);

        Power::create([
            'id' => 16189,
            'name' => 'Florescer Feérico',
            'description' => 'Você pode gastar uma quantidade de PM limitada pela sua Constituição para curar 2d8 PV por PM gasto no início do seu próximo turno.',
            'source' => 'power_granted',
            'usability' => 'active',
            'pm_cost' => 1,
            'icon_file_name' => 'florescer_feerico_01.webp',
            'effects' => [
                ['tag' => 'restore_pv', 'op' => 'roll', 'value' => '2d8'],
            ],
        ]);

        Power::create([
            'id' => 16190,
            'name' => 'Duende (Mineral)',
            'description' => 'Você é feito de material inorgânico, como argila, rocha, cristal ou vidro. Você recebe imunidade a efeitos de metabolismo e redução de corte, fogo e perfuração 5, mas não se beneficia de itens da categoria alimentação.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'duende_mineral_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'condition_type_immunity', 'op' => 'grant', 'value' => 'metabolism'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'slashing'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'fire'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'piercing'],
            ],
        ]);

        Power::create([
            'id' => 16191,
            'name' => 'Duende (Minúsculo)',
            'description' => 'Você é miudinho e fofinho. Pode ser uma caneca falante, um gato de monóculo ou um corvo com cara de velho (é, talvez não seja tão fofo assim). Você é Minúsculo (+5 em testes de Furtividade, –5 em testes de manobras de combate, usa armas reduzidas), possui deslocamento base 6m e sofre –1 em Força.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'duende_minusculo_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 6],
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_size', 'op' => 'set', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 16192,
            'name' => 'Duende (Pequeno)',
            'description' => 'Você tem o tamanho de um hynne, de uma criança, de um cachorro ou de um barril. Talvez você seja um barril! Você é Pequeno (+2 em testes de Furtividade, –2 em testes de manobra) e possui deslocamento base 6m.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'duende_pequeno_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 6],
                ['tag' => 'mod_size', 'op' => 'set', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 16193,
            'name' => 'Duende (Médio)',
            'description' => 'Você é Médio (sem modificadores por tamanho) e possui deslocamento base 9m. Blé!',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'duende_medio_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'mod_size', 'op' => 'set', 'value' => 0],
            ],
        ]);

        Power::create([
            'id' => 16194,
            'name' => 'Duende (Grande)',
            'description' => 'Agora sim! Meta medo em qualquer um que achar que fadas são fracotes. Você é Grande (–2 em testes de Furtividade, +2 em testes de manobra, usa armas aumentadas), possui deslocamento base 9m e sofre –1 em Destreza.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'duende_grande_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_size', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16195,
            'name' => 'Aversão a Ferro',
            'description' => 'Um material rígido e inflexível, o ferro é nocivo a duendes. Você sofre 1 ponto de dano adicional por dado de dano de ataques com armas de ferro e sofre 1d6 pontos de dano por rodada se estiver empunhando ou vestindo um item de ferro. Isso também se aplica a aço, que inclui ferro em sua composição. Na prática, duendes usam apenas armas de madeira ou de materiais especiais, como mitral.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'aversao_a_ferro_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 16196,
            'name' => 'Aversão a Sinos',
            'description' => 'O badalar de um sino representa ordem e devoção, algo que faz mal a duendes. Se você escutar esse som, fica alquebrado e esmorecido até o fim da cena. No início de qualquer cena em um ambiente urbano no qual haja uma ou mais igrejas ou templos (o que inclui praticamente todas as aldeias e cidades do Reinado!), role 1d6. Em um resultado 1, você escutará um sino badalando em algum lugar.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'aversao_a_sinos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 16197,
            'name' => 'Tabu (Diplomacia)',
            'description' => 'Você possui um tabu — algo que nunca pode fazer (ou deixar de fazer). Crie seu tabu junto com o mestre. Em termos de regras, a esquisitice de seu tabu impõe uma penalidade de –5 em Diplomacia, Iniciativa, Luta ou Percepção, a sua escolha (um tabu que afete seu comportamento, por exemplo, impõe –5 em Diplomacia). Se você desrespeitar seu tabu, fica fatigado por um dia (mesmo que seja imune a essa condição). Nenhum efeito pode curar essa condição. Se no dia seguinte continuar desrespeitando o tabu, você fica exausto. Se no terceiro dia não mudar seu comportamento, você morre.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'tabu_diplomacia_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 16198,
            'name' => 'Tabu (Iniciativa)',
            'description' => 'Você possui um tabu — algo que nunca pode fazer (ou deixar de fazer). Crie seu tabu junto com o mestre. Em termos de regras, a esquisitice de seu tabu impõe uma penalidade de –5 em Diplomacia, Iniciativa, Luta ou Percepção, a sua escolha (um tabu que afete seu comportamento, por exemplo, impõe –5 em Diplomacia). Se você desrespeitar seu tabu, fica fatigado por um dia (mesmo que seja imune a essa condição). Nenhum efeito pode curar essa condição. Se no dia seguinte continuar desrespeitando o tabu, você fica exausto. Se no terceiro dia não mudar seu comportamento, você morre.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'tabu_iniciativa_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 16199,
            'name' => 'Tabu (Luta)',
            'description' => 'Você possui um tabu — algo que nunca pode fazer (ou deixar de fazer). Crie seu tabu junto com o mestre. Em termos de regras, a esquisitice de seu tabu impõe uma penalidade de –5 em Diplomacia, Iniciativa, Luta ou Percepção, a sua escolha (um tabu que afete seu comportamento, por exemplo, impõe –5 em Diplomacia). Se você desrespeitar seu tabu, fica fatigado por um dia (mesmo que seja imune a essa condição). Nenhum efeito pode curar essa condição. Se no dia seguinte continuar desrespeitando o tabu, você fica exausto. Se no terceiro dia não mudar seu comportamento, você morre.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'tabu_luta_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 16200,
            'name' => 'Tabu (Percepção)',
            'description' => 'Você possui um tabu — algo que nunca pode fazer (ou deixar de fazer). Crie seu tabu junto com o mestre. Em termos de regras, a esquisitice de seu tabu impõe uma penalidade de –5 em Diplomacia, Iniciativa, Luta ou Percepção, a sua escolha (um tabu que afete seu comportamento, por exemplo, impõe –5 em Diplomacia). Se você desrespeitar seu tabu, fica fatigado por um dia (mesmo que seja imune a essa condição). Nenhum efeito pode curar essa condição. Se no dia seguinte continuar desrespeitando o tabu, você fica exausto. Se no terceiro dia não mudar seu comportamento, você morre.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'tabu_percepcao_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => -5],
            ],
        ]);

        Power::create([
            'id' => 16201,
            'name' => 'Duende Aleatório',
            'description' => 'Se criar seu duende de forma aleatória, você começa o jogo com +2 PM — um presente do Deus do Caos por sua ousadia.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => 'duende_aleatorio_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16202,
            'name' => 'Essência Feérica',
            'description' => 'Você é uma criatura do tipo espírito, recebe visão na penumbra e pode falar com animais livremente.',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'essencia_feerica_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6]],
            ],
        ]);

        Power::create([
            'id' => 16203,
            'name' => 'Magia Instintiva',
            'description' => 'Você pode usar Sabedoria no lugar de seu atributo-chave de magias arcanas e Misticismo. Além disso, quando lança uma magia, você recebe +1 PM para gastar em seus aprimoramentos (não cumulativo com outros efeitos que fornecem PM para aprimoramentos, como bolsa de pó).',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'magia_instintiva_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16204],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16205],
            ],
        ]);

        Power::create([
            'id' => 16204,
            'name' => 'Magia Instintiva (Sabedoria)',
            'description' => 'Você pode usar Sabedoria no lugar de seu atributo-chave de magias arcanas e Misticismo.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => 'magia_instintiva_01.webp',
            'applies_when' => ['spell_types' => ['arcana']],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 20, 'value' => 'knw'],
                ['tag' => 'spell_key_attribute_override', 'op' => 'set', 'value' => 'knw'],
            ],
        ]);

        Power::create([
            'id' => 16205,
            'name' => 'Magia Instintiva (PM)',
            'description' => 'Quando lança uma magia, você recebe +1 PM para gastar em seus aprimoramentos (não cumulativo com outros efeitos que fornecem PM para aprimoramentos, como bolsa de pó).',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'magia_instintiva_01.webp',
            'effects' => [
                ['tag' => 'spell_enhancement_free_pm', 'op' => 'add', 'value' => 1, 'stack_group' => 'spell_enhancement_free_pm'],
            ],
        ]);

        Power::create([
            'id' => 16206,
            'name' => 'Sentidos Místicos',
            'description' => 'Você está sempre sob o efeito básico da magia Visão Mística. (Seus olhos brilham com uma luz azul e passam a enxergar auras mágicas. Este efeito é similar ao uso de Misticismo para detectar magia, mas você detecta todas as auras mágicas em alcance médio e recebe todas as informações sobre elas sem gastar ações. Além disso, você pode gastar uma ação de movimento para descobrir se uma criatura que possa perceber em alcance médio é capaz de lançar magias e qual a aura gerada pelas magias de círculo mais alto que ela pode lançar.)',
            'source' => 'race_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'sentidos_misticos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6]],
            ],
        ]);

        Power::create([
            'id' => 16207,
            'name' => 'Canção da Melancolia',
            'description' => 'Quando faz um teste de Vontade contra efeitos mentais, você rola dois dados e usa o pior resultado.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'cancao_da_melancolia_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6]],
            ],
            'effects' => [
                ['tag' => 'disadvantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 29],
            ],
        ]);

        Power::create([
            'id' => 16208,
            'name' => 'Força dos Titãs',
            'description' => 'Quando acerta um ataque corpo a corpo ou de arremesso, você pode gastar 1 PM. Se fizer isso, sempre que rolar o resultado máximo em um dado de dano da arma, role um dado extra, até um limite de dados extras igual à sua Força.',
            'source' => 'race_granted',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => 'forca_dos_titas_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee', 'thrown']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [10]],
            ],
            'effects' => [
                ['tag' => 'extra_die_on_max', 'op' => 'grant', 'value' => 'str'],
            ],
        ]);

        Power::create([
            'id' => 16209,
            'name' => 'Meio-Gigante',
            'description' => 'Você é uma criatura do tipo humanoide (gigante). Seu tamanho é Grande e você pode usar Força como atributo-chave de Intimidação.',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => 'meio_gigante_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [10]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 14, 'value' => 'str'],
            ],
        ]);

        Power::create([
            'id' => 16210,
            'name' => 'Infância entre os Pequenos',
            'description' => 'Você se torna treinado em uma perícia a sua escolha.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'infancia_entre_os_pequenos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [10]],
            ],
            'effects' => [
                ['tag' => 'free_skills_choice', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16211,
            'name' => 'Festeiro Feérico',
            'description' => 'Você é uma criatura do tipo espírito, recebe visão na penumbra e +2 em Atuação e Fortitude.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'festeiro_feerico_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [45]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 4, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16212,
            'name' => 'Instrumentista Mágico',
            'description' => 'Se estiver empunhando um instrumento musical, você pode lançar as magias Amedrontar, Enfeitiçar, Hipnotismo e Sono (atributo-chave Carisma). Caso aprenda novamente uma dessas magias, seu custo diminui em –1 PM.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'instrumentalista_magico_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [45]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 13],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 27],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 28],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 4],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 16213,
            'name' => 'Marrada',
            'description' => 'Você possui uma arma natural de marrada (dano 1d6, crítico x2, impacto). Uma vez por rodada, quando usa a ação agredir para atacar com outra arma, pode gastar 1 PM para fazer um ataque corpo a corpo extra com a marrada.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => 'satiro_marrada_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [45]],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1006],
            ],
        ]);

        Power::create([
            'id' => 16214,
            'name' => 'Pernas Caprinas',
            'description' => 'Seu deslocamento é 12m e você pode usar Destreza como atributo-chave de Atletismo (em vez de Força).',
            'source' => 'race_granted',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => 'pernas_caprinas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [45]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 3, 'value' => 'dex'],
            ],
        ]);

        Power::create([
            'id' => 16215,
            'name' => 'Chassi',
            'description' => 'Você leva um dia para vestir ou remover uma armadura (pois precisa acoplar as peças dela a seu chassi). Entretanto, por ser acoplada, sua armadura não conta no limite de itens que você pode usar (mas você só pode usar uma armadura).',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
        ]);

        Power::create([
            'id' => 16216,
            'name' => 'Chassi de Barro',
            'description' => 'Constituição +2. Seu deslocamento não é afetado por terreno difícil e passa automaticamente em testes de Acrobacia para passar por espaços apertados. Se permanecer mais de um dia sem contato com água, você não recupera PM com descanso até voltar para a água.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 2],
                ['tag' => 'resting_pm_recovery', 'op' => 'set', 'value' => 0],
            ],
        ]);

        Power::create([
            'id' => 16217,
            'name' => 'Chassi de Bronze',
            'description' => '+1 em dois atributos. Seu deslocamento não é reduzido por armaduras pesadas ou excesso de carga. Sua armadura não é acoplada em seu corpo; você pode removê-la e colocá-la no tempo normal, mas ela conta em seu limite de itens vestidos.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'choice_bonus_to_attributes', 'op' => 'add', 'value' => 2],
                ['tag' => 'waive_heavy_armor_movement_penalty', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 16218,
            'name' => 'Chassi de Carne',
            'description' => 'Constituição +2, Força +1, Carisma –1. Seu deslocamento é 6m, mas não é reduzido por uso de armadura ou excesso de carga. Você recebe imunidade a metamorfose e trevas, mas não pode escolher elemental (água ou fogo) ou vapor como sua fonte de energia e dano mágico de fogo e frio o deixa lento por 1d4 rodadas.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => -1],
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 6],
                ['tag' => 'waive_heavy_armor_movement_penalty', 'op' => 'grant'],
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'darkness'],
                ['tag' => 'block_spell', 'op' => 'grant', 'spell_id' => 30],
                ['tag' => 'inflict_condition_on_damage_type_received', 'op' => 'grant', 'value' => 10, 'damage_reduction_type' => 'fire'],
                ['tag' => 'inflict_condition_on_damage_type_received', 'op' => 'grant', 'value' => 10, 'damage_reduction_type' => 'cold'],
            ],
        ]);

        Power::create([
            'id' => 16219,
            'name' => 'Chassi Dourado (DH)',
            'description' => 'Carisma +2, Força +1. Você pode gastar 1 PM para marcar uma criatura em alcance curto como culpada. Até o fim da cena, ou até você usar esta habilidade em outra criatura, você sempre sabe onde a criatura culpada está e, uma vez por rodada, um de seus ataques contra essa criatura causa +1d6 pontos de dano de luz.',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16220],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16221],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16222],
            ],
        ]);

        Power::create([
            'id' => 16220,
            'name' => 'Chassi Dourado (Atributos)',
            'description' => 'Carisma +2, Força +1.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16221,
            'name' => 'Chassi Dourado (Marcar Culpado)',
            'description' => 'Você pode gastar 1 PM para marcar uma criatura em alcance curto como culpada. Até o fim da cena, ou até você usar esta habilidade em outra criatura, você sempre sabe onde a criatura culpada está.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'pm_cost' => 1,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 16222,
            'name' => 'Chassi Dourado (Dano de Luz)',
            'description' => 'Uma vez por rodada, um de seus ataques contra a criatura culpada causa +1d6 pontos de dano de luz.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['active_power_id' => 16221],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'light'],
            ],
        ]);

        Power::create([
            'id' => 16223,
            'name' => 'Chassi de Espelhos',
            'description' => 'Carisma +2, Sabedoria +1, Constituição –1. Quando uma criatura em alcance curto usa uma habilidade de classe que você possa ver, você pode gastar 1 PM para copiar essa habilidade. Até o fim do seu próximo turno, você pode usá-la como uma habilidade de raça (se ela usar um atributo para algo, use seu Carisma). Se espelhar outra habilidade, você perde a anterior. <br><br>No APP, adicione a habilidade manualmente em Adicionar Poder.',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16224],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16225],
            ],
        ]);

        Power::create([
            'id' => 16224,
            'name' => 'Chassi de Espelhos (Atributos)',
            'description' => 'Carisma +2, Sabedoria +1, Constituição –1.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 16225,
            'name' => 'Chassi de Espelhos (Copiar Habilidade)',
            'description' => 'Quando uma criatura em alcance curto usa uma habilidade de classe que você possa ver, você pode gastar 1 PM para copiar essa habilidade. Até o fim do seu próximo turno, você pode usá-la como uma habilidade de raça (se ela usar um atributo para algo, use seu Carisma). Se espelhar outra habilidade, você perde a anterior. <br><br>No APP, adicione a habilidade manualmente em Adicionar Poder.',
            'source' => 'power_granted',
            'usability' => 'active',
            'pm_cost' => 1,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 16226,
            'name' => 'Chassi de Ferro',
            'description' => 'Força +1 e Constituição +1. Seu deslocamento é 6m, mas não é reduzido por uso de armadura ou excesso de carga. Você recebe +2 na Defesa, mas possui penalidade de armadura –2.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 6],
                ['tag' => 'waive_heavy_armor_movement_penalty', 'op' => 'grant'],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'chassi_armor_penalty', 'op' => 'grant', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16227,
            'name' => 'Chassi de Gelo Eterno',
            'description' => 'Constituição +2. Seu deslocamento é 6m, mas não é reduzido por uso de armadura ou excesso de carga. Você recebe imunidade a frio, redução de fogo 10, mas não pode escolher elemental (fogo) ou vapor como sua fonte de energia.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 6],
                ['tag' => 'waive_heavy_armor_movement_penalty', 'op' => 'grant'],
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'cold'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 10, 'damage_reduction_type' => 'fire'],
            ],
        ]);

        Power::create([
            'id' => 16228,
            'name' => 'Chassi de Pedra',
            'description' => 'Constituição +2. Você não pode correr e seu deslocamento é 6m, mas não é reduzido por uso de armadura ou excesso de carga. Você recebe redução de corte, fogo e perfuração 5.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 6],
                ['tag' => 'waive_heavy_armor_movement_penalty', 'op' => 'grant'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'slashing'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'fire'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'piercing'],
            ],
        ]);

        Power::create([
            'id' => 16229,
            'name' => 'Chassi de Sucata',
            'description' => 'Força +1 e Constituição +1. Seu deslocamento é 6m, mas não é reduzido por uso de armadura ou excesso de carga. Quando recebe cuidados prolongados com a perícia Ofício (artesão), sua recuperação de PV aumenta em +2 por nível nesse dia (ao invés de +1).',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_movement', 'op' => 'set', 'value' => 6],
                ['tag' => 'waive_heavy_armor_movement_penalty', 'op' => 'grant'],
                ['tag' => 'resting_bonus_pv', 'op' => 'add_per_level', 'value' => 1, 'per_character_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 16230,
            'name' => 'Chassi Mashin',
            'description' => '+1 em dois atributos a sua escolha. Você se torna treinado em duas perícias a sua escolha, e pode substituir uma dessas perícias por uma maravilha mecânica. Entretanto, você é sempre Médio.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'choice_bonus_to_attributes', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16231,
            'name' => 'Maravilha Mecânica',
            'description' => 'Se escolher uma maravilha mecânica, você recebe uma das habilidades a seguir. Uma vez por patamar, você pode escolher uma maravilha mecânica no lugar de um poder de classe. <br><br>No APP, escolher esses poderes no patamar certo fica por sua conta!',
            'source' => 'specific',
            'usability' => 'roleplay',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
        ]);

        Power::create([
            'id' => 16232,
            'name' => 'Criatura Artificial',
            'description' => 'Você é uma criatura do tipo construto. Recebe visão no escuro e imunidade a efeitos de cansaço, metabólicos e de veneno. Além disso, não precisa respirar, alimentar-se ou dormir, mas não se beneficia de cura mundana e de itens da categoria alimentação. Você precisa ficar inerte por oito horas por dia para recarregar sua fonte de energia. Se fizer isso, recupera PV e PM por descanso em condições normais (golens não são afetados por condições boas ou ruins de descanso). Por fim, a perícia Cura não funciona em você, mas Ofício (artesão) pode ser usada no lugar dela.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16233],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16234],
            ],
        ]);

        Power::create([
            'id' => 16233,
            'name' => 'Criatura Artificial (Construto)',
            'description' => 'Você é uma criatura do tipo construto. Recebe visão no escuro e imunidade a efeitos de cansaço, metabólicos e de veneno. Além disso, não precisa respirar, alimentar-se ou dormir, mas não se beneficia de cura mundana e de itens da categoria alimentação. Por fim, a perícia Cura não funciona em você, mas Ofício (artesão) pode ser usada no lugar dela.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'poison'],
                ['tag' => 'condition_type_immunity', 'op' => 'grant', 'value' => 'tired'],
                ['tag' => 'condition_type_immunity', 'op' => 'grant', 'value' => 'metabolism'],
            ],
        ]);

        Power::create([
            'id' => 16234,
            'name' => 'Criatura Artificial (Recarga)',
            'description' => 'Você precisa ficar inerte por oito horas por dia para recarregar sua fonte de energia. Se fizer isso, recupera PV e PM por descanso em condições normais (golens não são afetados por condições boas ou ruins de descanso).',
            'source' => 'power_granted',
            'usability' => 'resting',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'resting', 'op' => 'set', 'value' => 0],
            ],
        ]);

        Power::create([
            'id' => 16235,
            'name' => 'Fonte de Energia (Alquímica)',
            'description' => 'Uma mistura alquímica gera a energia necessária à sua vida. Você pode gastar uma ação padrão para ingerir um item alquímico qualquer; se fizer isso, recupera 1 PM.',
            'source' => 'specific',
            'usability' => 'active',
            'action_cost' => 'standard',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'restore_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16236,
            'name' => 'Fonte de Energia (Elemental - Água)',
            'description' => 'Você possui um espírito elemental preso em seu corpo. Você é imune a dano de frio. Se fosse sofrer dano mágico deste tipo, em vez disso cura PV em quantidade igual à metade do dano.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'cold'],
                ['tag' => 'change_damage_to_heal_half', 'op' => 'grant', 'value' => 'cold'],
            ],
        ]);

        Power::create([
            'id' => 16237,
            'name' => 'Fonte de Energia (Elemental - Ar)',
            'description' => 'Você possui um espírito elemental preso em seu corpo. Você é imune a dano de eletricidade. Se fosse sofrer dano mágico deste tipo, em vez disso cura PV em quantidade igual à metade do dano.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'electricity'],
                ['tag' => 'change_damage_to_heal_half', 'op' => 'grant', 'value' => 'electricity'],
            ],
        ]);

        Power::create([
            'id' => 16238,
            'name' => 'Fonte de Energia (Elemental - Fogo)',
            'description' => 'Você possui um espírito elemental preso em seu corpo. Você é imune a dano de fogo. Se fosse sofrer dano mágico deste tipo, em vez disso cura PV em quantidade igual à metade do dano.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'fire'],
                ['tag' => 'change_damage_to_heal_half', 'op' => 'grant', 'value' => 'fire'],
            ],
        ]);

        Power::create([
            'id' => 16239,
            'name' => 'Fonte de Energia (Elemental - Terra)',
            'description' => 'Você possui um espírito elemental preso em seu corpo. Você é imune a dano de ácido. Se fosse sofrer dano mágico deste tipo, em vez disso cura PV em quantidade igual à metade do dano.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'acid'],
                ['tag' => 'change_damage_to_heal_half', 'op' => 'grant', 'value' => 'acid'],
            ],
        ]);

        Power::create([
            'id' => 16240,
            'name' => 'Fonte de Energia (Sagrada)',
            'description' => 'Você foi animado por um texto ou símbolo sagrado depositado em seu corpo. Você pode lançar uma magia divina de 1º círculo a sua escolha (atributo-chave Sabedoria). Caso aprenda novamente essa magia, seu custo diminui em –1 PM. Alguém treinado em Religião pode trocar essa magia com um ritual que demora um dia e exige o gasto de um pergaminho mágico com outra magia de 1° círculo. <br><br>No APP, remova a magia e adicione a nova manualmente.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'limit_spell_choices', 'op' => 'set', 'spell_circle' => 1, 'spell_type' => 'divina'],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'knw'],
            ],
        ]);

        Power::create([
            'id' => 16241,
            'name' => 'Fonte de Energia (Vapor)',
            'description' => 'Seu corpo é movido por vapor e engrenagens. Você é imune a dano de fogo; se fosse sofrer dano desse tipo, em vez disso seu deslocamento aumenta em 4,5m por 1 rodada. Entretanto, dano de frio deixa-o lento por 1 rodada. Você pode gastar uma ação padrão e PM para soprar um jato de vapor escaldante em um cone de 4,5m. Criaturas na área sofrem 1d6 pontos de dano de fogo por PM gasto e ficam em chamas (Ref CD Con reduz à metade e evita a condição).',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'damage_immunity', 'op' => 'grant', 'damage_reduction_type' => 'fire'],
                ['tag' => 'change_damage_to_movement_boost', 'op' => 'grant', 'value' => 4.5, 'damage_reduction_type' => 'fire'],
                ['tag' => 'inflict_condition_on_damage_type_received', 'op' => 'grant', 'value' => 10, 'damage_reduction_type' => 'cold'],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 3005],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'con'],
            ],
        ]);

        Power::create([
            'id' => 16242,
            'name' => 'Propósito de Criação',
            'description' => 'Você foi construído “pronto” para um propósito específico e não teve uma infância. Você não tem direito a escolher uma origem, mas recebe um poder geral a sua escolha.',
            'source' => 'race_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'general_power_choice', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 16243,
            'name' => 'Golem (Pequeno)',
            'description' => 'Você é Pequeno (+2 em testes de Furtividade, –2 em testes de manobra) e recebe +1 em Destreza.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_size', 'op' => 'set', 'value' => -1],
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16244,
            'name' => 'Golem (Médio)',
            'description' => 'Você é Médio (sem modificadores por tamanho).',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_size', 'op' => 'set', 'value' => 0],
            ],
        ]);

        Power::create([
            'id' => 16245,
            'name' => 'Golem (Grande)',
            'description' => 'Você é Grande (–2 em testes de Furtividade, +2 em testes de manobra, usa armas aumentadas) e sofre –1 em Destreza.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [61]],
            ],
            'effects' => [
                ['tag' => 'mod_size', 'op' => 'set', 'value' => 1],
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 16246,
            'name' => 'Herança de Al-Gazara',
            'description' => 'Devido à presença do puro caos primordial de Nimb em seu sangue, você recebe +1 em um atributo aleatório.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'choice_bonus_to_attributes', 'op' => 'add', 'value' => 1],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        //TODO fix this when we add druida
        Power::create([
            'id' => 16247,
            'name' => 'Herança de Arbória',
            'description' => 'Como parte do Grande Ciclo de Allihanna, você recebe a habilidade Forma Selvagem para uma única forma, escolhida entre Ágil, Sorrateira e Veloz. Caso adquira essa habilidade novamente, o custo dessa forma diminui em –1 PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        //TODO fix this when we add druida
        Power::create([
            'id' => 16248,
            'name' => 'Herança de Chacina',
            'description' => 'Pela ferocidade de Megalokk, você recebe a habilidade Forma Selvagem para uma única forma, escolhida entre Feroz e Resistente. Caso adquira essa habilidade novamente, o custo dessa forma diminui em –1 PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16249,
            'name' => 'Herança de Deathok',
            'description' => 'A mudança constante faz parte de sua alma. Você recebe +2 em duas perícias a sua escolha. A cada manhã, você pode trocar essas perícias. <br><br>No APP, marque esse poder quando for rolar a perícia escolhida.',
            'source' => 'specific',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16250,
            'name' => 'Herança de Drashantyr',
            'description' => 'Graças ao poder elemental dos dragões, você recebe +1 PM e redução de ácido, eletricidade, fogo, frio, luz e trevas 5.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 1],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'acid'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'electricity'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'fire'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'cold'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'light'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'darkness'],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16251,
            'name' => 'Herança de Kundali',
            'description' => 'Pelo espírito protetor, mas também opressor, de Tauron, você recebe +2 na Defesa e em testes de manobras de combate.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16252,
            'name' => 'Herança de Magika (Carisma)',
            'description' => 'Você aprende e pode lançar uma magia arcana de 1º círculo a sua escolha (atributo-chave Carisma, a sua escolha). Caso aprenda novamente essa magia, seu custo diminui em –1 PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'limit_spell_choices', 'op' => 'set', 'spell_circle' => 1, 'spell_type' => 'arcana'],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16253,
            'name' => 'Herança de Magika (Inteligência)',
            'description' => 'Você aprende e pode lançar uma magia arcana de 1º círculo a sua escolha (atributo-chave Inteligência, a sua escolha). Caso aprenda novamente essa magia, seu custo diminui em –1 PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'limit_spell_choices', 'op' => 'set', 'spell_circle' => 1, 'spell_type' => 'arcana'],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'int'],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16254,
            'name' => 'Herança de Nivenciuén (Sangue Mágico)',
            'description' => 'Mesmo que o Reino de Glórienn tenha sofrido um destino terrível, a antiga soberania élfica ainda permeia seu sangue. Você recebe +2 em Misticismo e Sangue Mágico.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 2],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16010],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16255,
            'name' => 'Herança de Nivenciuén (Graça de Glórienn)',
            'description' => 'Mesmo que o Reino de Glórienn tenha sofrido um destino terrível, a antiga soberania élfica ainda permeia seu sangue. Você recebe +2 em Misticismo e Graça de Glórienn.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 2],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16009],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16256,
            'name' => 'Herança de Odisseia',
            'description' => 'Sua alma tocada por Valkaria está sempre preparada para problemas! Você recebe +2 em Iniciativa e Percepção, e sua capacidade de carga aumenta em 2 espaços.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 2],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        //TODO fix this when we add informing rolls
        Power::create([
            'id' => 16257,
            'name' => 'Herança de Ordine',
            'description' => 'As forças da lei e ordem de Khalmyr afetam suas ações. Você recebe +2 em Intuição, em Investigação e em testes sem rolagens de dados (ao escolher 0, 10 ou 20).',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 2],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        //TODO fix this when we add informing rolls
        Power::create([
            'id' => 16258,
            'name' => 'Herança de Pelágia',
            'description' => 'Mesmo nas situações mais desesperadoras, seu espírito se mantém plácido e imperturbável como o próprio Oceano. Escolha três perícias. Com elas, você pode gastar 1 PM para escolher 10 em qualquer situação, exceto testes de ataque. <br><br>No APP, use o poder para gastar os PMs. A rolagem com 10 fixo fica por sua conta!',
            'source' => 'specific',
            'usability' => 'active',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16259,
            'name' => 'Herança de Pyra',
            'description' => 'Em algum lugar dentro de você, sempre existe uma segunda chance. Quando faz um teste de resistência ou um teste de atributo para remover uma condição, você pode gastar 2 PM para rolá-lo novamente.',
            'source' => 'specific',
            'usability' => 'active',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16260,
            'name' => 'Herança de Ramknal',
            'description' => 'Escolha duas perícias entre Acrobacia, Enganação, Furtividade, Jogatina e Ladinagem. Quando faz um teste da perícia escolhida, você pode gastar 2 PM para receber +5 nesse teste. <br><br>No APP, marque o poder para usar nas perícias que escolheu.',
            'source' => 'specific',
            'usability' => 'roll_active',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 1, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 17, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 18, 'value' => 5],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16261,
            'name' => 'Herança de Serena',
            'description' => 'Pela proteção de Marah, você recebe +2 na Defesa e em testes de resistência contra oponentes aos quais não tenha causado dano, perda de PV ou condições (exceto enfeitiçado, fascinado e pasmo) nessa cena.',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16262],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16263],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16262,
            'name' => 'Herança de Serena (Defesa)',
            'description' => '+2 na Defesa contra oponentes aos quais não tenha causado dano, perda de PV ou condições (exceto enfeitiçado, fascinado e pasmo) nessa cena.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16263,
            'name' => 'Herança de Serena (Resistência)',
            'description' => '+2 em testes de resistência contra oponentes aos quais não tenha causado dano, perda de PV ou condições (exceto enfeitiçado, fascinado e pasmo) nessa cena.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16264,
            'name' => 'Herança de Skerry',
            'description' => 'Você carrega a força de criatividade. Quando faz um teste de Ofício, pode gastar 1 PM para ser treinado na perícia em questão ou para rolar dois dados e usar o melhor resultado.',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16265],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16266],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16265,
            'name' => 'Herança de Skerry (Treinar)',
            'description' => 'Gaste 1 PM para ser treinado em Ofício em um teste.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'applies_when' => ['skill_not_trained' => true],
            'effects' => [
                ['tag' => 'skill', 'op' => 'trains', 'skill_id' => 22],
            ],
        ]);

        Power::create([
            'id' => 16266,
            'name' => 'Herança de Skerry (Vantagem)',
            'description' => 'Gaste 1 PM para rolar dois dados em um teste de Ofício e usar o melhor resultado.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'applies_when' => ['skill_trained' => true],
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 22],
            ],
        ]);

        Power::create([
            'id' => 16267,
            'name' => 'Herança de Solaris',
            'description' => 'Pelo poder de Azgher, durante o dia você recebe +1 em todos os testes de perícia. Se estiver diretamente sob a luz do sol, esse bônus aumenta para +2.',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16268],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16269],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16268,
            'name' => 'Herança de Solaris (Dia)',
            'description' => '+1 em todos os testes de perícia durante o dia.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 1, 'stack_group' => 'heranca_solaris'],
            ],
        ]);

        Power::create([
            'id' => 16269,
            'name' => 'Herança de Solaris (Sob o Sol)',
            'description' => '+2 em todos os testes de perícia enquanto estiver diretamente sob a luz do sol.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 2, 'stack_group' => 'heranca_solaris'],
            ],
        ]);

        Power::create([
            'id' => 16270,
            'name' => 'Herança de Sombria',
            'description' => 'Pelo poder de Tenebra, durante a noite você recebe +1 em todos os testes de perícia. Se estiver num local sem nenhuma iluminação artificial (como tochas ou magia), esse bônus aumenta para +2.',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16271],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16272],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16271,
            'name' => 'Herança de Sombria (Noite)',
            'description' => '+1 em todos os testes de perícia durante a noite.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 1, 'stack_group' => 'heranca_sombria'],
            ],
        ]);

        Power::create([
            'id' => 16272,
            'name' => 'Herança de Sombria (Escuridão)',
            'description' => '+2 em todos os testes de perícia em um local sem nenhuma iluminação artificial (como tochas ou magia).',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 2, 'stack_group' => 'heranca_sombria'],
            ],
        ]);

        Power::create([
            'id' => 16273,
            'name' => 'Herança de Sora',
            'description' => 'Os honrados espíritos ancestrais de Lin-Wu abençoam sua perseverança. Você recebe +2 em Nobreza, Vontade e em testes de perícia estendidos (incluindo contra perigos complexos).',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        //TODO automatic tests against illusions not modeled
        Power::create([
            'id' => 16274,
            'name' => 'Herança de Terápolis',
            'description' => 'Você recebe +2 em Intuição e Vontade, e pode fazer testes dessas perícias contra ilusões automaticamente, sem precisar interagir com elas.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16275,
            'name' => 'Herança de Venomia',
            'description' => 'Ser escorregadio como Sszzaas faz parte de sua natureza, mesmo que você não goste disso. Você recebe +2 em Enganação e em testes para evitar manobras de combate e efeitos de movimento.',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16276],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16277],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16276,
            'name' => 'Herança de Venomia (Enganação)',
            'description' => '+2 em Enganação.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16277,
            'name' => 'Herança de Venomia (Luta)',
            'description' => '+2 em testes de Luta para evitar manobras de combate e em testes de resistência contra efeitos de movimento.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 16278,
            'name' => 'Herança de Vitalia',
            'description' => 'A força da vida corre intensa em seu sangue. Você recebe +5 PV por patamar e sua recuperação de pontos de vida com descanso aumenta em uma categoria.',
            'source' => 'specific',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16279],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16280],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16279,
            'name' => 'Herança de Vitalia (PV)',
            'description' => '+5 PV por patamar.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_max_pv', 'op' => 'add', 'value' => 5],
                ['tag' => 'mod_max_pv', 'op' => 'add_per_patamar', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 16280,
            'name' => 'Herança de Vitalia (Descanso)',
            'description' => 'Sua recuperação de pontos de vida com descanso aumenta em uma categoria.',
            'source' => 'power_granted',
            'usability' => 'resting',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'resting_pv_recovery', 'op' => 'add_step', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 16281,
            'name' => 'Herança de Werra (Marciais)',
            'description' => 'Você possui um conhecimento intuitivo para armas. Você recebe +1 em testes de ataque e proficiência com armas marciais.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 1],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 40],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);

        Power::create([
            'id' => 16282,
            'name' => 'Herança de Werra (Exóticas)',
            'description' => 'Você possui um conhecimento intuitivo para armas. Você recebe proficiência em duas armas exóticas. <br><br>No APP, adicione manualmente as proficiências nas armas exóticas que escolher.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
            'effects' => [
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16065],
                ['tag' => 'removes_power', 'op' => 'grant', 'power_id' => 16066],
            ],
        ]);
    }
}
