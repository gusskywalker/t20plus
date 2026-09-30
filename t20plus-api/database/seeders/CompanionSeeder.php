<?php

namespace Database\Seeders;

use App\Models\Companion;
use Illuminate\Database\Seeder;

class CompanionSeeder extends Seeder
{

    public function run(): void
    {

        Companion::create([
            'id' => 1,
            'name' => 'Monstro Conjurado',
            'description' => 'Um monstro Pequeno que ataca seus inimigos. Não é uma criatura real, e sim um construto feito de energia. Se for destruído, ou quando a magia acaba, desaparece com um brilho, sem deixar nada para trás. O monstro não age sem receber uma ordem.',
            'type' => 'conjurado',
            'base_stats' => [
                'base_size' => -1,
                'base_str' => 2,
                'base_dex' => 3,
                'base_defense' => 'caster',
                'base_reflexes' => 'caster',
                'base_max_pv' => 20,
                'base_movement' => 9,
                'base_attack' => [
                    'dmg' => '2d4+2',
                    'reach' => 0,
                    'damage_types' => ['slashing', 'bludgeoning', 'piercing'],
                ],
                'base_immunities' => ['fortitude', 'vontade'],
            ],
            'source_spell_id' => 33,
            'icon_file_name' => 'conjurar_monstro_01.webp',
        ]);

        Companion::create([
            'id' => 2,
            'name' => "Aquin'ne",
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15000],
            ],
            'source_power_id' => 2014,
            'icon_file_name' => 'familiar_aquinne_01.webp',
        ]);

        Companion::create([
            'id' => 3,
            'name' => 'Asa-Assassina',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15001],
            ],
            'source_power_id' => 2015,
            'icon_file_name' => 'familiar_asa_de_aco_01.webp',
        ]);

        Companion::create([
            'id' => 4,
            'name' => 'Borboleta',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15002],
            ],
            'source_power_id' => 2016,
            'icon_file_name' => 'familiar_borboleta_01.webp',
        ]);

        Companion::create([
            'id' => 5,
            'name' => 'Chibi-Kabuto',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15003],
            ],
            'source_power_id' => 2017,
            'icon_file_name' => 'familiar_chibi_kabuto_01.webp',
        ]);

        Companion::create([
            'id' => 6,
            'name' => 'Cobra',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15004],
            ],
            'source_power_id' => 2018,
            'icon_file_name' => 'familiar_cobra_01.webp',
        ]);

        Companion::create([
            'id' => 7,
            'name' => 'Coruja',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15005],
            ],
            'source_power_id' => 2019,
            'icon_file_name' => 'familiar_coruja_01.webp',
        ]);

        Companion::create([
            'id' => 8,
            'name' => 'Diabrete',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15006],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15007],
            ],
            'source_power_id' => 2020,
            'icon_file_name' => 'familiar_diabrete_01.webp',
        ]);

        Companion::create([
            'id' => 9,
            'name' => 'Dragão',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15008],
            ],
            'source_power_id' => 2023,
            'icon_file_name' => 'familiar_dragao_01.webp',
        ]);

        Companion::create([
            'id' => 10,
            'name' => 'Falcão',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15009],
            ],
            'source_power_id' => 2024,
            'icon_file_name' => 'familiar_falcao_01.webp',
        ]);

        Companion::create([
            'id' => 11,
            'name' => 'Fuinha',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15010],
            ],
            'source_power_id' => 2025,
            'icon_file_name' => 'familiar_fuinha_01.webp',
        ]);

        Companion::create([
            'id' => 12,
            'name' => 'Gato',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15011],
            ],
            'source_power_id' => 2026,
            'icon_file_name' => 'familiar_gato_01.webp',
        ]);

        Companion::create([
            'id' => 13,
            'name' => 'Homúnculo',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15012],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15013],
            ],
            'source_power_id' => 2027,
            'icon_file_name' => 'familiar_homunculo_01.webp',
        ]);

        Companion::create([
            'id' => 14,
            'name' => 'Lagarto',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15014],
            ],
            'source_power_id' => 2030,
            'icon_file_name' => 'familiar_lagarato_01.webp',
        ]);

        Companion::create([
            'id' => 15,
            'name' => 'Macaco',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15015],
            ],
            'source_power_id' => 2031,
            'icon_file_name' => 'familiar_macaco_01.webp',
        ]);

        Companion::create([
            'id' => 16,
            'name' => 'Morcego',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15016],
            ],
            'source_power_id' => 2032,
            'icon_file_name' => 'famliar_morcego_01.webp',
        ]);

        Companion::create([
            'id' => 17,
            'name' => 'Pakk',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15017],
            ],
            'source_power_id' => 2033,
            'icon_file_name' => 'familiar_pakk_01.webp',
        ]);

        Companion::create([
            'id' => 18,
            'name' => 'Papagaio Zumbi',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15018],
            ],
            'source_power_id' => 2034,
            'icon_file_name' => 'familiar_papagaio_zumbi_01.webp',
        ]);

        Companion::create([
            'id' => 19,
            'name' => 'Rato',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15019],
            ],
            'source_power_id' => 2035,
            'icon_file_name' => 'familiar_rato_01.webp',
        ]);

        Companion::create([
            'id' => 20,
            'name' => 'Sapo',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15020],
            ],
            'source_power_id' => 2036,
            'icon_file_name' => 'familiar_sapo_01.webp',
        ]);

        Companion::create([
            'id' => 21,
            'name' => 'Stahg',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15021],
            ],
            'source_power_id' => 2037,
            'icon_file_name' => 'familiar_stahg_01.webp',
        ]);

        Companion::create([
            'id' => 22,
            'name' => 'Tartaruga',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15022],
            ],
            'source_power_id' => 2038,
            'icon_file_name' => 'familiar_tartaruga_01.webp',
        ]);

        Companion::create([
            'id' => 23,
            'name' => 'Tentacule',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15023],
            ],
            'source_power_id' => 2039,
            'icon_file_name' => 'familiar_tentacule_01.webp',
        ]);

        Companion::create([
            'id' => 24,
            'name' => 'Terrier',
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15024],
            ],
            'source_power_id' => 2040,
            'icon_file_name' => 'familiar_terrier_01.webp',
        ]);

        Companion::create([
            'id' => 25,
            'name' => "T'peel",
            'description' => 'Um familiar é uma criatura mágica. Em termos de jogo, é um parceiro especial com o qual você pode se comunicar telepaticamente em alcance longo. Ele obedece a suas ordens, mas ainda está limitado ao que uma criatura de sua espécie pode fazer. Se ele morrer, você fica atordoado por uma rodada. Você pode invocar um novo familiar com um ritual que exige um dia e T$ 100 em ingredientes.',
            'type' => 'familiar',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15025],
            ],
            'source_power_id' => 2041,
            'icon_file_name' => 'familiar_tpel_01.webp',
        ]);

        Companion::create([
            'id' => 26,
            'name' => 'Capanga Elemental',
            'description' => 'Um capanga elemental Pequeno criado por Gênese Elemental. Ele desaparece quando é reduzido a 0 PV ou no fim da cena.',
            'type' => 'capanga',
            'base_stats' => [
                'base_size' => -1,
                'base_defense' => 15,
                'base_movement' => 9,
                'base_max_pv' => 1,
                'base_attack' => [
                    'dmg' => '1d6+1',
                ],
            ],
            'icon_file_name' => 'capanga_elemental_01.webp',
        ]);

        Companion::create([
            'id' => 27,
            'name' => 'Parceiro Iniciante (Aberrante)',
            'description' => 'Apenas devotos de Aharadak podem ter esse parceiro.',
            'type' => 'parceiro',
            'parceiro_type' => 'aberrante',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15026],
            ],
            'icon_file_name' => 'comp_aberrante_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 28,
            'name' => 'Parceiro Veterano (Aberrante)',
            'description' => 'Apenas devotos de Aharadak podem ter esse parceiro.',
            'type' => 'parceiro',
            'parceiro_type' => 'aberrante',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15026],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15027],
            ],
            'icon_file_name' => 'comp_aberrante_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 29,
            'name' => 'Parceiro Mestre (Aberrante)',
            'description' => 'Apenas devotos de Aharadak podem ter esse parceiro.',
            'type' => 'parceiro',
            'parceiro_type' => 'aberrante',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15026],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15027],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15028],
            ],
            'icon_file_name' => 'comp_aberrante_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 30,
            'name' => 'Parceiro Iniciante (Adepto)',
            'description' => 'Um conjurador capaz de ajudá-lo a lançar suas magias.',
            'type' => 'parceiro',
            'parceiro_type' => 'adepto',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15029],
            ],
            'icon_file_name' => 'comp_adepto_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 31,
            'name' => 'Parceiro Veterano (Adepto)',
            'description' => 'Um conjurador capaz de ajudá-lo a lançar suas magias.',
            'type' => 'parceiro',
            'parceiro_type' => 'adepto',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15029],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15030],
            ],
            'icon_file_name' => 'comp_adepto_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 32,
            'name' => 'Parceiro Mestre (Adepto)',
            'description' => 'Um conjurador capaz de ajudá-lo a lançar suas magias.',
            'type' => 'parceiro',
            'parceiro_type' => 'adepto',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15029],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15030],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15031],
            ],
            'icon_file_name' => 'comp_adepto_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 33,
            'name' => 'Parceiro Iniciante (Ajudante)',
            'description' => 'Um bardo, nobre ou sábio que ajuda com palavras firmes ou encorajadoras.',
            'type' => 'parceiro',
            'parceiro_type' => 'ajudante',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15032],
            ],
            'icon_file_name' => 'comp_ajudante_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 34,
            'name' => 'Parceiro Veterano (Ajudante)',
            'description' => 'Um bardo, nobre ou sábio que ajuda com palavras firmes ou encorajadoras.',
            'type' => 'parceiro',
            'parceiro_type' => 'ajudante',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15032],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15033],
            ],
            'icon_file_name' => 'comp_ajudante_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 35,
            'name' => 'Parceiro Mestre (Ajudante)',
            'description' => 'Um bardo, nobre ou sábio que ajuda com palavras firmes ou encorajadoras.',
            'type' => 'parceiro',
            'parceiro_type' => 'ajudante',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15032],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15033],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15034],
            ],
            'icon_file_name' => 'comp_ajudante_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 36,
            'name' => 'Parceiro Iniciante (Arauto)',
            'description' => 'Um bardo, nobre ou outro NPC versado em assuntos de etiqueta e diplomacia.',
            'type' => 'parceiro',
            'parceiro_type' => 'arauto',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15035],
            ],
            'icon_file_name' => 'comp_arauto_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 37,
            'name' => 'Parceiro Veterano (Arauto)',
            'description' => 'Um bardo, nobre ou outro NPC versado em assuntos de etiqueta e diplomacia.',
            'type' => 'parceiro',
            'parceiro_type' => 'arauto',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15035],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15036],
            ],
            'icon_file_name' => 'comp_arauto_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 38,
            'name' => 'Parceiro Mestre (Arauto)',
            'description' => 'Um bardo, nobre ou outro NPC versado em assuntos de etiqueta e diplomacia.',
            'type' => 'parceiro',
            'parceiro_type' => 'arauto',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15035],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15036],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15037],
            ],
            'icon_file_name' => 'comp_arauto_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 39,
            'name' => 'Parceiro Iniciante (Artesão)',
            'description' => 'Um artesão, como um alquimista ou um ferreiro.',
            'type' => 'parceiro',
            'parceiro_type' => 'artesao',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15038],
            ],
            'icon_file_name' => 'comp_artesao_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 40,
            'name' => 'Parceiro Veterano (Artesão)',
            'description' => 'Um artesão, como um alquimista ou um ferreiro.',
            'type' => 'parceiro',
            'parceiro_type' => 'artesao',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15038],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15039],
            ],
            'icon_file_name' => 'comp_artesao_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 41,
            'name' => 'Parceiro Mestre (Artesão)',
            'description' => 'Um artesão, como um alquimista ou um ferreiro.',
            'type' => 'parceiro',
            'parceiro_type' => 'artesao',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15038],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15039],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15040],
            ],
            'icon_file_name' => 'comp_artesao_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 42,
            'name' => 'Parceiro Iniciante (Assassino)',
            'description' => 'Um ladino ou outro tipo furtivo e letal.',
            'type' => 'parceiro',
            'parceiro_type' => 'assassino',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15041],
            ],
            'icon_file_name' => 'comp_assassino_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 43,
            'name' => 'Parceiro Veterano (Assassino)',
            'description' => 'Um ladino ou outro tipo furtivo e letal.',
            'type' => 'parceiro',
            'parceiro_type' => 'assassino',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15041],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15042],
            ],
            'icon_file_name' => 'comp_assassino_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 44,
            'name' => 'Parceiro Mestre (Assassino)',
            'description' => 'Um ladino ou outro tipo furtivo e letal.',
            'type' => 'parceiro',
            'parceiro_type' => 'assassino',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15041],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15042],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15043],
            ],
            'icon_file_name' => 'comp_assassino_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 45,
            'name' => 'Parceiro Iniciante (Atirador)',
            'description' => 'Um arqueiro, besteiro ou outro combatente à distância.',
            'type' => 'parceiro',
            'parceiro_type' => 'atirador',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15044],
            ],
            'icon_file_name' => 'comp_atirador_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 46,
            'name' => 'Parceiro Veterano (Atirador)',
            'description' => 'Um arqueiro, besteiro ou outro combatente à distância.',
            'type' => 'parceiro',
            'parceiro_type' => 'atirador',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15044],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15045],
            ],
            'icon_file_name' => 'comp_atirador_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 47,
            'name' => 'Parceiro Mestre (Atirador)',
            'description' => 'Um arqueiro, besteiro ou outro combatente à distância.',
            'type' => 'parceiro',
            'parceiro_type' => 'atirador',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15044],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15045],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15046],
            ],
            'icon_file_name' => 'comp_atirador_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 48,
            'name' => 'Parceiro Iniciante (Besta de Carga)',
            'description' => 'Um animal capaz de carregar peso, como um boi, burro ou mula.',
            'type' => 'parceiro',
            'parceiro_type' => 'besta_de_carga',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15047],
            ],
            'icon_file_name' => 'comp_besta_carga_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 49,
            'name' => 'Parceiro Veterano (Besta de Carga)',
            'description' => 'Um animal capaz de carregar peso, como um boi, burro ou mula.',
            'type' => 'parceiro',
            'parceiro_type' => 'besta_de_carga',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15047],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15048],
            ],
            'icon_file_name' => 'comp_besta_carga_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 50,
            'name' => 'Parceiro Mestre (Besta de Carga)',
            'description' => 'Um animal capaz de carregar peso, como um boi, burro ou mula.',
            'type' => 'parceiro',
            'parceiro_type' => 'besta_de_carga',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15047],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15048],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15049],
            ],
            'icon_file_name' => 'comp_besta_carga_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 51,
            'name' => 'Parceiro Iniciante (Carregador)',
            'description' => 'Alguém forte e corajoso para acompanhá-lo em suas aventuras enquanto carrega seu equipamento.',
            'type' => 'parceiro',
            'parceiro_type' => 'carregador',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15050],
            ],
            'icon_file_name' => 'comp_carregador_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 52,
            'name' => 'Parceiro Veterano (Carregador)',
            'description' => 'Alguém forte e corajoso para acompanhá-lo em suas aventuras enquanto carrega seu equipamento.',
            'type' => 'parceiro',
            'parceiro_type' => 'carregador',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15050],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15051],
            ],
            'icon_file_name' => 'comp_carregador_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 53,
            'name' => 'Parceiro Mestre (Carregador)',
            'description' => 'Alguém forte e corajoso para acompanhá-lo em suas aventuras enquanto carrega seu equipamento.',
            'type' => 'parceiro',
            'parceiro_type' => 'carregador',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15050],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15051],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15052],
            ],
            'icon_file_name' => 'comp_carregador_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 54,
            'name' => 'Parceiro Iniciante (Combatente)',
            'description' => 'Um bucaneiro, guerreiro, paladino ou animal de caça.',
            'type' => 'parceiro',
            'parceiro_type' => 'combatente',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15053],
            ],
            'icon_file_name' => 'comp_combatente_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 55,
            'name' => 'Parceiro Veterano (Combatente)',
            'description' => 'Um bucaneiro, guerreiro, paladino ou animal de caça.',
            'type' => 'parceiro',
            'parceiro_type' => 'combatente',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15053],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15054],
            ],
            'icon_file_name' => 'comp_combatente_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 56,
            'name' => 'Parceiro Mestre (Combatente)',
            'description' => 'Um bucaneiro, guerreiro, paladino ou animal de caça.',
            'type' => 'parceiro',
            'parceiro_type' => 'combatente',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15053],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15054],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15055],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15056],
            ],
            'icon_file_name' => 'comp_combatente_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 57,
            'name' => 'Parceiro Iniciante (Destruidor)',
            'description' => 'Um arcanista ou inventor.',
            'type' => 'parceiro',
            'parceiro_type' => 'destruidor',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15057],
            ],
            'icon_file_name' => 'comp_destruidor_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 58,
            'name' => 'Parceiro Veterano (Destruidor)',
            'description' => 'Um arcanista ou inventor.',
            'type' => 'parceiro',
            'parceiro_type' => 'destruidor',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15057],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15058],
            ],
            'icon_file_name' => 'comp_destruidor_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 59,
            'name' => 'Parceiro Mestre (Destruidor)',
            'description' => 'Um arcanista ou inventor.',
            'type' => 'parceiro',
            'parceiro_type' => 'destruidor',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15057],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15058],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15059],
            ],
            'icon_file_name' => 'comp_destruidor_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 60,
            'name' => 'Parceiro Iniciante (Emissário)',
            'description' => 'Um menestrel ou cortesão treinado em apresentações solenes.',
            'type' => 'parceiro',
            'parceiro_type' => 'emissario',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15060],
            ],
            'icon_file_name' => 'comp_emissario_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 61,
            'name' => 'Parceiro Veterano (Emissário)',
            'description' => 'Um menestrel ou cortesão treinado em apresentações solenes.',
            'type' => 'parceiro',
            'parceiro_type' => 'emissario',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15060],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15061],
            ],
            'icon_file_name' => 'comp_emissario_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 62,
            'name' => 'Parceiro Mestre (Emissário)',
            'description' => 'Um menestrel ou cortesão treinado em apresentações solenes.',
            'type' => 'parceiro',
            'parceiro_type' => 'emissario',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15060],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15061],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15062],
            ],
            'icon_file_name' => 'comp_emissario_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 63,
            'name' => 'Parceiro Iniciante (Espião)',
            'description' => 'Um indivíduo discreto e de ouvidos abertos.',
            'type' => 'parceiro',
            'parceiro_type' => 'espiao',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15063],
            ],
            'icon_file_name' => 'comp_espiao_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 64,
            'name' => 'Parceiro Veterano (Espião)',
            'description' => 'Um indivíduo discreto e de ouvidos abertos.',
            'type' => 'parceiro',
            'parceiro_type' => 'espiao',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15063],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15064],
            ],
            'icon_file_name' => 'comp_espiao_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 65,
            'name' => 'Parceiro Mestre (Espião)',
            'description' => 'Um indivíduo discreto e de ouvidos abertos.',
            'type' => 'parceiro',
            'parceiro_type' => 'espiao',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15063],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15064],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15065],
            ],
            'icon_file_name' => 'comp_espiao_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 66,
            'name' => 'Parceiro Iniciante (Fortão)',
            'description' => 'Um bárbaro, lutador ou outro tipo que bate primeiro e pensa depois.',
            'type' => 'parceiro',
            'parceiro_type' => 'fortao',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15066],
            ],
            'icon_file_name' => 'comp_fortao_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 67,
            'name' => 'Parceiro Veterano (Fortão)',
            'description' => 'Um bárbaro, lutador ou outro tipo que bate primeiro e pensa depois.',
            'type' => 'parceiro',
            'parceiro_type' => 'fortao',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15066],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15067],
            ],
            'icon_file_name' => 'comp_fortao_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 68,
            'name' => 'Parceiro Mestre (Fortão)',
            'description' => 'Um bárbaro, lutador ou outro tipo que bate primeiro e pensa depois.',
            'type' => 'parceiro',
            'parceiro_type' => 'fortao',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15066],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15067],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15068],
            ],
            'icon_file_name' => 'comp_fortao_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 69,
            'name' => 'Parceiro Iniciante (Guardião)',
            'description' => 'Um cavaleiro, cão de guarda ou outro NPC cuja função primária é proteger.',
            'type' => 'parceiro',
            'parceiro_type' => 'guardiao',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15069],
            ],
            'icon_file_name' => 'comp_guardiao_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 70,
            'name' => 'Parceiro Veterano (Guardião)',
            'description' => 'Um cavaleiro, cão de guarda ou outro NPC cuja função primária é proteger.',
            'type' => 'parceiro',
            'parceiro_type' => 'guardiao',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15069],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15070],
            ],
            'icon_file_name' => 'comp_guardiao_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 71,
            'name' => 'Parceiro Mestre (Guardião)',
            'description' => 'Um cavaleiro, cão de guarda ou outro NPC cuja função primária é proteger.',
            'type' => 'parceiro',
            'parceiro_type' => 'guardiao',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15069],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15070],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15071],
            ],
            'icon_file_name' => 'comp_guardiao_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 72,
            'name' => 'Parceiro Iniciante (Magivocador)',
            'description' => 'Um conjurador especializado em magias ofensivas.',
            'type' => 'parceiro',
            'parceiro_type' => 'magivocador',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15072],
            ],
            'icon_file_name' => 'comp_magivocador_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 73,
            'name' => 'Parceiro Veterano (Magivocador)',
            'description' => 'Um conjurador especializado em magias ofensivas.',
            'type' => 'parceiro',
            'parceiro_type' => 'magivocador',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15072],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15073],
            ],
            'icon_file_name' => 'comp_magivocador_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 74,
            'name' => 'Parceiro Mestre (Magivocador)',
            'description' => 'Um conjurador especializado em magias ofensivas.',
            'type' => 'parceiro',
            'parceiro_type' => 'magivocador',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15072],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15073],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15074],
            ],
            'icon_file_name' => 'comp_magivocador_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 75,
            'name' => 'Parceiro Iniciante (Médico)',
            'description' => 'Um clérigo, druida, herbalista ou outro NPC com capacidades curativas.',
            'type' => 'parceiro',
            'parceiro_type' => 'medico',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15075],
            ],
            'icon_file_name' => 'comp_medico_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 76,
            'name' => 'Parceiro Veterano (Médico)',
            'description' => 'Um clérigo, druida, herbalista ou outro NPC com capacidades curativas.',
            'type' => 'parceiro',
            'parceiro_type' => 'medico',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15075],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15076],
            ],
            'icon_file_name' => 'comp_medico_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 77,
            'name' => 'Parceiro Mestre (Médico)',
            'description' => 'Um clérigo, druida, herbalista ou outro NPC com capacidades curativas.',
            'type' => 'parceiro',
            'parceiro_type' => 'medico',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15075],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15076],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15077],
            ],
            'icon_file_name' => 'comp_medico_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 78,
            'name' => 'Parceiro Iniciante (Menestrel)',
            'description' => 'Um bardo que acompanha o grupo durante suas aventuras e cria canções e poemas sobre suas aventuras, com a finalidade de aumentar sua fama.',
            'type' => 'parceiro',
            'parceiro_type' => 'menestrel',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15078],
            ],
            'icon_file_name' => 'comp_menestrel_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 79,
            'name' => 'Parceiro Veterano (Menestrel)',
            'description' => 'Um bardo que acompanha o grupo durante suas aventuras e cria canções e poemas sobre suas aventuras, com a finalidade de aumentar sua fama.',
            'type' => 'parceiro',
            'parceiro_type' => 'menestrel',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15078],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15079],
            ],
            'icon_file_name' => 'comp_menestrel_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 80,
            'name' => 'Parceiro Mestre (Menestrel)',
            'description' => 'Um bardo que acompanha o grupo durante suas aventuras e cria canções e poemas sobre suas aventuras, com a finalidade de aumentar sua fama.',
            'type' => 'parceiro',
            'parceiro_type' => 'menestrel',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15078],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15079],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15080],
            ],
            'icon_file_name' => 'comp_menestrel_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 81,
            'name' => 'Parceiro Iniciante (Perseguidor)',
            'description' => 'Um caçador, animal farejador ou outro especialista em localizar alvos.',
            'type' => 'parceiro',
            'parceiro_type' => 'perseguidor',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15081],
            ],
            'icon_file_name' => 'comp_perseguidor_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 82,
            'name' => 'Parceiro Veterano (Perseguidor)',
            'description' => 'Um caçador, animal farejador ou outro especialista em localizar alvos.',
            'type' => 'parceiro',
            'parceiro_type' => 'perseguidor',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15081],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15082],
            ],
            'icon_file_name' => 'comp_perseguidor_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 83,
            'name' => 'Parceiro Mestre (Perseguidor)',
            'description' => 'Um caçador, animal farejador ou outro especialista em localizar alvos.',
            'type' => 'parceiro',
            'parceiro_type' => 'perseguidor',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15081],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15082],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15083],
            ],
            'icon_file_name' => 'comp_perseguidor_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 84,
            'name' => 'Parceiro Iniciante (Sábio)',
            'description' => 'Um estudioso de assuntos diversos.',
            'type' => 'parceiro',
            'parceiro_type' => 'sabio',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15084],
            ],
            'icon_file_name' => 'comp_sabio_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 85,
            'name' => 'Parceiro Veterano (Sábio)',
            'description' => 'Um estudioso de assuntos diversos.',
            'type' => 'parceiro',
            'parceiro_type' => 'sabio',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15084],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15085],
            ],
            'icon_file_name' => 'comp_sabio_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 86,
            'name' => 'Parceiro Mestre (Sábio)',
            'description' => 'Um estudioso de assuntos diversos.',
            'type' => 'parceiro',
            'parceiro_type' => 'sabio',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15084],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15085],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15086],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15087],
            ],
            'icon_file_name' => 'comp_sabio_mestre_01.webp',
        ]);

        Companion::create([
            'id' => 87,
            'name' => 'Parceiro Iniciante (Vigilante)',
            'description' => 'Um vigia ou animal de guarda, sempre atento aos arredores.',
            'type' => 'parceiro',
            'parceiro_type' => 'vigilante',
            'parceiro_tier' => 'iniciante',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15088],
            ],
            'icon_file_name' => 'comp_vigilante_iniciante_01.webp',
        ]);

        Companion::create([
            'id' => 88,
            'name' => 'Parceiro Veterano (Vigilante)',
            'description' => 'Um vigia ou animal de guarda, sempre atento aos arredores.',
            'type' => 'parceiro',
            'parceiro_type' => 'vigilante',
            'parceiro_tier' => 'veterano',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15088],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15089],
            ],
            'icon_file_name' => 'comp_vigilante_veterano_01.webp',
        ]);

        Companion::create([
            'id' => 89,
            'name' => 'Parceiro Mestre (Vigilante)',
            'description' => 'Um vigia ou animal de guarda, sempre atento aos arredores.',
            'type' => 'parceiro',
            'parceiro_type' => 'vigilante',
            'parceiro_tier' => 'mestre',
            'character_related_effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15088],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15089],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 15090],
            ],
            'icon_file_name' => 'comp_vigilante_mestre_01.webp',
        ]);
    }
}
