<?php

namespace Database\Seeders;

use App\Models\Weapon;
use Illuminate\Database\Seeder;

class WeaponSeeder extends Seeder
{

    public function run(): void
    {

        Weapon::create([
            'id' => 1,
            'name' => 'Espada Curta',
            'description' => 'O tipo mais comum de espada, usada por guardas ou como arma secundária de guerreiros mais capazes. Mede entre 40 e 50cm.',
            'cost' => 10,

            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d6',
            'base_margin' => 19,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'slots' => 1,
            'icon_file_name' => 'espada_curta_01.webp',
        ]);

        Weapon::create([
            'id' => 2,
            'name' => 'Cimitarra',
            'description' => 'Uma espada de lâmina curva, mais leve que uma espada longa.',
            'cost' => 15,
            'proficiency_id' => 40,
            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d6',
            'base_margin' => 18,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'slots' => 1,
            'icon_file_name' => 'cimitarra_01.webp',
            'ability_ids' => [2],
        ]);

        Weapon::create([
            'id' => 3,
            'name' => 'Machado de Guerra',
            'description' => 'Este imenso machado com lâmina dupla é uma das armas mais perigosas que existem.',
            'cost' => 20,
            'proficiency_id' => 40,
            'purpose' => 'melee',
            'grip' => 'two_hand',
            'base_dmg' => '1d12',
            'base_margin' => 20,
            'base_multiplier' => 3,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'slots' => 2,
            'icon_file_name' => 'machado_de_guerra_01.webp',
        ]);

        Weapon::create([
            'id' => 4,
            'name' => 'Desarmado',
            'description' => 'Ataque desarmado — dano de impacto não letal, não afetado por efeitos que visam armas.',
            'cost' => -1,

            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d3',
            'base_margin' => 20,
            'base_reach' => 0,
            'damage_type' => 'bludgeoning',
            'slots' => 0,
            'icon_file_name' => 'desarmado_01.webp',
        ]);

        Weapon::create([
            'id' => 6,
            'name' => 'Arco Curto',
            'description' => 'Uma arma antiga e comum, este arco é usado primariamente como ferramenta de caça, embora seja usado como arma de guerra por milícias, bandidos e exércitos menos equipados. Exige as duas mãos, mas pode ser usado montado.',
            'cost' => 30,
            'purpose' => 'fired',
            'grip' => 'two_hand',
            'base_dmg' => '1d6',
            'base_margin' => 20,
            'base_multiplier' => 3,
            'base_reach' => 30,
            'damage_type' => 'piercing',
            'slots' => 1,
            'icon_file_name' => 'arco_curto_01.webp',
        ]);

        Weapon::create([
            'id' => 7,
            'name' => 'Pistola-Tambor',
            'description' => 'Esta arma de fogo possui um tambor giratório que armazena quatro munições. Esse tambor é parte de um mecanismo complexo e por isso conta como uma melhoria para a arma. Recarregar uma pistola-tambor é uma ação completa.',
            'cost' => 2100,
            'proficiency_id' => 268,
            'purpose' => 'fired',
            'is_firearm' => true,
            'grip' => 'one_hand',
            'base_dmg' => '2d6',
            'base_margin' => 19,
            'base_multiplier' => 3,
            'base_reach' => 9,
            'damage_type' => 'piercing',
            'slots' => 1,
            'pre_applied_upgrade_ids' => ['improvement_ids' => [32]],
            'icon_file_name' => 'pistola_tambor_01.webp',
        ]);

        Weapon::create([
            'id' => 8,
            'name' => 'Pistola',
            'description' => 'A arma de fogo mais comum. Uma pistola conta como uma arma leve para propósitos do poder Estilo de Duas Armas e similares. Recarregar uma pistola é uma ação padrão.',
            'cost' => 250,
            'proficiency_id' => 41,
            'purpose' => 'fired',
            'is_firearm' => true,
            'grip' => 'one_hand',
            'base_dmg' => '2d6',
            'base_margin' => 19,
            'base_multiplier' => 3,
            'base_reach' => 9,
            'damage_type' => 'piercing',
            'slots' => 1,
            'icon_file_name' => 'pistola_01.webp',
        ]);

        //TODO remove this, its just for testing criticals
        Weapon::create([
            'id' => 5,
            'name' => 'Machado de Guerra CRITADOR',
            'description' => 'Este imenso machado com lâmina dupla é uma das armas mais perigosas que existem.',
            'cost' => 20,
            'proficiency_id' => 40,
            'purpose' => 'melee',
            'grip' => 'two_hand',
            'base_dmg' => '1d12',
            'base_margin' => 8,
            'base_multiplier' => 3,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'slots' => 2,
            'icon_file_name' => 'machado_de_guerra_01.webp',
        ]);
    }
}
