<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 19000 and 19999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class ExoticWeaponProficiencyPowerSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 44,
            'name' => 'Proficiência - Arco de Guerra',
            'description' => 'Você recebe proficiência em arcos de guerra.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_arco_de_guerra_01.webp',
        ]);

        Power::create([
            'id' => 268,
            'name' => 'Proficiência - Pistola-Tambor',
            'description' => 'Você recebe proficiência em pistolas-tambor.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'pistola_tambor_01.webp',
        ]);

        Power::create([
            'id' => 19000,
            'name' => 'Proficiência - Arpão',
            'description' => 'Você recebe proficiência em arpões.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_arpao_01.webp',
        ]);

        Power::create([
            'id' => 19001,
            'name' => 'Proficiência - Rede',
            'description' => 'Você recebe proficiência em redes.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_rede_01.webp',
        ]);

        Power::create([
            'id' => 19002,
            'name' => 'Proficiência - Katar',
            'description' => 'Você recebe proficiência em katares.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19003,
            'name' => 'Proficiência - Kimbata',
            'description' => 'Você recebe proficiência em kimbatas.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19004,
            'name' => 'Proficiência - Açoite Finntroll',
            'description' => 'Você recebe proficiência em açoites finntroll.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19005,
            'name' => 'Proficiência - At’mokhet',
            'description' => 'Você recebe proficiência em at’mokhets.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19006,
            'name' => 'Proficiência - Chicote',
            'description' => 'Você recebe proficiência em chicotes.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19007,
            'name' => 'Proficiência - Espada Bastarda',
            'description' => 'Você recebe proficiência em espadas bastardas.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19008,
            'name' => 'Proficiência - Espada Canora',
            'description' => 'Você recebe proficiência em espadas canoras.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19009,
            'name' => 'Proficiência - Espada Vespa',
            'description' => 'Você recebe proficiência em espadas vespa.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19010,
            'name' => 'Proficiência - Espada-Calibre',
            'description' => 'Você recebe proficiência em espadas-calibre.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19011,
            'name' => 'Proficiência - Espada-Gadanho',
            'description' => 'Você recebe proficiência em espadas-gadanho.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19012,
            'name' => 'Proficiência - Katana',
            'description' => 'Você recebe proficiência em katanas.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19013,
            'name' => 'Proficiência - Khopesh',
            'description' => 'Você recebe proficiência em khopeshes.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19014,
            'name' => 'Proficiência - Kum’shrak',
            'description' => 'Você recebe proficiência em kum’shraks.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19015,
            'name' => 'Proficiência - Lança de Falange',
            'description' => 'Você recebe proficiência em lanças de falange.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19016,
            'name' => 'Proficiência - Lâmina de Essência',
            'description' => 'Você recebe proficiência em lâminas de essência.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19017,
            'name' => 'Proficiência - Machado Anão',
            'description' => 'Você recebe proficiência em machados anões.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19018,
            'name' => 'Proficiência - Machado de Haste',
            'description' => 'Você recebe proficiência em machados de haste.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19019,
            'name' => 'Proficiência - Maça de Guerra',
            'description' => 'Você recebe proficiência em maças de guerra.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19020,
            'name' => 'Proficiência - Rapieira',
            'description' => 'Você recebe proficiência em rapieiras.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19021,
            'name' => 'Proficiência - Sabre Élfico',
            'description' => 'Você recebe proficiência em sabres élficos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19022,
            'name' => 'Proficiência - Mordida do Diabo',
            'description' => 'Você recebe proficiência em mordidas do diabo.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19023,
            'name' => 'Proficiência - Presa de Serpente',
            'description' => 'Você recebe proficiência em presas de serpente.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19024,
            'name' => 'Proficiência - Wakizashi',
            'description' => 'Você recebe proficiência em wakizashis.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19025,
            'name' => 'Proficiência - Corrente de Espinhos',
            'description' => 'Você recebe proficiência em correntes de espinhos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19026,
            'name' => 'Proficiência - Gythka',
            'description' => 'Você recebe proficiência em gythkas.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19027,
            'name' => 'Proficiência - Marrão',
            'description' => 'Você recebe proficiência em marrões.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19028,
            'name' => 'Proficiência - Machado Táurico',
            'description' => 'Você recebe proficiência em machados táuricos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19029,
            'name' => 'Proficiência - Montante Cinético',
            'description' => 'Você recebe proficiência em montantes cinéticos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19030,
            'name' => 'Proficiência - Shuriken',
            'description' => 'Você recebe proficiência em shurikens.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19031,
            'name' => 'Proficiência - Boleadeira',
            'description' => 'Você recebe proficiência em boleadeiras.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19032,
            'name' => 'Proficiência - Chakram',
            'description' => 'Você recebe proficiência em chakrams.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19033,
            'name' => 'Proficiência - Arco Élfico',
            'description' => 'Você recebe proficiência em arcos élficos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19034,
            'name' => 'Proficiência - Balestra',
            'description' => 'Você recebe proficiência em balestras.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 19035,
            'name' => 'Proficiência - Besta de Repetição',
            'description' => 'Você recebe proficiência em bestas de repetição.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => null,
        ]);
    }
}
