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
                ['type' => 'race', 'race_ids' => [1,12,21,42]],
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
                ['type' => 'race', 'race_ids' => [22,7]],
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
                ['type' => 'race', 'race_ids' => [25]],
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
            'usability' => 'roleplay',
            'icon_file_name' => 'devagar_e_sempre_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
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
                ['type' => 'race', 'race_ids' => [20]],
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
                ['type' => 'race', 'race_ids' => [25]],
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
                ['type' => 'race', 'race_ids' => [25]],
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
                ['tag' => 'power_chosen_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
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
                ['tag' => 'power_chosen_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);
    }
}
