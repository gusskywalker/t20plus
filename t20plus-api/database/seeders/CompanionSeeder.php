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
            'icon_file_name' => null,
        ]);
    }
}
