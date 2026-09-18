<?php

namespace Database\Seeders;

use App\Models\Weapon;
use Illuminate\Database\Seeder;

class WeaponSeeder extends Seeder
{
    //This file must use IDs between 0 and 999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
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

        //TODO adicionar a parte de "arremessar" essa arma, quando fizermos os bglh de arremesso especificamente sei lá.
        Weapon::create([
            'id' => 9,
            'name' => 'Adaga',
            'description' => 'Esta faca afiada é usada por muitos habitantes adultos do Reinado, embora seja favorita de ladrões e assassinos, por ser facilmente escondida.',
            'cost' => 2,
            'purpose' => 'melee',
            'grip' => 'light',
            'base_dmg' => '1d4',
            'base_margin' => 19,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'slots' => 1,
            'icon_file_name' => 'adaga_01.webp',
        ]);

        Weapon::create([
            'id' => 10,
            'name' => 'Faca de Corte',
            'description' => 'Esta faca afiada é usada por muitos habitantes adultos do Reinado, especialmente coureiros experientes.',
            'cost' => -1,
            'purpose' => 'melee',
            'grip' => 'light',
            'base_dmg' => '1d4',
            'base_margin' => 19,
            'base_reach' => 0,
            'damage_type' => 'slashing',
            'slots' => 1,
            'icon_file_name' => 'faca_afiada_01.webp',
        ]);

        Weapon::create([
            'id' => 5,
            'name' => 'Arco de Guerra',
            'description' => 'Este arco robusto possui braços grossos e resistentes, capazes de disparos poderosos. Como um arco longo, permite que você aplique sua Força às rolagens de dano e não pode ser usado se você estiver montado. A força exigida para puxar o arco de guerra o torna uma arma desbalanceada.',
            'cost' => 200,
            'proficiency_id' => 40,
            'purpose' => 'fired',
            'grip' => 'two_hand',
            'base_dmg' => '1d12',
            'base_margin' => 20,
            'base_multiplier' => 3,
            'base_reach' => 30,
            'damage_type' => 'piercing',
            'slots' => 2,
            'ability_ids' => [4],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 318],
            ],
            'icon_file_name' => 'arco_de_guerra_01.webp',
        ]);

        Weapon::create([
            'id' => 11,
            'name' => 'Funda',
            'description' => 'Uma simples tira de couro usada para arremessar balas de metal. Recarregar uma funda é uma ação de movimento. Ao contrário de outras armas de disparo, você aplica seu modificador de Força a rolagens de dano com uma funda.',
            'cost' => 0,
            'purpose' => 'fired',
            'grip' => 'one_hand',
            'base_dmg' => '1d4',
            'base_margin' => 20,
            'base_multiplier' => 2,
            'base_reach' => 30,
            'damage_type' => 'bludgeoning',
            'slots' => 1,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14000],
            ],
            'icon_file_name' => 'funda_01.webp',
        ]);

        Weapon::create([
            'id' => 12,
            'name' => 'Tridente',
            'description' => 'Uma lança com três pontas, favorita de povos marinhos e gladiadores e própria para prender as pernas do oponente. O tridente é uma arma versátil, fornecendo +2 em testes para derrubar.',
            'cost' => 15,
            'proficiency_id' => 40,
            'purpose' => 'thrown',
            'grip' => 'one_hand',
            'base_dmg' => '1d8',
            'base_margin' => 20,
            'base_multiplier' => 2,
            'base_reach' => 9,
            'damage_type' => 'piercing',
            'slots' => 1,
            'ability_ids' => [9],
            'icon_file_name' => 'tridente_01.webp',
        ]);

        Weapon::create([
            'id' => 13,
            'name' => 'Arpão (Arremesso)',
            'description' => 'Originalmente um instrumento de pesca, esta haste afiada tem rebarbas em uma extremidade e uma corda na outra. Com um ataque bem-sucedido, o arpão fica preso na vítima. Enquanto você segura a corda, sempre que a vítima se move, você pode fazer um teste de Força oposto como uma reação. Se você vencer, a vítima só pode se mover até o limite da corda (9m). A vítima pode se soltar gastando uma ação de movimento, mas perde 1d10 pontos de vida. O arpão é uma arma de arremesso; pode ser usado como arma corpo a corpo, mas com penalidade de –5 no teste de ataque. Esta é uma arma para devotos de Oceano, e se beneficia de poderes concedidos que afetam essas armas (como Arsenal das Profundezas).',
            'cost' => 30,
            'proficiency_id' => 19000,
            'purpose' => 'thrown',
            'grip' => 'one_hand',
            'base_dmg' => '1d10',
            'base_margin' => 20,
            'base_multiplier' => 3,
            'base_reach' => 9,
            'damage_type' => 'piercing',
            'slots' => 1,
            'icon_file_name' => 'arpao_01.webp',
        ]);

        Weapon::create([
            'id' => 14,
            'name' => 'Arpão (Corpo a Corpo)',
            'description' => 'O arpão pode ser usado como arma corpo a corpo, mas com penalidade de –5 no teste de ataque. <br><br>No APP, caso faça uma melhoria no seu arpão de arremesso. Faça aqui também e adicione os Tibares de volta. Se for usar seu Arpão corpo a corpo, equipe essa versão da arma.',
            'cost' => 0,
            'proficiency_id' => 19000,
            'purpose' => 'melee',
            'grip' => 'one_hand',
            'base_dmg' => '1d10',
            'base_margin' => 20,
            'base_multiplier' => 3,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'slots' => 0,
            'icon_file_name' => 'arpao_01.webp',
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -5],
            ],
        ]);

        Weapon::create([
            'id' => 15,
            'name' => 'Rede',
            'description' => 'A rede não causa dano nem ameaça crítico. Quando um ataque com ela acerta, o alvo fica enredado.',
            'cost' => 20,
            'proficiency_id' => 19001,
            'purpose' => 'thrown',
            'grip' => 'two_hand',
            'base_dmg' => '1d1',
            'base_margin' => 20,
            'base_multiplier' => 2,
            'base_reach' => 9,
            'damage_type' => 'bludgeoning',
            'slots' => 1,
            'icon_file_name' => 'rede_01.webp',
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14002],
            ],
        ]);
    }
}
