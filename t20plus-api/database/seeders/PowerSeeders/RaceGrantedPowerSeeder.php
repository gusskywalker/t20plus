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

        // Shared across many races' own vessels (e.g. Anão's Conhecimento
        // das Rochas) instead of duplicated per race — only ever reached
        // via a cascade, never picked directly, so no prerequisites of its
        // own, same treatment as any other power_granted child.
        Power::create([
            'id' => 16000,
            'name' => 'Visão no Escuro',
            'description' => 'Você enxerga no escuro total perfeitamente dentro do alcance padrão (geralmente enxergando formas e cores atenuadas, tratado como luz fraca/penumbra).',
            'source' => 'power_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'visao_no_escuro_01.webp',
        ]);

        // Shared, same treatment as Visão no Escuro above — only ever
        // reached via a cascade, no prerequisites of its own.
        Power::create([
            'id' => 16011,
            'name' => 'Visão na Penumbra',
            'description' => 'Você enxerga em escuridão leve em alcance curto (exceto mágica). Ignora camuflagem leve por esse tipo de escuridão.',
            'source' => 'power_granted',
            'usability' => 'roleplay',
            'icon_file_name' => 'visao_na_penumbra_01.webp',
        ]);

        // Vessel — only shows up to be picked, no own effects. Grants the
        // shared Visão no Escuro plus its own roll_active child below.
        Power::create([
            'id' => 16001,
            'name' => 'Conhecimento das Rochas',
            'description' => 'Você recebe visão no escuro e +2 em testes de Percepção e Sobrevivência realizados no subterrâneo.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'conhecimento_das_rochas_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16000],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16002],
            ],
        ]);

        Power::create([
            'id' => 16002,
            'name' => 'Conhecimento das Rochas',
            'description' => 'Você recebe visão no escuro e +2 em testes de Percepção e Sobrevivência realizados no subterrâneo.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'conhecimento_das_rochas_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        // The 6m itself is already Anão's own base_movement (RaceSeeder) —
        // nothing to hook up here besides that. The armor/encumbrance
        // exception has no such penalty system in the app at all yet, so
        // no effects for now, same treatment as any other pure-flavor
        // condition power.
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

        // add_per_level (value 1, per_character_level 1) already contributes its
        // own +1 starting at level 1 — the flat add is 2, not 3, so the
        // two combined land exactly on "+3 at level 1, +1 every level
        // after" (level + 2) instead of double-counting level 1.
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

        // grant_or_reduce_spell_pm_cost_by_1 — see tag-system.md's own
        // section on this pipeline. Not level-picked, so ManagesPowers::
        // grantPower() lands other_source_spell_ids on the character's
        // first level instead of a level row of its own.
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

        // other_sources_state / on_other_sources_satisfied — see
        // tag-system.md's own section on this pipeline. "pode se comunicar
        // com animais... pode usar Adestramento" is a pure skill-use
        // permission, no numeric effect of its own — only the "receba
        // novamente" clause has a real bonus.
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

        // The 12m itself is already Elfo's own base_movement (RaceSeeder) —
        // nothing to hook up here besides that, same treatment as Anão's
        // own Devagar e Sempre.
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

        // Vessel — only shows up to be picked, no own effects. Grants the
        // shared Visão na Penumbra plus its own passive child below.
        Power::create([
            'id' => 16012,
            'name' => 'Sentidos Élficos',
            'description' => 'Você recebe visão na penumbra e +2 em Misticismo e Percepção.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => 'sentidos_elficos_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [7]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16011],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16013],
            ],
        ]);

        Power::create([
            'id' => 16013,
            'name' => 'Sentidos Élficos',
            'description' => 'Você recebe visão na penumbra e +2 em Misticismo e Percepção.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => 'sentidos_elficos_01.webp',
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
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16000],
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
            'effects' => [
                ['tag' => 'free_skills_choice', 'op' => 'add', 'value' => 2],
                ['tag' => 'free_skill_and_general_power_choice', 'op' => 'grant'],
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
            'icon_file_name' => null,
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
            'icon_file_name' => null,
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
            'icon_file_name' => null,
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
            'icon_file_name' => null,
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
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [16]],
            ],
        ]);
    }
}
