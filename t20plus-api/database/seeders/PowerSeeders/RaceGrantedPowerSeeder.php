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
            'icon_file_name' => null,
        ]);

        // Vessel — only shows up to be picked, no own effects. Grants the
        // shared Visão no Escuro plus its own roll_active child below.
        Power::create([
            'id' => 16001,
            'name' => 'Conhecimento das Rochas',
            'description' => 'Você recebe visão no escuro e +2 em testes de Percepção e Sobrevivência realizados no subterrâneo.',
            'source' => 'race_granted',
            'usability' => 'vessel',
            'icon_file_name' => null,
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
            'description' => 'Você recebe +2 em testes de Percepção e Sobrevivência realizados no subterrâneo.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
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
            'icon_file_name' => null,
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
            'icon_file_name' => null,
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
            'icon_file_name' => null,
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
            'icon_file_name' => null,
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
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [5]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);
    }
}
