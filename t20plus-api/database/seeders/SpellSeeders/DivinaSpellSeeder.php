<?php

namespace Database\Seeders\SpellSeeders;

use App\Models\Spell;
use Illuminate\Database\Seeder;

class DivinaSpellSeeder extends Seeder
{

    public function run(): void
    {

        Spell::create([
            'id' => 6,
            'name' => 'Comando',
            'description' => 'Você dá uma ordem irresistível, que o alvo deve ser capaz de ouvir (mas não precisa entender). Se falhar na resistência, ele deve obedecer ao comando em seu próprio turno da melhor maneira possível. Escolha um dos efeitos. Fuja: o alvo gasta seu turno se afastando de você (usando todas as suas ações). Largue: o alvo solta quaisquer itens que esteja segurando e não pode pegá-los novamente até o início de seu próximo turno. Como esta é uma ação livre, ele ainda pode executar outras ações (exceto pegar aquilo que largou). Pare: o alvo fica pasmo (apenas uma vez por cena). Senta: com uma ação livre, o alvo senta no chão (se estava pendurado ou voando, desce até o chão). Ele pode fazer outras ações, mas não se levantar até o início de seu próximo turno. Venha: o alvo gasta seu turno se aproximando de você (usando todas as suas ações).',
            'type' => 'divina',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'effect' => '1 humanoide',
            'duration' => '1 rodada',
            'resistance' => 'vontade',
            'icon_file_name' => 'comando_01.webp',
            'effects' => [
                ['trigger' => 'on_spell_fail', 'tag' => 'condition', 'op' => 'no_condition_caused'],
            ],
            'enhancements' => [
                [
                    'description' => 'muda o alvo para 1 criatura.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'aumenta a quantidade de alvos em +1.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
            ],
        ]);

        Spell::create([
            'id' => 9,
            'name' => 'Abençoar Alimentos',
            'description' => 'Você purifica e abençoa uma porção de comida ou dose de bebida. Isso torna um alimento sujo, estragado ou envenenado próprio para consumo. Além disso, se for consumido até o final da duração, o alimento oferece 5 PV temporários ou 1 PM temporário (além de quaisquer bônus que já oferecesse). Bônus de alimentação duram um dia e cada personagem só pode receber um bônus de alimentação por dia.',
            'type' => 'divina',
            'circle' => 1,
            'school' => 'transmutacao',
            'action_cost' => 'standard',
            'range' => 'curto',
            'effect' => 'alimento para 1 criatura',
            'duration' => 'cena',
            'resistance' => null,
            'icon_file_name' => null,
            'enhancements' => [
                [
                    'description' => 'o alimento é purificado (não causa nenhum efeito nocivo se estava estragado ou envenenado), mas não fornece bônus ao ser consumido.',
                    'pm_cost' => 0,
                    'repeatable' => false,
                    'is_truque' => true,
                ],
                [
                    'description' => 'aumenta o número de alvos em +1.',
                    'pm_cost' => 1,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda a duração para permanente, o alvo para 1 frasco com água e adiciona componente material (pó de prata no valor de T$ 5). Em vez do normal, cria um frasco de água benta.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
            ],
        ]);

        Spell::create([
            'id' => 10,
            'name' => 'Acalmar Animal',
            'description' => 'O animal fica prestativo em relação a você. Ele não fica sob seu controle, mas percebe suas palavras e ações da maneira mais favorável possível. Você recebe +10 nos testes de Adestramento e Diplomacia que fizer contra o animal. Um alvo hostil ou que esteja envolvido em um combate recebe +5 em seu teste de resistência. Se você ou seus aliados tomarem qualquer ação hostil contra o alvo, a magia é dissipada e ele retorna à atitude que tinha antes (ou piorada, de acordo com o mestre). Se tratar bem o alvo, a atitude pode permanecer mesmo após o término da magia.',
            'type' => 'divina',
            'circle' => 1,
            'school' => 'encantamento',
            'action_cost' => 'standard',
            'range' => 'curto',
            'effect' => '1 animal',
            'duration' => 'cena',
            'resistance' => 'vontade',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 2, 'value' => 10, 'usability' => 'roll_active'],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 10, 'usability' => 'roll_active'],
            ],
            'enhancements' => [
                [
                    'description' => 'muda o alcance para médio.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '1',
                ],
                [
                    'description' => 'muda o alvo para 1 monstro ou espírito com Inteligência 1 ou 2.',
                    'pm_cost' => 1,
                    'repeatable' => false,
                    'is_truque' => false,
                    'unique_change_group' => '2',
                ],
                [
                    'description' => 'aumenta o número de alvos em +1.',
                    'pm_cost' => 2,
                    'repeatable' => true,
                    'is_truque' => false,
                ],
                [
                    'description' => 'muda o alvo para 1 monstro ou espírito. Requer 3º círculo.',
                    'pm_cost' => 5,
                    'repeatable' => false,
                    'is_truque' => false,
                    'min_circle' => 3,
                    'unique_change_group' => '2',
                ],
            ],
        ]);
    }
}
