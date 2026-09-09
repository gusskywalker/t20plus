<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class GeneralPowerSeeder extends Seeder
{

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
        ]);

        Power::create([
            'id' => 70,
            'name' => 'Saque Rápido',
            'description' => 'Você recebe +2 em Iniciativa e pode sacar ou guardar itens como uma ação livre (em vez de ação de movimento). Além disso, a ação que você gasta para recarregar armas de disparo diminui em uma categoria (ação completa para padrão, padrão para movimento, movimento para livre).',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'saque_rapido_01.webp',
            'effects' => [
                ['tag' => 'skill', 'skill_id' => 13, 'op' => 'add', 'value' => 2],
            ],
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 13],
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
                ['tag' => 'skill', 'skill_id' => 10, 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 72,
            'name' => 'Ataque Poderoso',
            'description' => 'Sempre que faz um ataque corpo a corpo, você pode sofrer –2 no teste de ataque para receber +5 na rolagem de dano.',
            'source' => 'general',

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
                ['tag' => 'skill', 'skill_id' => 26, 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 259,
            'name' => 'Estilo de Duas Armas',
            'description' => 'Se estiver empunhando duas armas (e pelo menos uma delas for leve) e fizer a ação agredir, você pode fazer dois ataques, um com cada arma. Se fizer isso, sofre –2 em todos os testes de ataque até o seu próximo turno. Se possuir Ambidestria, em vez disso não sofre penalidade para usá-lo.',
            'source' => 'general',
            'usability' => 'roll_active',
            'default_checked' => true,
            'icon_file_name' => 'estilo_duas_armas_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 2],
                ['type' => 'skill_trained', 'skill_id' => 19],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 260,
            'name' => 'Arma Secundária Grande',
            'description' => 'Você pode empunhar normalmente duas armas de uma mão (sem o requisito da segunda arma ser leve).',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'arma_secundaria_grande_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_id' => 259],
            ],
            'effects' => [

                ['tag' => 'allow_dual_wield_full', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 261,
            'name' => 'Empunhadura Poderosa',
            'description' => 'Ao usar uma arma feita para uma categoria de tamanho maior que a sua, a penalidade que você sofre nos testes de ataque diminui para –2 (normalmente, usar uma arma de uma categoria de tamanho maior impõe –5 nos testes de ataque).',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'empunhadura_poderosa_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'str', 'min' => 3],
            ],
            'effects' => [

                ['tag' => 'reduce_weapon_size_penalty', 'op' => 'set', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 263,
            'name' => 'Estilo de Disparo',
            'description' => 'Se estiver usando uma arma de disparo, você soma sua Destreza nas rolagens de dano.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'estilo_de_disparo_01.webp',
            'applies_when' => ['weapon_purpose' => ['fired']],
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 25],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 'dex'],
            ],
        ]);

        Power::create([
            'id' => 264,
            'name' => 'Estilo de Arremesso',
            'description' => 'Você pode sacar armas de arremesso como uma ação livre e recebe +2 nas rolagens de dano com elas. Se também possuir o poder Saque Rápido, também recebe +2 nos testes de ataque com essas armas.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'estilo_de_arremesso_01.webp',
            'applies_when' => ['weapon_purpose' => ['thrown']],
            'prerequisites' => [
                ['type' => 'skill_trained', 'skill_id' => 25],
            ],
            'effects' => [
                // TODO Only the unconditional +2 dano — the Saque Rápido (id 70)

                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 265,
            'name' => 'Disparo Preciso',
            'description' => 'Você pode fazer ataques à distância contra oponentes envolvidos em combate corpo a corpo sem sofrer a penalidade de –5 no teste de ataque.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'disparo_preciso_01.webp',
            'prerequisites' => [
                ['type' => 'power', 'power_ids_any' => [263, 264]],
            ],
            'effects' => [

                ['tag' => 'nullify_ranged_weapon_melee_penalty', 'op' => 'grant'],
            ],
        ]);

        Power::create([
            'id' => 266,
            'name' => 'Mira Apurada',
            'description' => 'Quando usa a ação mirar, você recebe +2 em testes de ataque e na margem de ameaça com ataques à distância até o fim do turno.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'mira_apurada_01.webp',
            'prerequisites' => [
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
                ['type' => 'power', 'power_id' => 265],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],

                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 268,
            'name' => 'Proficiência - Pistola-Tambor',
            'description' => 'Você recebe proficiência em pistolas-tambor.',
            'source' => 'general',
            'usability' => 'passive',
            'icon_file_name' => 'pistola_tambor_01.webp',
        ]);

        // TODO implement this correctly when adding parceiros
        Power::create([
            'id' => 285,
            'name' => 'Parceiro Combatente (Iniciante)',
            'description' => 'Seu parceiro fornece +2 em acerto. <br><br>No APP, ative o poder quando o parceiro estiver na cena.',
            'source' => 'specific',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
            ],
        ]);

        // TODO implement this correctly when we add roles and campaigns
        Power::create([
            'id' => 286,
            'name' => 'Xerife',
            'description' => 'Você é o xerife designado da campanha.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'skill_id' => 13, 'op' => 'add', 'value' => 1],
            ],
        ]);

        // TODO implement this correctly when we add roles and campaigns
        Power::create([
            'id' => 287,
            'name' => 'Arquivista',
            'description' => 'Você é o arquivista designado da campanha.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'skill_id' => 8, 'op' => 'add', 'value' => 1],
            ],
        ]);

        // TODO implement this correctly when we add roles and campaigns
        Power::create([
            'id' => 288,
            'name' => 'Advogado',
            'description' => 'Você é o advogado designado da campanha.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        // TODO implement this correctly when we add roles and campaigns
        Power::create([
            'id' => 289,
            'name' => 'Estrategista',
            'description' => 'Você é o estrategista designado da campanha.',
            'source' => 'specific',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'skill_id' => 12, 'op' => 'add', 'value' => 1],
            ],
        ]);
    }
}
