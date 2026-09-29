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
            'name' => "Familiar (Aquin'ne)",
            'description' => "Um aquin'ne familiar concede deslocamento de natação 9m e permite lançar magias e respirar debaixo d'água.",
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_aquinne_01.webp',
        ]);

        Power::create([
            'id' => 15001,
            'name' => 'Familiar (Asa-Assassina)',
            'description' => 'Permite que você gaste 1 PM quando causa dano de corte ou perfuração a uma criatura para deixá-la sangrando. <br><br>No APP, use o poder (gasta seu PM) e informe o mestre da condição causada.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => 'familiar_asa_de_aco_01.webp',
        ]);

        Power::create([
            'id' => 15002,
            'name' => 'Familiar (Borboleta)',
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
            'name' => 'Familiar (Chibi-Kabuto)',
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
            'name' => 'Familiar (Cobra)',
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
            'name' => 'Familiar (Coruja)',
            'description' => 'Quando lança uma magia com alcance de toque, você pode pagar 1 PM para aumentar seu alcance para curto.',
            'source' => 'companion_granted',
            'usability' => 'spell_enhancement',
            'pm_cost' => 1,
            'icon_file_name' => 'familiar_coruja_01.webp',
            'applies_when' => ['spell_ranges' => ['toque']],
        ]);

        Power::create([
            'id' => 15006,
            'name' => 'Familiar (Diabrete) (Ilusão)',
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
            'name' => 'Familiar (Diabrete) (Veneno)',
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
            'name' => 'Familiar (Dragão)',
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
            'name' => 'Familiar (Falcão)',
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
            'name' => 'Familiar (Fuinha)',
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
            'name' => 'Familiar (Gato)',
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
            'name' => 'Familiar (Homúnculo) (Transmutação)',
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
            'name' => 'Familiar (Homúnculo) (Veneno)',
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
            'name' => 'Familiar (Lagarto)',
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
            'name' => 'Familiar (Macaco)',
            'description' => 'Uma vez por rodada, você pode usar seu familiar para sacar ou guardar um item, ou para pegar um item solto Pequeno ou menor (1 espaço ou menos) em alcance curto e que ele consiga alcançar.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_macaco_01.webp',
        ]);

        Power::create([
            'id' => 15016,
            'name' => 'Familiar (Morcego)',
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
            'name' => 'Familiar (Pakk)',
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
            'name' => 'Familiar (Papagaio Zumbi)',
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
            'name' => 'Familiar (Rato)',
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
            'name' => 'Familiar (Sapo)',
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
            'name' => 'Familiar (Stahg)',
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
            'name' => 'Familiar (Tartaruga)',
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
            'name' => 'Familiar (Tentacule)',
            'description' => 'Pode ser usado, uma vez por rodada, para sacar ou guardar um item, ou para pegar um item solto Pequeno ou menor (1 espaço ou menos) em alcance curto e que ele consiga alcançar.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => 'familiar_tentacule_01.webp',
        ]);

        Power::create([
            'id' => 15024,
            'name' => 'Familiar (Terrier)',
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
            'name' => "Familiar (T'peel)",
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
            'name' => 'Companheiro Aberrante Iniciante',
            'description' => 'Uma vez por rodada, você pode gastar 1 PM para disparar um pulso mental contra uma criatura em alcance curto; ela sofre 2d6 pontos de dano psíquico ou perde 1d4 PM, a sua escolha.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15027,
            'name' => 'Companheiro Aberrante Veterano',
            'description' => 'Você pode gastar 2 PM para causar 4d6 pontos de dano ou fazer a criatura perder 2d4 PM.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 2,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15028,
            'name' => 'Companheiro Aberrante Mestre',
            'description' => 'Você pode gastar 4 PM para causar 6d6 pontos de dano ou fazer a criatura perder 3d4 PM.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 4,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15029,
            'name' => 'Companheiro Adepto Iniciante',
            'description' => 'O custo para lançar suas magias de 1º círculo diminui –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_circles' => [1]],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15030,
            'name' => 'Companheiro Adepto Veterano',
            'description' => 'O custo para lançar suas magias de 2º círculo diminui –1 PM.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_circles' => [2]],
            'effects' => [
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 15031,
            'name' => 'Companheiro Adepto Mestre',
            'description' => 'A redução no custo de suas magias de 1º e 2º círculo se torna cumulativa com outras reduções.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_circles' => [1, 2]],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15029],
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15030],
                ['tag' => 'mod_spell_pm_cost', 'op' => 'add', 'value' => -1, 'cumulative_with_other_pm_cost_reductions' => true],
            ],
        ]);

        Power::create([
            'id' => 15032,
            'name' => 'Companheiro Ajudante Iniciante',
            'description' => 'Você recebe +2 em duas perícias, definidas pelo parceiro. Um ajudante não pode fornecer bônus em Luta ou Pontaria.<br><br>No APP, marque o poder do parceiro quando for rolar as perícias escolhidas.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15033,
            'name' => 'Companheiro Ajudante Veterano',
            'description' => 'Você recebe +2 em três perícias, definidas pelo parceiro. Um ajudante não pode fornecer bônus em Luta ou Pontaria.<br><br>No APP, marque o poder do parceiro quando for rolar as perícias escolhidas.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15032],
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15034,
            'name' => 'Companheiro Ajudante Mestre',
            'description' => 'Você recebe +4 em três perícias, definidas pelo parceiro. Um ajudante não pode fornecer bônus em Luta ou Pontaria.<br><br>No APP, marque o poder do parceiro quando for rolar as perícias escolhidas.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15033],
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 15035,
            'name' => 'Companheiro Arauto Iniciante',
            'description' => 'Você recebe +2 em Diplomacia, Intuição e Nobreza e pode fazer testes destas perícias mesmo sem ser treinado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 2],
            ],
        ]);

        //TODO fix this when we add nobre
        Power::create([
            'id' => 15036,
            'name' => 'Companheiro Arauto Veterano',
            'description' => 'Você recebe também Jogo da Corte.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15037,
            'name' => 'Companheiro Arauto Mestre',
            'description' => 'No início de cada cena, você pode gastar 2 PM para conceder +2 em testes de perícias baseadas em Carisma para você e seus aliados até o final da cena. <br><br>No APP, seus aliados devem adicionar manualmente o bônus aos seus testes. Use o poder para gastar os PMs.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 2,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15038,
            'name' => 'Companheiro Artesão Iniciante',
            'description' => 'Você recebe +2 em um Ofício (definido pelo aliado) e pode fazer testes desta perícia mesmo sem ser treinado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 22, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15039,
            'name' => 'Companheiro Artesão Veterano',
            'description' => 'Como acima, mas para dois tipos de Ofício.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15040,
            'name' => 'Companheiro Artesão Mestre',
            'description' => 'Como acima, mas você fabrica itens em uma categoria de tempo menor (mínimo de 1 hora, não cumulativo com outros efeitos que reduzam o tempo de fabricação).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15041,
            'name' => 'Companheiro Assassino Iniciante',
            'description' => 'Você pode usar a habilidade Ataque Furtivo +1d6. Se já possui a habilidade, o bônus é cumulativo.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15042,
            'name' => 'Companheiro Assassino Veterano',
            'description' => 'Além do Ataque Furtivo, fornece bônus por flanquear contra um inimigo por rodada.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15043,
            'name' => 'Companheiro Assassino Mestre',
            'description' => 'O dano do Ataque Furtivo muda para +2d6.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15044,
            'name' => 'Companheiro Atirador Iniciante',
            'description' => 'Uma vez por rodada, você recebe +1d6 em uma rolagem de dano à distância.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6'],
            ],
        ]);

        Power::create([
            'id' => 15045,
            'name' => 'Companheiro Atirador Veterano',
            'description' => 'Uma vez por rodada, você recebe +1d10 em uma rolagem de dano à distância.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15044],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d10'],
            ],
        ]);

        Power::create([
            'id' => 15046,
            'name' => 'Companheiro Atirador Mestre',
            'description' => 'Uma vez por rodada, você recebe +2d8 em uma rolagem de dano à distância.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['fired', 'thrown']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15045],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d8'],
            ],
        ]);

        Power::create([
            'id' => 15047,
            'name' => 'Companheiro Besta de Carga Iniciante',
            'description' => 'Pode carregar 10 espaços de itens.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 15048,
            'name' => 'Companheiro Besta de Carga Veterano',
            'description' => 'Pode carregar 15 espaços de itens.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15047],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 15],
            ],
        ]);

        Power::create([
            'id' => 15049,
            'name' => 'Companheiro Besta de Carga Mestre',
            'description' => 'Pode carregar 20 espaços de itens.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15048],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 20],
            ],
        ]);

        Power::create([
            'id' => 15050,
            'name' => 'Companheiro Carregador Iniciante',
            'description' => 'Pode carregar 2 espaços e usar qualquer item que esteja carregando e não exija um teste (como empunhar uma tocha ou aplicar um bálsamo restaurador).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15051,
            'name' => 'Companheiro Carregador Veterano',
            'description' => 'Pode carregar 5 espaços e usar qualquer item que esteja carregando e não exija um teste (como empunhar uma tocha ou aplicar um bálsamo restaurador).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15050],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 15052,
            'name' => 'Companheiro Carregador Mestre',
            'description' => 'Pode carregar 10 espaços e usar qualquer item que esteja carregando e não exija um teste (como empunhar uma tocha ou aplicar um bálsamo restaurador).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15051],
                ['tag' => 'mod_inventory_space', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 15053,
            'name' => 'Companheiro Combatente Iniciante',
            'description' => 'Você recebe +2 em testes de ataque.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15054,
            'name' => 'Companheiro Combatente Veterano',
            'description' => 'Você recebe +3 em testes de ataque.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15053],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15055,
            'name' => 'Companheiro Combatente Mestre',
            'description' => 'Você recebe +4 em testes de ataque.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15054],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 15056,
            'name' => 'Companheiro Combatente Mestre (Ataque Extra)',
            'description' => 'Uma vez por rodada, você pode gastar 5 PM para fazer um ataque extra.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 5,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15057,
            'name' => 'Companheiro Destruidor Iniciante',
            'description' => 'Uma vez por rodada, como uma ação livre, você pode gastar 1 PM para causar 2d6 pontos de dano de ácido, eletricidade, fogo ou frio (de acordo com o parceiro) em um alvo em alcance curto.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 1,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15058,
            'name' => 'Companheiro Destruidor Veterano',
            'description' => 'Você pode gastar 2 PM para causar 4d6 pontos de dano.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 2,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15059,
            'name' => 'Companheiro Destruidor Mestre',
            'description' => 'Você pode gastar 4 PM para causar 6d6 pontos de dano em uma área de 6m de raio em alcance médio.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 4,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15060,
            'name' => 'Companheiro Emissário Iniciante',
            'description' => 'Você recebe +2 em Diplomacia e Intuição.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15061,
            'name' => 'Companheiro Emissário Veterano',
            'description' => 'No início de cada cena, você pode gastar 2 PM para fornecer +2 em testes de perícias baseadas em Carisma para você e seus aliados até o final da cena.',
            'source' => 'companion_granted',
            'usability' => 'active',
            'duration' => null,
            'pm_cost' => 2,
            'icon_file_name' => null,
        ]);

        //TODO fix this when we add nobre
        Power::create([
            'id' => 15062,
            'name' => 'Companheiro Emissário Mestre',
            'description' => 'Você pode usar o poder Jogo da Corte.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15063,
            'name' => 'Companheiro Espião Iniciante',
            'description' => 'Você recebe +2 em Furtividade e Investigação.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15064,
            'name' => 'Companheiro Espião Veterano',
            'description' => 'Você faz testes de Investigação na metade do tempo e não sofre penalidade em testes de Furtividade por se mover no seu deslocamento normal.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15065,
            'name' => 'Companheiro Espião Mestre',
            'description' => 'Quando faz um teste de Furtividade ou Investigação, você pode gastar 2 PM para rolar dois dados e usar o melhor resultado.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 11],
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 15],
            ],
        ]);

        Power::create([
            'id' => 15066,
            'name' => 'Companheiro Fortão Iniciante',
            'description' => 'Uma vez por rodada, você recebe +1d8 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d8'],
            ],
        ]);

        Power::create([
            'id' => 15067,
            'name' => 'Companheiro Fortão Veterano',
            'description' => 'Uma vez por rodada, você recebe +1d12 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15066],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d12'],
            ],
        ]);

        Power::create([
            'id' => 15068,
            'name' => 'Companheiro Fortão Mestre',
            'description' => 'Uma vez por rodada, você recebe +3d6 em uma rolagem de dano corpo a corpo.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['melee']],
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15067],
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '3d6'],
            ],
        ]);

        Power::create([
            'id' => 15069,
            'name' => 'Companheiro Guardião Iniciante',
            'description' => 'Você recebe +2 na Defesa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15070,
            'name' => 'Companheiro Guardião Veterano',
            'description' => 'Você recebe +3 na Defesa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15069],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15071,
            'name' => 'Companheiro Guardião Mestre',
            'description' => 'Você recebe +4 na Defesa e +2 em testes de resistência.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
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
            'name' => 'Companheiro Magivocador Iniciante',
            'description' => 'O dano de suas magias aumenta em +1 dado do mesmo tipo.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_spell_dmg', 'op' => 'extra_die', 'value' => 'spell_die'],
            ],
        ]);

        Power::create([
            'id' => 15073,
            'name' => 'Companheiro Magivocador Veterano',
            'description' => 'O dano de suas magias aumenta em +1 dado do mesmo tipo e a CD para resistir a suas magias aumenta em +1.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15072],
                ['tag' => 'mod_spell_dmg', 'op' => 'extra_die', 'value' => 'spell_die'],
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 15074,
            'name' => 'Companheiro Magivocador Mestre',
            'description' => 'O dano de suas magias aumenta em +2 dados do mesmo tipo e a CD para resistir a suas magias aumenta em +2.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15073],
                ['tag' => 'mod_spell_dmg', 'op' => 'extra_die', 'value' => 'spell_die'],
                ['tag' => 'mod_spell_dmg', 'op' => 'extra_die', 'value' => 'spell_die'],
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        //TODO fix this when we add healing others
        Power::create([
            'id' => 15075,
            'name' => 'Companheiro Médico Iniciante',
            'description' => 'Uma vez por rodada você pode gastar 1 PM para curar 1d8+1 PV de uma criatura adjacente.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        //TODO fix this when we add healing others
        Power::create([
            'id' => 15076,
            'name' => 'Companheiro Médico Veterano',
            'description' => 'Você pode gastar 3 PM para curar 3d8+3 PV ou remover uma condição prejudicial (como abalado ou fatigado).',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        //TODO fix this when we add healing others
        Power::create([
            'id' => 15077,
            'name' => 'Companheiro Médico Mestre',
            'description' => 'Você pode gastar 5 PM para curar 6d8+6 PV.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15078,
            'name' => 'Companheiro Menestrel Iniciante',
            'description' => '+1 em ajustes de recompensa e 1 ponto de fama bônus sempre que ganha fama.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 15079,
            'name' => 'Companheiro Menestrel Veterano',
            'description' => '+1 em ajustes de recompensa e 2 pontos de fama bônus sempre que ganha fama.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15078],
            ],
        ]);

        Power::create([
            'id' => 15080,
            'name' => 'Companheiro Menestrel Mestre',
            'description' => '+2 em ajustes de recompensa e 3 pontos de fama bônus sempre que ganha fama.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15079],
            ],
        ]);

        Power::create([
            'id' => 15081,
            'name' => 'Companheiro Perseguidor Iniciante',
            'description' => 'Você recebe +2 em Percepção e Sobrevivência.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15082,
            'name' => 'Companheiro Perseguidor Veterano',
            'description' => 'Você pode usar Sentidos Aguçados.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11036],
            ],
        ]);

        Power::create([
            'id' => 15083,
            'name' => 'Companheiro Perseguidor Mestre',
            'description' => 'Você pode usar Percepção às Cegas.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 11035],
            ],
        ]);

        Power::create([
            'id' => 15084,
            'name' => 'Companheiro Sábio Iniciante',
            'description' => 'Você recebe +2 em Conhecimento, Misticismo e Nobreza e pode fazer testes destas perícias mesmo sem ser treinado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 6, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15085,
            'name' => 'Companheiro Sábio Veterano',
            'description' => 'O bônus se aplica a uma perícia adicional, a sua escolha (exceto Luta ou Pontaria).<br><br>No APP, ative o poder do companheiro quando for rolar a perícia escolhida.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 15086,
            'name' => 'Companheiro Sábio Mestre',
            'description' => 'Você recebe +3 em Conhecimento, Misticismo e Nobreza e pode fazer testes destas perícias mesmo sem ser treinado.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15084],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 6, 'value' => 3],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 3],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15087,
            'name' => 'Companheiro Sábio Mestre (Perícia Adicional)',
            'description' => 'O bônus na perícia adicional, a sua escolha (exceto Luta ou Pontaria), muda para +3.<br><br>No APP, ative o poder do companheiro quando for rolar a perícia escolhida.',
            'source' => 'companion_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 15085],
                ['tag' => 'all_skills_no_combat', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 15088,
            'name' => 'Companheiro Vigilante Iniciante',
            'description' => 'Você recebe +2 em Percepção e Iniciativa.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 13, 'value' => 2],
            ],
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15089,
            'name' => 'Companheiro Vigilante Veterano',
            'description' => 'Você pode usar Esquiva Sobrenatural.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        //TODO fix this when we add ladino
        Power::create([
            'id' => 15090,
            'name' => 'Companheiro Vigilante Mestre',
            'description' => 'Você pode usar Olhos nas Costas.',
            'source' => 'companion_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);
    }
}
