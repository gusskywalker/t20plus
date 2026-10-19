<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 15000 and 15999. If you are reading this comment it means you are adding a new power.
class CompanionGrantedPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 15000,
            'name' => "Familiar Aquin'ne: Natação",
            'description' => "Um aquin'ne familiar concede deslocamento de natação 9m e permite lançar magias e respirar debaixo d'água.",
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_aquinne_01.webp',
        ]);

        Power::create([
            'id' => 15001,
            'name' => 'Familiar Asa-Assassina: Sangramento',
            'description' => 'Permite que você gaste 1 PM quando causa dano de corte ou perfuração a uma criatura para deixá-la sangrando. <br><br>No APP, use o poder (gasta seu PM) e informe o mestre da condição causada.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'familiar_asa_de_aco_01.webp',
        ]);

        Power::create([
            'id' => 15002,
            'name' => 'Familiar Borboleta: CD de Vontade',
            'description' => 'A CD dos testes de Vontade para resistir a suas magias aumenta em +1.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_borboleta_01.webp',
            'applies_when' => ['spell_resistances' => ['vontade']],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15003,
            'name' => 'Familiar Chibi-Kabuto: Defesa de Magias',
            'description' => 'Aumenta em +1 o bônus na Defesa que você recebe por suas magias.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_chibi_kabuto_01.webp',
            'effects' => [
                ['tag' => 'mod_spell_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15004,
            'name' => 'Familiar Cobra: CD de Fortitude',
            'description' => 'A CD dos testes de Fortitude para resistir a suas magias aumenta em +1.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_cobra_01.webp',
            'applies_when' => ['spell_resistances' => ['fortitude']],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15005,
            'name' => 'Familiar Coruja: Alcance de Toque',
            'description' => 'Quando lança uma magia com alcance de toque, você pode pagar 1 PM para aumentar seu alcance para curto.',
            'source' => 'companion_granted',
            'usability' => 'spell_enhancement',
            'pm_cost' => 1,
            'icon_file_name' => 'familiar_coruja_01.webp',
            'applies_when' => ['spell_ranges' => ['toque']],
        ]);

        Power::create([
            'id' => 15006,
            'name' => 'Familiar Diabrete: PM Extra (Ilusão)',
            'description' => 'Um diabrete fornece +1 PM para gastar em aprimoramentos sempre que você lança uma magia de ilusão ou veneno.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_diabrete_01.webp',
            'applies_when' => ['spell_schools' => ['ilusao']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15007,
            'name' => 'Familiar Diabrete: PM Extra (Veneno)',
            'description' => 'Um diabrete fornece +1 PM para gastar em aprimoramentos sempre que você lança uma magia de ilusão ou veneno.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_diabrete_01.webp',
            'applies_when' => ['spell_damage_types' => ['poison']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15008,
            'name' => 'Familiar Dragão: CD e Custo',
            'description' => 'Suas magias que compartilhem o tipo de dano do sopro do dragão têm a CD aumentada em +2 e custam -1 PM (cumulativo com outras reduções).',
            'source' => 'companion_granted',
            'usability' => 'spell_enhancement',
            'pm_cost' => -1,
            'icon_file_name' => 'familiar_dragao_01.webp',
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15009,
            'name' => 'Familiar Falcão: Sem Surpresa',
            'description' => 'Você não pode ser surpreendido e nunca fica desprevenido.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_falcao_01.webp',
            'effects' => [
                ['tag' => 'block_condition', 'op' => 'grant', 'condition_id' => 21],
                ['tag' => 'block_condition', 'op' => 'grant', 'condition_id' => 3],
            ],
        ]);

        Power::create([
            'id' => 15010,
            'name' => 'Familiar Fuinha: Perícias',
            'description' => 'Você recebe +2 em Iniciativa e Investigação.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_fuinha_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15011,
            'name' => 'Familiar Gato: Visão e Furtividade',
            'description' => 'Você recebe visão no escuro e +2 em Furtividade.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_gato_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15012,
            'name' => 'Familiar Homúnculo: PM Extra (Transmutação)',
            'description' => 'Fornece +1 PM para gastar em aprimoramentos sempre que você lança uma magia de transmutação ou veneno.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_homunculo_01.webp',
            'applies_when' => ['spell_schools' => ['transmutacao']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15013,
            'name' => 'Familiar Homúnculo: PM Extra (Veneno)',
            'description' => 'Fornece +1 PM para gastar em aprimoramentos sempre que você lança uma magia de transmutação ou veneno.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_homunculo_01.webp',
            'applies_when' => ['spell_damage_types' => ['poison']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15014,
            'name' => 'Familiar Lagarto: CD de Reflexos',
            'description' => 'A CD dos testes de Reflexos para resistir a suas magias aumenta em +1.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_lagarato_01.webp',
            'applies_when' => ['spell_resistances' => ['reflexos']],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15015,
            'name' => 'Familiar Macaco: Item a Mão',
            'description' => 'Uma vez por rodada, você pode usar seu familiar para sacar ou guardar um item, ou para pegar um item solto Pequeno ou menor (1 espaço ou menos) em alcance curto e que ele consiga alcançar.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_macaco_01.webp',
        ]);

        Power::create([
            'id' => 15016,
            'name' => 'Familiar Morcego: Percepção as Cegas',
            'description' => 'Você adquire percepção às cegas em alcance curto.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'famliar_morcego_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11035],
            ],
        ]);

        Power::create([
            'id' => 15017,
            'name' => 'Familiar Pakk: Explosão de Chamas',
            'description' => 'Permite que você lance Explosão de Chamas. Caso aprenda essa magia, seu custo diminui em -1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_pakk_01.webp',
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 19],
            ],
        ]);

        Power::create([
            'id' => 15018,
            'name' => 'Familiar Papagaio Zumbi: CD e Pilotagem',
            'description' => 'Aumenta em +1 a CD dos testes para resistir a suas magias de necromancia e concede +2 em Pilotagem.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_papagaio_zumbi_01.webp',
            'applies_when' => ['spell_schools' => ['necromancia']],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 24, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15019,
            'name' => 'Familiar Rato: Fortitude',
            'description' => 'Você usa seu atributo-chave em Fortitude, no lugar de Constituição.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_rato_01.webp',
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 10, 'value' => 'key_attribute'],
            ],
        ]);

        Power::create([
            'id' => 15020,
            'name' => 'Familiar Sapo: PV',
            'description' => 'Você soma seu atributo-chave ao seu total de pontos de vida (cumulativo).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_sapo_01.webp',
            'effects' => [
                ['tag' => 'mod_max_pv', 'op' => 'add', 'value' => 'key_attribute'],
            ],
        ]);

        Power::create([
            'id' => 15021,
            'name' => 'Familiar Stahg: CD de Frio',
            'description' => 'Concede +1 na CD de suas magias de frio.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_stahg_01.webp',
            'applies_when' => ['spell_damage_types' => ['cold']],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15022,
            'name' => 'Familiar Tartaruga: Defesa e Natação',
            'description' => 'Você recebe +1 na Defesa e deslocamento de natação igual ao seu deslocamento básico.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_tartaruga_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15023,
            'name' => 'Familiar Tentacule: Item a Mão',
            'description' => 'Pode ser usado, uma vez por rodada, para sacar ou guardar um item, ou para pegar um item solto Pequeno ou menor (1 espaço ou menos) em alcance curto e que ele consiga alcançar.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_tentacule_01.webp',
        ]);

        Power::create([
            'id' => 15024,
            'name' => 'Familiar Terrier: Redução de Dano',
            'description' => 'Um terrier familiar concede redução de dano 2/impacto.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_terrier_01.webp',
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 2, 'damage_reduction_type' => 'bludgeoning'],
            ],
        ]);

        Power::create([
            'id' => 15025,
            'name' => "Familiar T'peel: Carga e Queda Suave",
            'description' => "Um t'peel familiar pode carregar 2 espaços de itens e permite que você lance Queda Suave.",
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_tpel_01.webp',
            'effects' => [
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 2],
                ['tag' => 'grant_spell', 'op' => 'grant', 'spell_id' => 20],
            ],
        ]);

        Power::create([
            'id' => 15026,
            'name' => 'Parceiro Aberrante: Pulso Mental (Iniciante)',
            'description' => 'Uma vez por rodada, você pode gastar 1 PM para disparar um pulso mental contra uma criatura em alcance curto; ela sofre 2d6 pontos de dano psíquico ou perde 1d4 PM, a sua escolha.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'comp_aberrante_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15027,
            'name' => 'Parceiro Aberrante: Pulso Mental (Veterano)',
            'description' => 'Você pode gastar 2 PM para causar 4d6 pontos de dano ou fazer a criatura perder 2d4 PM.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 2,
            'icon_file_name' => 'comp_aberrante_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15028,
            'name' => 'Parceiro Aberrante: Pulso Mental (Mestre)',
            'description' => 'Você pode gastar 4 PM para causar 6d6 pontos de dano ou fazer a criatura perder 3d4 PM.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 4,
            'icon_file_name' => 'comp_aberrante_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15029,
            'name' => 'Parceiro Adepto: Redução de Custo (1º Círculo)',
            'description' => 'O custo para lançar suas magias de 1º círculo diminui –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_adepto_iniciante_01.webp',
            'applies_when' => ['spell_circles' => [1]],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15030,
            'name' => 'Parceiro Adepto: Redução de Custo (2º Círculo)',
            'description' => 'O custo para lançar suas magias de 2º círculo diminui –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_adepto_veterano_01.webp',
            'applies_when' => ['spell_circles' => [2]],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15031,
            'name' => 'Parceiro Adepto: Redução de Custo Cumulativa',
            'description' => 'A redução no custo de suas magias de 1º e 2º círculo se torna cumulativa com outras reduções.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_adepto_mestre_01.webp',
            'applies_when' => ['spell_circles' => [1, 2]],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15029],
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15030],
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1, 'cumulative_with_other_pm_cost_reductions' => true],
            ],
        ]);

        Power::create([
            'id' => 15032,
            'name' => 'Parceiro Ajudante: Bônus em Perícias',
            'description' => 'Você recebe +2 em duas perícias, definidas pelo parceiro. Um ajudante não pode fornecer bônus em Luta ou Pontaria.<br><br>No APP, marque o poder do parceiro quando for rolar as perícias escolhidas.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_ajudante_iniciante_01.webp',
            'effects' => [
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15033,
            'name' => 'Parceiro Ajudante: Bônus em Perícias',
            'description' => 'Você recebe +2 em três perícias, definidas pelo parceiro. Um ajudante não pode fornecer bônus em Luta ou Pontaria.<br><br>No APP, marque o poder do parceiro quando for rolar as perícias escolhidas.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_ajudante_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15032],
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15034,
            'name' => 'Parceiro Ajudante: Bônus em Perícias',
            'description' => 'Você recebe +4 em três perícias, definidas pelo parceiro. Um ajudante não pode fornecer bônus em Luta ou Pontaria.<br><br>No APP, marque o poder do parceiro quando for rolar as perícias escolhidas.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_ajudante_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15033],
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 15035,
            'name' => 'Parceiro Arauto: Perícias',
            'description' => 'Você recebe +2 em Diplomacia, Intuição e Nobreza e pode fazer testes destas perícias mesmo sem ser treinado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_arauto_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 2],
            ],
        ]);

        //TODO fix this when we add nobre
        Power::create([
            'id' => 15036,
            'name' => 'Parceiro Arauto: Jogo da Corte',
            'description' => 'Você recebe também Jogo da Corte.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_arauto_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15037,
            'name' => 'Parceiro Arauto: Bônus em Perícias',
            'description' => 'No início de cada cena, você pode gastar 2 PM para conceder +2 em testes de perícias baseadas em Carisma para você e seus aliados até o final da cena. <br><br>No APP, seus aliados devem adicionar manualmente o bônus aos seus testes. Use o poder para gastar os PMs.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 2,
            'icon_file_name' => 'comp_arauto_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15038,
            'name' => 'Parceiro Artesão: Bônus em Ofício',
            'description' => 'Você recebe +2 em um Ofício (definido pelo aliado) e pode fazer testes desta perícia mesmo sem ser treinado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_artesao_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 22, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15039,
            'name' => 'Parceiro Artesão: Dois Ofícios',
            'description' => 'Como acima, mas para dois tipos de Ofício.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_artesao_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15040,
            'name' => 'Parceiro Artesão: Fabricação Rápida',
            'description' => 'Como acima, mas você fabrica itens em uma categoria de tempo menor (mínimo de 1 hora, não cumulativo com outros efeitos que reduzam o tempo de fabricação).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_artesao_mestre_01.webp',
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15041,
            'name' => 'Parceiro Assassino: Ataque Furtivo',
            'description' => 'Você pode usar a habilidade Ataque Furtivo +1d6. Se já possui a habilidade, o bônus é cumulativo.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_assassino_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15042,
            'name' => 'Parceiro Assassino: Flanquear',
            'description' => 'Além do Ataque Furtivo, fornece bônus por flanquear contra um inimigo por rodada.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_assassino_veterano_01.webp',
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15043,
            'name' => 'Parceiro Assassino: Ataque Furtivo Aprimorado',
            'description' => 'O dano do Ataque Furtivo muda para +2d6.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_assassino_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15044,
            'name' => 'Parceiro Atirador: Dano a Distância',
            'description' => 'Uma vez por rodada, você recebe +1d6 em uma rolagem de dano à distância.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_atirador_iniciante_01.webp',
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6'],
            ],
        ]);

        Power::create([
            'id' => 15045,
            'name' => 'Parceiro Atirador: Dano a Distância',
            'description' => 'Uma vez por rodada, você recebe +1d10 em uma rolagem de dano à distância.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_atirador_veterano_01.webp',
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15044],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d10'],
            ],
        ]);

        Power::create([
            'id' => 15046,
            'name' => 'Parceiro Atirador: Dano a Distância',
            'description' => 'Uma vez por rodada, você recebe +2d8 em uma rolagem de dano à distância.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_atirador_mestre_01.webp',
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15045],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d8'],
            ],
        ]);

        Power::create([
            'id' => 15047,
            'name' => 'Parceiro Besta de Carga: Capacidade de Carga',
            'description' => 'Pode carregar 10 espaços de itens.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_besta_carga_iniciante_01.webp',
            'effects' => [
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 15048,
            'name' => 'Parceiro Besta de Carga: Capacidade de Carga',
            'description' => 'Pode carregar 15 espaços de itens.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_besta_carga_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15047],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 15],
            ],
        ]);

        Power::create([
            'id' => 15049,
            'name' => 'Parceiro Besta de Carga: Capacidade de Carga',
            'description' => 'Pode carregar 20 espaços de itens.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_besta_carga_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15048],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 20],
            ],
        ]);

        Power::create([
            'id' => 15050,
            'name' => 'Parceiro Carregador: Capacidade de Carga',
            'description' => 'Pode carregar 2 espaços e usar qualquer item que esteja carregando e não exija um teste (como empunhar uma tocha ou aplicar um bálsamo restaurador).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_carregador_iniciante_01.webp',
            'effects' => [
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15051,
            'name' => 'Parceiro Carregador: Capacidade de Carga',
            'description' => 'Pode carregar 5 espaços e usar qualquer item que esteja carregando e não exija um teste (como empunhar uma tocha ou aplicar um bálsamo restaurador).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_carregador_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15050],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 15052,
            'name' => 'Parceiro Carregador: Capacidade de Carga',
            'description' => 'Pode carregar 10 espaços e usar qualquer item que esteja carregando e não exija um teste (como empunhar uma tocha ou aplicar um bálsamo restaurador).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_carregador_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15051],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 15053,
            'name' => 'Parceiro Combatente: Bônus de Ataque',
            'description' => 'Você recebe +2 em testes de ataque.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_combatente_iniciante_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15054,
            'name' => 'Parceiro Combatente: Bônus de Ataque',
            'description' => 'Você recebe +3 em testes de ataque.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_combatente_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15053],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15055,
            'name' => 'Parceiro Combatente: Bônus de Ataque',
            'description' => 'Você recebe +4 em testes de ataque.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_combatente_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15054],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 15056,
            'name' => 'Parceiro Combatente: Ataque Extra',
            'description' => 'Uma vez por rodada, você pode gastar 5 PM para fazer um ataque extra.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 5,
            'icon_file_name' => 'comp_combatente_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15057,
            'name' => 'Parceiro Destruidor: Dano Elemental (Iniciante)',
            'description' => 'Uma vez por rodada, como uma ação livre, você pode gastar 1 PM para causar 2d6 pontos de dano de ácido, eletricidade, fogo ou frio (de acordo com o parceiro) em um alvo em alcance curto.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'comp_destruidor_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15058,
            'name' => 'Parceiro Destruidor: Dano Elemental (Veterano)',
            'description' => 'Você pode gastar 2 PM para causar 4d6 pontos de dano.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 2,
            'icon_file_name' => 'comp_destruidor_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15059,
            'name' => 'Parceiro Destruidor: Dano em Área',
            'description' => 'Você pode gastar 4 PM para causar 6d6 pontos de dano em uma área de 6m de raio em alcance médio.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 4,
            'icon_file_name' => 'comp_destruidor_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15060,
            'name' => 'Parceiro Emissário: Perícias',
            'description' => 'Você recebe +2 em Diplomacia e Intuição.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_emissario_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15061,
            'name' => 'Parceiro Emissário: Bônus em Perícias',
            'description' => 'No início de cada cena, você pode gastar 2 PM para fornecer +2 em testes de perícias baseadas em Carisma para você e seus aliados até o final da cena.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 2,
            'icon_file_name' => 'comp_emissario_veterano_01.webp',
        ]);

        //TODO fix this when we add nobre
        Power::create([
            'id' => 15062,
            'name' => 'Parceiro Emissário: Jogo da Corte',
            'description' => 'Você pode usar o poder Jogo da Corte.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_emissario_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15063,
            'name' => 'Parceiro Espião: Perícias',
            'description' => 'Você recebe +2 em Furtividade e Investigação.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_espiao_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15064,
            'name' => 'Parceiro Espião: Investigação Rápida',
            'description' => 'Você faz testes de Investigação na metade do tempo e não sofre penalidade em testes de Furtividade por se mover no seu deslocamento normal.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_espiao_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15065,
            'name' => 'Parceiro Espião: Vantagem em Testes',
            'description' => 'Quando faz um teste de Furtividade ou Investigação, você pode gastar 2 PM para rolar dois dados e usar o melhor resultado.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'pm_cost' => 2,
            'icon_file_name' => 'comp_espiao_mestre_01.webp',
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 11],
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 15],
            ],
        ]);

        Power::create([
            'id' => 15066,
            'name' => 'Parceiro Fortão: Dano Corpo a Corpo',
            'description' => 'Uma vez por rodada, você recebe +1d8 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_fortao_iniciante_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 15067,
            'name' => 'Parceiro Fortão: Dano Corpo a Corpo',
            'description' => 'Uma vez por rodada, você recebe +1d12 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_fortao_veterano_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15066],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d12'],
            ],
        ]);

        Power::create([
            'id' => 15068,
            'name' => 'Parceiro Fortão: Dano Corpo a Corpo',
            'description' => 'Uma vez por rodada, você recebe +3d6 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_fortao_mestre_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15067],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '3d6'],
            ],
        ]);

        Power::create([
            'id' => 15069,
            'name' => 'Parceiro Guardião: Defesa',
            'description' => 'Você recebe +2 na Defesa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_guardiao_iniciante_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15070,
            'name' => 'Parceiro Guardião: Defesa',
            'description' => 'Você recebe +3 na Defesa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_guardiao_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15069],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15071,
            'name' => 'Parceiro Guardião: Defesa e Resistência',
            'description' => 'Você recebe +4 na Defesa e +2 em testes de resistência.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_guardiao_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15070],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 4],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 10, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 26, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15072,
            'name' => 'Magivocador: Dado Extra',
            'description' => 'O dano de suas magias aumenta em +1 dado do mesmo tipo.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_magivocador_iniciante_01.webp',
            'effects' => [
                ['tag' => 'mod_spell_dmg', 'op' => 'extra_die', 'value' => 'spell_die'],
            ],
        ]);

        Power::create([
            'id' => 15073,
            'name' => 'Parceiro Magivocador: CD de Magias',
            'description' => 'A CD para resistir a suas magias aumenta em +1.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_magivocador_veterano_01.webp',
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15074,
            'name' => 'Magivocador: Dados Extras',
            'description' => 'O dano de suas magias aumenta em +2 dados do mesmo tipo.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_magivocador_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15072],
                ['tag' => 'mod_spell_dmg', 'op' => 'extra_die', 'value' => 'spell_die'],
                ['tag' => 'mod_spell_dmg', 'op' => 'extra_die', 'value' => 'spell_die'],
            ],
        ]);

        Power::create([
            'id' => 15091,
            'name' => 'Parceiro Magivocador: CD de Magias',
            'description' => 'A CD para resistir a suas magias aumenta em +2.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_magivocador_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15073],
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15075,
            'name' => 'Parceiro Médico: Cura (Iniciante)',
            'description' => 'Uma vez por rodada você pode gastar 1 PM para curar 1d8+1 PV de uma criatura adjacente.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_medico_iniciante_01.webp',
            'effects' => [
                ['tag' => 'grant_spell', 'op' => 'grant', 'spell_id' => 3009],
            ],
        ]);

        Power::create([
            'id' => 15076,
            'name' => 'Parceiro Médico: Cura (Veterano)',
            'description' => 'Você pode gastar 3 PM para curar 3d8+3 PV de uma criatura adjacente ou remover uma condição prejudicial (como abalado ou fatigado). <br><br>No APP, o alvo deve remover manualmente a condição negativa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_medico_veterano_01.webp',
            'effects' => [
                ['tag' => 'grant_spell', 'op' => 'grant', 'spell_id' => 3010],
            ],
        ]);

        Power::create([
            'id' => 15077,
            'name' => 'Parceiro Médico: Cura (Mestre)',
            'description' => 'Você também pode gastar 5 PM para curar 6d8+6 PV de uma criatura adjacente ou remover uma condição prejudicial (como abalado ou fatigado). <br><br>No APP, o alvo deve remover manualmente a condição negativa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_medico_mestre_01.webp',
            'effects' => [
                ['tag' => 'grant_spell', 'op' => 'grant', 'spell_id' => 3011],
            ],
        ]);

        Power::create([
            'id' => 15078,
            'name' => 'Parceiro Menestrel: Fama e Recompensas',
            'description' => '+1 em ajustes de recompensa e 1 ponto de fama bônus sempre que ganha fama.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_menestrel_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15079,
            'name' => 'Parceiro Menestrel: Fama e Recompensas',
            'description' => '+1 em ajustes de recompensa e 2 pontos de fama bônus sempre que ganha fama.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_menestrel_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15078],
            ],
        ]);

        Power::create([
            'id' => 15080,
            'name' => 'Parceiro Menestrel: Fama e Recompensas',
            'description' => '+2 em ajustes de recompensa e 3 pontos de fama bônus sempre que ganha fama.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_menestrel_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15079],
            ],
        ]);

        Power::create([
            'id' => 15081,
            'name' => 'Parceiro Perseguidor: Perícias',
            'description' => 'Você recebe +2 em Percepção e Sobrevivência.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_perseguidor_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15082,
            'name' => 'Parceiro Perseguidor: Sentidos Aguçados',
            'description' => 'Você pode usar Sentidos Aguçados.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_perseguidor_veterano_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11036],
            ],
        ]);

        Power::create([
            'id' => 15083,
            'name' => 'Parceiro Perseguidor: Percepção as Cegas',
            'description' => 'Você pode usar Percepção às Cegas.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_perseguidor_mestre_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11035],
            ],
        ]);

        Power::create([
            'id' => 15084,
            'name' => 'Parceiro Sábio: Perícias',
            'description' => 'Você recebe +2 em Conhecimento, Misticismo e Nobreza e pode fazer testes destas perícias mesmo sem ser treinado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_sabio_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 6, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15085,
            'name' => 'Parceiro Sábio: Perícia Adicional',
            'description' => 'O bônus se aplica a uma perícia adicional, a sua escolha (exceto Luta ou Pontaria).<br><br>No APP, ative o poder do companheiro quando for rolar a perícia escolhida.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_sabio_veterano_01.webp',
            'effects' => [
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15086,
            'name' => 'Parceiro Sábio: Perícias',
            'description' => 'Você recebe +3 em Conhecimento, Misticismo e Nobreza e pode fazer testes destas perícias mesmo sem ser treinado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_sabio_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15084],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 6, 'value' => 3],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 3],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15087,
            'name' => 'Parceiro Sábio: Perícia Adicional',
            'description' => 'O bônus na perícia adicional, a sua escolha (exceto Luta ou Pontaria), muda para +3.<br><br>No APP, ative o poder do companheiro quando for rolar a perícia escolhida.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_sabio_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15085],
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15088,
            'name' => 'Parceiro Vigilante: Perícias',
            'description' => 'Você recebe +2 em Percepção e Iniciativa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_vigilante_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 2],
            ],
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15089,
            'name' => 'Parceiro Vigilante: Esquiva Sobrenatural',
            'description' => 'Você pode usar Esquiva Sobrenatural.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_vigilante_veterano_01.webp',
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15090,
            'name' => 'Parceiro Vigilante: Olhos nas Costas',
            'description' => 'Você pode usar Olhos nas Costas.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_vigilante_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15092,
            'name' => 'Asa-Assassina: Sangramento',
            'description' => 'Uma vez por rodada, quando causa dano com um ataque, você pode deixar a vítima sangrando.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_asa_assassina_iniciante_01.webp',
            'effects' => [
                ['trigger' => 'on_hit_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 1],
            ],
        ]);

        Power::create([
            'id' => 15093,
            'name' => 'Asa-Assassina: Sangramento (1d8)',
            'description' => 'Uma vez por rodada, quando causa dano com um ataque, você pode deixar a vítima sangrando. A perda de PV pelo sangramento aumenta para 1d8.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_asa_assassina_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15092],
                ['trigger' => 'on_hit_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 1],
            ],
        ]);

        Power::create([
            'id' => 15094,
            'name' => 'Asa-Assassina: Sangramento (2d8)',
            'description' => 'Uma vez por rodada, quando causa dano com um ataque, você pode deixar a vítima sangrando. A perda de PV pelo sangramento aumenta para 2d8.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_asa_assassina_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15093],
                ['trigger' => 'on_hit_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 1],
            ],
        ]);

        Power::create([
            'id' => 15095,
            'name' => 'Bogum: Elo Mental',
            'description' => 'Você forma um elo mental com o bogum (semelhante ao de um arcanista com seu familiar) e recebe +2 em Percepção e Sobrevivência.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_bogum_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15096,
            'name' => 'Bogum: Dano de Ácido',
            'description' => 'Uma vez por rodada, você recebe +1d6 de ácido em uma rolagem de dano.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_bogum_veterano_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'acid'],
            ],
        ]);

        Power::create([
            'id' => 15097,
            'name' => 'Bogum: Dedo de Ente',
            'description' => 'O bogum fornece o benefício de um dedo de ente, cumulativo com outros itens esotéricos. Sempre que você gasta pelo menos 1 PM para lançar uma magia, você rola 1d4. Se tirar um resultado 4, você recupera 1 PM. <br><br>No APP, role o 1d4 manualmente e caso tire 4 na rolagem, adicione o PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_bogum_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15098,
            'name' => 'Cão de Kally: Dano de Fogo',
            'description' => 'Uma vez por rodada, uma de suas rolagens de dano corpo a corpo causa +1d6 pontos de dano de fogo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_cao_de_kally_iniciante_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'fire'],
            ],
        ]);

        Power::create([
            'id' => 15099,
            'name' => 'Cão de Kally: Dano de Fogo (2d6)',
            'description' => 'Uma vez por rodada, uma de suas rolagens de dano corpo a corpo causa +2d6 pontos de dano de fogo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_cao_de_kally_veterano_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15098],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d6', 'damage_type' => 'fire'],
            ],
        ]);

        Power::create([
            'id' => 15100,
            'name' => 'Cão de Kally: Cone de Fogo',
            'description' => 'Uma vez por rodada, você pode gastar 3 PM para causar 4d6 pontos de dano de fogo em um cone de 6m.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 3,
            'icon_file_name' => 'comp_cao_de_kally_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15101,
            'name' => 'Carcaju: Dano Corpo a Corpo',
            'description' => 'Uma vez por rodada você recebe +1d6 em uma rolagem de dano corpo a corpo. Se rolar um 6 nesse dado extra de dano, você pode rolar +1d6 e somar ao resultado uma vez. <br><br>No APP, caso tire 6 na rolagem, role manualmente mais 1d6 e adicione ao dano.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_carcaju_iniciante_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6'],
            ],
        ]);

        Power::create([
            'id' => 15102,
            'name' => 'Carcaju: Margem de Ameaça',
            'description' => 'Sua margem de ameaça com armas corpo a corpo aumenta em +1.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_carcaju_veterano_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15103,
            'name' => 'Carcaju: Fúria Defensiva',
            'description' => 'Quando sofre dano, você recebe +2 em testes de ataque e rolagens de dano até o fim de seu próximo turno.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_carcaju_mestre_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        //TODO fix the spell_ids once all spells that cause movement conditions are seeded
        Power::create([
            'id' => 15104,
            'name' => 'Cocatriz: Redução de Custo',
            'description' => 'O custo de suas habilidades mágicas que causam condições de movimento é reduzido em –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_cocatriz_iniciante_01.webp',
            'applies_when' => ['spell_ids' => [4, 11, 25, 1000]],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        //TODO fix the spell_ids once all spells that cause movement conditions are seeded
        Power::create([
            'id' => 15105,
            'name' => 'Cocatriz: CD',
            'description' => 'A CD para resistir às suas habilidades mágicas que causam condições de movimento aumenta em +2.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_cocatriz_veterano_01.webp',
            'applies_when' => ['spell_ids' => [4, 11, 25, 1000]],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        //TODO fix the spell_ids once all spells that cause movement conditions are seeded
        Power::create([
            'id' => 15106,
            'name' => 'Cocatriz: Redução de Custo Cumulativa',
            'description' => 'A redução de custo de suas habilidades mágicas que causam condições de movimento se torna cumulativa com outras reduções.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_cocatriz_mestre_01.webp',
            'applies_when' => ['spell_ids' => [4, 11, 25, 1000]],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15104],
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1, 'cumulative_with_other_pm_cost_reductions' => true],
            ],
        ]);

        Power::create([
            'id' => 15107,
            'name' => 'Dragonete: Redução de Custo',
            'description' => 'O custo de suas magias de encantamento e ilusão diminui em –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_dragonete_iniciante_01.webp',
            'applies_when' => ['spell_schools' => ['encantamento', 'ilusao']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15108,
            'name' => 'Dragonete: Alcance',
            'description' => 'O alcance de suas magias de encantamento e ilusão aumenta em um passo (de curto para médio e de médio para longo).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_dragonete_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15109,
            'name' => 'Dragonete: Redução de Custo Cumulativa',
            'description' => 'A redução de custo de suas magias de encantamento e ilusão se torna cumulativa com outras reduções.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_dragonete_mestre_01.webp',
            'applies_when' => ['spell_schools' => ['encantamento', 'ilusao']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15107],
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1, 'cumulative_with_other_pm_cost_reductions' => true],
            ],
        ]);

        //TODO fix this when we add Visão Mística
        Power::create([
            'id' => 15110,
            'name' => 'Dragonete: Visão Mística',
            'description' => 'Você fica sempre sob o efeito básico da magia Visão Mística.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_dragonete_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15111,
            'name' => 'Elemental de Veneno: CD do Veneno',
            'description' => 'A CD para resistir aos seus efeitos de veneno aumenta em +2.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_elemental_veneno_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15112,
            'name' => 'Elemental de Veneno (Iniciante)',
            'description' => 'Uma vez por rodada você pode gastar 1 PM para envenenar uma arma que esteja usando. A arma causa perda de 1d12 pontos de vida e o veneno dura até você acertar um ataque ou até o fim da cena.',
            'source' => 'companion_granted',
            'usability' => 'item_enhancer',
            'pm_cost' => 1,
            'icon_file_name' => 'comp_elemental_veneno_iniciante_01.webp',
            'applies_when' => ['categories' => ['weapon']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d12', 'damage_type' => 'poison'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15113,
            'name' => 'Elemental de Veneno (Veterano)',
            'description' => 'Você também pode gastar 3 PM para aplicar um veneno que causa perda de 2d12 pontos de vida.',
            'source' => 'companion_granted',
            'usability' => 'item_enhancer',
            'pm_cost' => 3,
            'icon_file_name' => 'comp_elemental_veneno_veterano_01.webp',
            'applies_when' => ['categories' => ['weapon']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d12', 'damage_type' => 'poison'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15114,
            'name' => 'Elemental de Veneno (Mestre)',
            'description' => 'Você também pode gastar 5 PM para aplicar um veneno que causa perda de 3d12 pontos de vida.',
            'source' => 'companion_granted',
            'usability' => 'item_enhancer',
            'pm_cost' => 5,
            'icon_file_name' => 'comp_elemental_veneno_mestre_01.webp',
            'applies_when' => ['categories' => ['weapon']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '3d12', 'damage_type' => 'poison'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15115,
            'name' => 'Elemental de Veneno: Ignora Imunidade',
            'description' => 'Seus efeitos de veneno ignoram imunidade a veneno.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_elemental_veneno_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15116,
            'name' => 'Fadinha de Prata: Bônus',
            'description' => 'Você recebe um bônus de +2 em testes de perícia (inclusive luta e pontaria) e dano.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_fadinha_de_prata_iniciante_01.webp',
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15117,
            'name' => 'Fadinha de Prata: Bônus (+4)',
            'description' => 'Você recebe um bônus de +4 em testes de perícia (inclusive luta e pontaria) e dano.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_fadinha_de_prata_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15116],
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 4],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 15118,
            'name' => 'Fadinha de Prata: Bônus (+6)',
            'description' => 'Você recebe um bônus de +6 em testes de perícia (inclusive luta e pontaria) e dano.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_fadinha_de_prata_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15117],
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 6],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 6],
            ],
        ]);

        Power::create([
            'id' => 15119,
            'name' => 'Fadinha de Prata: Ação de Movimento Extra',
            'description' => 'Você recebe uma ação de movimento extra por turno.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_fadinha_de_prata_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15120,
            'name' => 'Falcão: Bônus de Adestramento',
            'description' => 'Uma vez por cena, você pode gastar uma ação de movimento e fazer um teste de Adestramento (CD 10). Se passar, o falcão concede a você +1 em testes de ataque, Percepção e Sobrevivência até o fim da cena. Para cada 10 pontos pelos quais o resultado de seu teste passar a CD, esse bônus aumenta em +1. <br><br>No APP, faça a rolagem de adestramento e adicione o bônus aos testes relacionados manualmente.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_falcao_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15121,
            'name' => 'Falcão: Sentidos Aguçados',
            'description' => 'Você pode usar Sentidos Aguçados.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_falcao_veterano_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11036],
            ],
        ]);

        Power::create([
            'id' => 15122,
            'name' => 'Falcão: Cegar',
            'description' => 'Uma vez por rodada, você pode gastar 1 PM para fazer uma criatura em alcance médio ficar cega por 1d4 rodadas (Ref CD Car evita).',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'comp_falcao_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15123,
            'name' => 'Fofo: Redução de Dano',
            'description' => 'Você recebe redução de dano 1.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_fofo_iniciante_01.webp',
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15124,
            'name' => 'Fofo: Redução de Dano (2)',
            'description' => 'Você recebe redução de dano 2.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_fofo_veterano_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15123],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15125,
            'name' => 'Fofo: Redução de Dano (3)',
            'description' => 'Você recebe redução de dano 3.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_fofo_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15124],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15126,
            'name' => 'Fofo: Item Vestido',
            'description' => 'Você pode vestir um item que ocupe 1 espaço ou menos sem contar em seu limite de itens vestidos.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_fofo_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15127,
            'name' => 'Gambá: Perícias',
            'description' => 'Você recebe +2 em Iniciativa e Percepção.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_gamba_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15128,
            'name' => 'Gambá: Enjoar',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para deixar uma criatura em alcance curto enjoada por 1d4 rodadas (Fort CD Sab evita).',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => 'comp_gamba_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15129,
            'name' => 'Gambá: Perícias (+4)',
            'description' => 'Você recebe +4 em Iniciativa e Percepção.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_gamba_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15127],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 4],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 15130,
            'name' => 'Gênio do Fogo: Chamas Mágicas',
            'description' => 'Seus ataques corpo a corpo deixam o alvo em chamas mágicas.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_genio_de_fogo_iniciante_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['trigger' => 'on_hit_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 22],
            ],
        ]);

        Power::create([
            'id' => 15131,
            'name' => 'Gênio do Fogo: Chamas Mágicas (1d8)',
            'description' => 'Seus ataques corpo a corpo deixam o alvo em chamas mágicas. Quando você deixa uma criatura em chamas, o dano dessa condição aumenta para 1d8.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_genio_de_fogo_veterano_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15130],
                ['trigger' => 'on_hit_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 22],
            ],
        ]);

        Power::create([
            'id' => 15132,
            'name' => 'Gênio do Fogo: Chamas Mágicas (Ação Completa)',
            'description' => 'Seus ataques corpo a corpo deixam o alvo em chamas mágicas. Quando você deixa uma criatura em chamas, o dano dessa condição aumenta para 1d8 e ela precisa gastar uma ação completa (em vez de uma ação padrão) para tentar apagar o fogo.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_genio_de_fogo_mestre_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15131],
                ['trigger' => 'on_hit_success', 'tag' => 'condition', 'op' => 'inflict', 'condition_id' => 22],
            ],
        ]);

        Power::create([
            'id' => 15133,
            'name' => 'Hiena: Perícias',
            'description' => 'Você recebe +2 em Furtividade e Sobrevivência.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_hiena_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15134,
            'name' => 'Hiena: Oportunismo',
            'description' => 'Você pode usar Oportunismo. Se possui esse poder, em vez disso o custo para usá-lo diminui em –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_hiena_veterano_01.webp',
        ]);

        //TODO fix this when we add montarias
        Power::create([
            'id' => 15135,
            'name' => 'Hiena: Sentidos Aguçados',
            'description' => 'Você pode usar Sentidos Aguçados. Alternativamente, uma hiena pode ser treinada como uma montaria Média com as mesmas estatísticas de um hienodonte.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_hiena_mestre_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11036],
            ],
        ]);

        Power::create([
            'id' => 15136,
            'name' => 'Homúnculo: Dano de Veneno',
            'description' => 'Seus venenos causam a perda de +1 PV por dado. <br><br>No APP, adicione manualmente +1 Dano/Dado de veneno.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_homunculo_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15137,
            'name' => 'Homúnculo: Veneno (Veterano)',
            'description' => 'Uma vez por rodada, quando faz um ataque, você pode gastar 1 PM. Se acertar o ataque, além do dano você causa a perda de 1d12 PV por veneno.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => 'comp_homunculo_veterano_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d12', 'damage_type' => 'poison'],
            ],
        ]);

        Power::create([
            'id' => 15138,
            'name' => 'Homúnculo: Veneno (Mestre)',
            'description' => 'Você também pode gastar 2 PM para causar a perda de 3d6 PV.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'pm_cost' => 2,
            'icon_file_name' => 'comp_homunculo_mestre_01.webp',
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '3d6', 'damage_type' => 'poison'],
            ],
        ]);

        Power::create([
            'id' => 15139,
            'name' => "Kill'Bone: Faro",
            'description' => 'Você recebe faro.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_killbone_iniciante_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16012],
            ],
        ]);

        Power::create([
            'id' => 15140,
            'name' => "Kill'Bone: Dano Corpo a Corpo",
            'description' => 'Uma vez por rodada, você recebe +1d6 em uma rolagem de dano corpo a corpo (o dano extra é dobrado contra trolls).',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_killbone_iniciante_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6'],
            ],
        ]);

        Power::create([
            'id' => 15141,
            'name' => "Kill'Bone: Dano Corpo a Corpo (1d8)",
            'description' => 'Uma vez por rodada, você recebe +1d8 em uma rolagem de dano corpo a corpo (o dano extra é dobrado contra trolls).',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_killbone_veterano_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15140],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 15142,
            'name' => "Kill'Bone: Dano Corpo a Corpo (1d10)",
            'description' => 'Uma vez por rodada, você recebe +1d10 em uma rolagem de dano corpo a corpo (o dano extra é dobrado contra trolls).',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_killbone_mestre_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15141],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d10'],
            ],
        ]);

        Power::create([
            'id' => 15143,
            'name' => "Kill'Bone: Contra Trolls",
            'description' => 'Você recebe +2 em testes de perícia contra trolls.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_killbone_veterano_01.webp',
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15144,
            'name' => "Kill'Bone: Sem Flanqueio",
            'description' => 'Você não pode ser flanqueado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_killbone_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15145,
            'name' => 'Gênio da Terra: Cubo de Terra',
            'description' => 'Uma vez por rodada, você pode criar um cubo de terra de 1,5m de lado em um espaço desocupado a até 9m. O cubo tem RD 5 e 30 PV e dura até o fim da cena ou até você acumular 4 cubos.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'icon_file_name' => 'comp_genio_terra_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15146,
            'name' => 'Gênio da Terra: Defesa',
            'description' => 'Você recebe +2 na Defesa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_genio_terra_veterano_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15147,
            'name' => 'Gênio da Terra: Controlar Terra',
            'description' => 'Você pode lançar Controlar Terra (atributo-chave Carisma).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_genio_terra_mestre_01.webp',
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 1007],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'attribute_car'],
            ],
        ]);

        Power::create([
            'id' => 15148,
            'name' => 'Kobold: Flanquear',
            'description' => 'Você recebe +1 em testes de ataque e rolagens de dano contra inimigos que esteja flanqueando, cumulativo com outros bônus por flanquear.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_kobold_iniciante_01.webp',
            'applies_when' => ['flanking' => true],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15149,
            'name' => 'Kobold: Flanquear (+3)',
            'description' => 'Você recebe +3 em testes de ataque e rolagens de dano contra inimigos que esteja flanqueando, cumulativo com outros bônus por flanquear.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_kobold_veterano_01.webp',
            'applies_when' => ['flanking' => true],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15148],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 3],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15150,
            'name' => 'Kobold: Flanquear Qualquer Criatura',
            'description' => 'Você pode flanquear mesmo criaturas que não podem ser flanqueadas.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_kobold_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15151,
            'name' => 'Ko-Kabuto: Visão na Penumbra',
            'description' => 'Você recebe visão na penumbra.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_kokabuto_iniciante_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16011],
            ],
        ]);

        Power::create([
            'id' => 15152,
            'name' => 'Ko-Kabuto: Defesa',
            'description' => 'Você recebe +1 em Defesa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_kokabuto_iniciante_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15153,
            'name' => 'Ko-Kabuto: Dano Corpo a Corpo',
            'description' => 'Uma vez por rodada, você recebe +1d8 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_kokabuto_veterano_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 15154,
            'name' => 'Ko-Kabuto: Defesa (+2)',
            'description' => 'Você recebe +2 em Defesa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_kokabuto_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15152],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15155,
            'name' => 'Ko-Kabuto: Dano Corpo a Corpo (1d10)',
            'description' => 'Uma vez por rodada, você recebe +1d10 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_kokabuto_mestre_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15153],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d10'],
            ],
        ]);

        //TODO fix this when we add montarias
        Power::create([
            'id' => 15156,
            'name' => 'Ko-Kabuto: Montaria',
            'description' => 'Um ko-kabuto pode ser treinado como uma montaria Média (adequada a criaturas Pequenas) com as mesmas estatísticas de um dai-kabuto.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_kokabuto_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15157,
            'name' => 'Luminar: Dano de Luz (Iniciante)',
            'description' => 'Você pode gastar 1 PM para causar 2d4 pontos de dano não letal de luz em uma criatura em alcance curto.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'comp_luminar_iniciante_01.webp',
        ]);

        Power::create([
            'id' => 15158,
            'name' => 'Luminar: Cura (2d4)',
            'description' => 'Uma vez por rodada, você pode gastar 1 PM para curar 2d4 PV por luz.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_luminar_iniciante_01.webp',
            'effects' => [
                ['tag' => 'grant_spell', 'op' => 'grant', 'spell_id' => 3006],
            ],
        ]);

        Power::create([
            'id' => 15159,
            'name' => 'Luminar: Bônus de Luz',
            'description' => 'Você também pode gastar 2 PM para conceder um bônus de +2 em seus testes de ataque e de resistência, e nos de seus aliados em alcance curto, por 1 rodada.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_luminar_veterano_01.webp',
            'effects' => [
                ['tag' => 'grant_spell', 'op' => 'grant', 'spell_id' => 3007],
            ],
        ]);

        Power::create([
            'id' => 15160,
            'name' => 'Luminar: Dano de Luz (Mestre)',
            'description' => 'Você pode gastar 3 PM para causar 6d4 pontos de dano não letal de luz em uma criatura em alcance curto.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 3,
            'icon_file_name' => 'comp_luminar_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15161,
            'name' => 'Luminar: Cura (6d4)',
            'description' => 'Você também pode gastar 3 PM para curar 6d4 PV.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_luminar_mestre_01.webp',
            'effects' => [
                ['tag' => 'grant_spell', 'op' => 'grant', 'spell_id' => 3008],
            ],
        ]);

        Power::create([
            'id' => 15162,
            'name' => 'Molosso: Defesa e Perícias',
            'description' => 'Você recebe +1 na Defesa e +2 em testes de Intuição e Percepção (contra seres ofídicos e sszzaazitas, o bônus nos testes de perícia muda para +5). <br><br>No APP, caso o oponente seja um ser ofídico ou Sszzaazita, adicione manualmente os bônus aos resultados.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_molosso_iniciante_01.webp',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15163,
            'name' => 'Molosso: Dano Corpo a Corpo',
            'description' => 'Uma vez por rodada, você recebe +2d6 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_molosso_veterano_01.webp',
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d6'],
            ],
        ]);

        Power::create([
            'id' => 15164,
            'name' => 'Molosso: Defesa e Perícias (+2)',
            'description' => 'Você recebe +2 na Defesa e +2 em testes de Intuição e Percepção (contra seres ofídicos e sszzaazitas, o bônus nos testes de perícia muda para +5). <br><br>No APP, caso o oponente seja um ser ofídico ou Sszzaazita, adicione manualmente os bônus aos resultados.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_molosso_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15162],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15165,
            'name' => 'Molosso: Derrubar',
            'description' => 'Uma vez por rodada, quando faz uma investida, você pode fazer a manobra derrubar como uma ação livre.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_molosso_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15166,
            'name' => 'Pássaro de Fogo: Redução de Dano',
            'description' => 'Você recebe redução de dano 5/trevas.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_passaro_fogo_iniciante_01.webp',
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'darkness'],
            ],
        ]);

        Power::create([
            'id' => 15167,
            'name' => 'Pássaro de Fogo: Sortudo',
            'description' => 'Você pode usar Sortudo. Se já possui este poder, em vez disso seu custo é reduzido em –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_passaro_fogo_iniciante_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 324],
            ],
        ]);

        //TODO fix this when we add NIMB
        Power::create([
            'id' => 15168,
            'name' => 'Pássaro do Caos: Sorte dos Loucos',
            'description' => 'Você pode usar Sorte dos Loucos. Se já tiver esse poder, a perda de PM é reduzida para 1d4.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_passaro_caos_iniciante_01.webp',
        ]);

        //TODO fix this when we add NIMB
        Power::create([
            'id' => 15169,
            'name' => 'Pássaro do Caos: Sorte dos Loucos em Aliados',
            'description' => 'Você pode usar Sorte dos Loucos em aliados em alcance curto (o aliado perde os PM em caso de falha).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_passaro_caos_veterano_01.webp',
        ]);

        //TODO fix this when we add NIMB
        Power::create([
            'id' => 15170,
            'name' => 'Pássaro do Caos: Rolar Novamente',
            'description' => 'Quando um inimigo em alcance curto faz um teste, você pode gastar 1 PM para forçá-lo a rolar novamente o dado. Se ainda assim ele passar, você perde 1d6 PM (ou 1d4 se tiver Sorte dos Loucos).',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'comp_passaro_caos_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15171,
            'name' => 'Perdigueiro Troll: Perícias',
            'description' => 'Você recebe +2 em Percepção e Sobrevivência.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_perdigueiro_troll_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15172,
            'name' => 'Perdigueiro Troll: Agarrar e Derrubar',
            'description' => 'Você recebe +2 em testes para agarrar e derrubar.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_perdigueiro_troll_veterano_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15173,
            'name' => 'Perdigueiro Troll: Agarrar com Mão Livre',
            'description' => 'Você pode manter uma criatura Grande ou menor agarrada sem ocupar uma mão.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_perdigueiro_troll_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15174,
            'name' => 'Perdigueiro Troll: Perícias (+5)',
            'description' => 'Você recebe +5 em Percepção e Sobrevivência.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_perdigueiro_troll_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15171],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 5],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 15175,
            'name' => 'Perdigueiro Troll: Agarrar e Derrubar (+5)',
            'description' => 'Você recebe +5 em testes para agarrar e derrubar.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_perdigueiro_troll_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15172],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 15176,
            'name' => 'Perdigueiro Troll: Agarrar como Reação',
            'description' => 'Uma vez por rodada, quando acerta um ataque corpo a corpo, você pode usar a manobra agarrar como uma reação.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_perdigueiro_troll_mestre_01.webp',
        ]);

        Power::create([
            'id' => 15177,
            'name' => 'Pilly: Redução de Custo',
            'description' => 'O custo de suas magias divinas de encantamento diminui em –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_pilly_iniciante_01.webp',
            'applies_when' => ['spell_types' => ['divina'], 'spell_schools' => ['encantamento']],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15178,
            'name' => 'Pilly: Voo',
            'description' => 'Você ganha deslocamento de voo 9m.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_pilly_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15179,
            'name' => 'Pilly: Redução de Custo (–2)',
            'description' => 'O custo de suas magias divinas de encantamento diminui em –2 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_pilly_mestre_01.webp',
            'applies_when' => ['spell_types' => ['divina'], 'spell_schools' => ['encantamento']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15177],
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 15180,
            'name' => 'Protetor da Ordem: Círculo da Justiça',
            'description' => 'Você pode lançar a magia Círculo da Justiça (atributo-chave Sabedoria); se aprender essa magia, seu custo diminui em –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_protetor_ordem_iniciante_01.webp',
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 1008],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'attribute_knw'],
            ],
        ]);

        Power::create([
            'id' => 15181,
            'name' => 'Protetor da Ordem: Rolar Novamente',
            'description' => 'Quando falha em um teste de resistência contra uma magia, você pode gastar 3 PM para rolar novamente esse teste (apenas uma vez por teste).',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 3,
            'icon_file_name' => 'comp_protetor_ordem_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15182,
            'name' => 'Protetor da Ordem: CD',
            'description' => 'A CD para resistir a sua magia Círculo da Justiça aumenta em +5.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_protetor_ordem_mestre_01.webp',
            'applies_when' => ['spell_ids' => [1008]],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 15183,
            'name' => 'Tentacute: Perícias',
            'description' => 'Você recebe +2 em Percepção.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_tentacute_iniciante_01.webp',
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15184,
            'name' => 'Tentacute: Punga',
            'description' => 'Quando faz um teste de Ladinagem para punga, você pode rolar dois dados e usar o melhor resultado.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => 'comp_tentacute_iniciante_01.webp',
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 18],
            ],
        ]);

        Power::create([
            'id' => 15185,
            'name' => 'Tentacute: Punga a Distância',
            'description' => 'Você pode gastar 1 PM para fazer um teste de Ladinagem para punga contra um alvo em alcance curto que possa ser alcançado pelo tentacute.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'comp_tentacute_veterano_01.webp',
        ]);

        Power::create([
            'id' => 15186,
            'name' => 'Tentacute: Perícias (+4)',
            'description' => 'Você recebe +4 em Percepção.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'comp_tentacute_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15183],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 15187,
            'name' => 'Tentacute: Punga a Distância (Médio)',
            'description' => 'Você pode gastar 1 PM para fazer um teste de Ladinagem para punga contra um alvo em alcance médio que possa ser alcançado pelo tentacute.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'comp_tentacute_mestre_01.webp',
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15185],
            ],
        ]);
    }
}
