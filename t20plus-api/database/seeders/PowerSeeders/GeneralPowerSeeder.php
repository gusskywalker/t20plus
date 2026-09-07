<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class GeneralPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Power::create([
            'id' => 6,
            'name' => 'Medicina',
            'description' => 'Você pode gastar uma ação completa para fazer um teste de Cura (CD 15) em uma criatura. Se você passar, ela recupera 1d6 PV, mais 1d6 para cada 5 pontos pelos quais o resultado do teste exceder a CD (2d6 com um resultado 20, 3d6 com um resultado 25 e assim por diante). Você só pode usar este poder uma vez por dia numa mesma criatura.',
            'source' => 'general',
            'usability' => 'active',
            'icon_file_name' => 'medicina_01.webp',
            'action_cost' => 'complete',
        ]);

        Power::create([
            'id' => 7,
            'name' => 'Vontade de Ferro',
            'description' => 'Você recebe +1 PM para cada dois níveis de personagem e +2 em Vontade.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'vontade_de_ferro_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add_per_level', 'value' => 1, 'per_levels' => 2],
                ['tag' => 'skill', 'skill_id' => 29, 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 40,
            'name' => 'Proficiência - Armas Marciais',
            'description' => 'Você recebe proficiência em armas marciais.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_armas_marciais_01.webp',
        ]);

        Power::create([
            'id' => 41,
            'name' => 'Proficiência - Armas de Fogo',
            'description' => 'Você recebe proficiência em armas de fogo.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_armas_de_fogo_01.webp',
        ]);

        Power::create([
            'id' => 42,
            'name' => 'Proficiência - Armaduras Pesadas',
            'description' => 'Você recebe proficiência em armaduras pesadas.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_armadura_pesada_01.webp',
        ]);

        Power::create([
            'id' => 43,
            'name' => 'Proficiência - Escudos',
            'description' => 'Você recebe proficiência em escudos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_escudos_01.webp',
        ]);

        Power::create([
            'id' => 44,
            'name' => 'Proficiência - Arco de Guerra',
            'description' => 'Você recebe proficiência em arcos de guerra.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'proficiencia_arco_de_guerra_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 40], // Proficiência - Armas Marciais
            ]
        ]);

        Power::create([
            'id' => 70,
            'name' => 'Saque Rápido',
            'description' => 'Você recebe +2 em Iniciativa e pode sacar ou guardar itens como uma ação livre (em vez de ação de movimento). Além disso, a ação que você gasta para recarregar armas de disparo diminui em uma categoria (ação completa para padrão, padrão para movimento, movimento para livre).',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'saque_rapido_01.webp',
            'effects' => [
                ['tag' => 'skill', 'skill_id' => 13, 'op' => 'add', 'value' => 2], // Iniciativa
            ],
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 13], // treinado em Iniciativa
            ],
        ]);

        Power::create([
            'id' => 71,
            'name' => 'Vitalidade',
            'description' => 'Você recebe +1 PV por nível de personagem e +2 em Fortitude.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'vitalidade_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'con', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_max_pv', 'op' => 'add_per_level', 'value' => 1, 'per_levels' => 1],
                ['tag' => 'skill', 'skill_id' => 10, 'op' => 'add', 'value' => 2], // Fortitude
            ],
        ]);

        Power::create([
            'id' => 72,
            'name' => 'Ataque Poderoso',
            'description' => 'Sempre que faz um ataque corpo a corpo, você pode sofrer –2 no teste de ataque para receber +5 na rolagem de dano.',
            'source' => 'general',
            // Same as Ataque Especial — rides a roll the player is already
            // making, decided fresh every attack, never persists.
            'usability' => 'roll_active',
            'icon_file_name' => 'ataque_poderoso_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 74,
            'name' => 'Esquiva',
            'description' => 'Você recebe +2 na Defesa e Reflexos.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'esquiva_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 1],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2], // Reflexos
            ],
        ]);
    }
}
