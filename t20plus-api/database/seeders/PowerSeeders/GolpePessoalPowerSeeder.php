<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 13000 and 13999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class GolpePessoalPowerSeeder extends Seeder
{

    public function run(): void
    {

        Power::create([
            'id' => 116,
            'name' => 'Conjurador',
            'description' => 'Escolha uma magia de 1º ou 2º círculos que tenha como alvo uma criatura ou que afete uma área. Se acertar seu golpe, você lança a magia como uma ação livre, tendo como alvo a criatura atingida ou como centro de sua área o ponto atingido pelo ataque (atributo-chave é um mental a sua escolha). Considere que a mão da arma está livre para lançar esta magia.',
            'source' => 'specific',
            'usability' => 'passive',

            'pm_cost' => 1,
            // TODO: no effects — needs a full spellcasting system (spell

        ]);

        Power::create([
            'id' => 117,
            'name' => 'Amplo',
            'description' => 'Seu ataque atinge todas as criaturas em alcance curto (incluindo aliados, mas não você mesmo). Faça um único teste de ataque e compare com a Defesa de cada criatura. <br><br>No APP, sem efeitos automáticos além do custo de PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 3,

        ]);

        Power::create([
            'id' => 118,
            'name' => 'Atordoante',
            'description' => 'Uma criatura que sofra dano do ataque fica atordoada por uma rodada (apenas uma vez por cena; Fortitude CD For anula). <br><br>No APP, sem efeitos automáticos além do custo de PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,

        ]);

        Power::create([
            'id' => 119,
            'name' => 'Destruidor',
            'description' => 'Aumenta o multiplicador de crítico em +1.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            'effects' => [
                ['tag' => 'mod_multiplier', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 120,
            'name' => 'Elemental',
            'description' => 'Causa +2d6 pontos de dano de ácido, eletricidade, fogo ou frio. Você pode escolher este efeito mais vezes para aumentar o dano em +2d6 (do mesmo tipo ou de outro), por +2 PM a cada vez.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d6'],
            ],
        ]);

        Power::create([
            'id' => 121,
            'name' => 'Letal',
            'description' => 'Aumenta a margem de ameaça em +2. Você pode escolher este efeito duas vezes para aumentar a margem de ameaça em +5.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            'effects' => [

                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 122,
            'name' => 'Sequencial',
            'description' => 'Seu golpe causa +1d6 pontos de dano. A cada vez que você acerta o golpe na mesma cena, esse bônus aumenta em um passo. <br><br>No APP, sem efeitos automáticos além do custo de PM. Role manualmente o bônus atual de dano extra.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,

        ]);

        Power::create([
            'id' => 123,
            'name' => 'Sifão',
            'description' => 'Você recebe 1 PM temporário para cada 10 pontos da rolagem de dano. Você pode receber um máximo de PM temporários por cena igual ao seu nível e eles desaparecem no fim da cena. No APP, sem efeitos automáticos além do custo de PM. Adicione manualmente o PM temporário.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,

        ]);

        Power::create([
            'id' => 124,
            'name' => 'Avanço',
            'description' => 'Você pode percorrer até o seu deslocamento em linha reta antes de desferir o golpe. <br><br>No APP, sem efeitos automáticos além do custo de PM.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 125,
            'name' => 'Brutal',
            'description' => 'Fornece um dado extra de dano do mesmo tipo.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [

                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'weapon_die'],
            ],
        ]);

        Power::create([
            'id' => 126,
            'name' => 'Carregado',
            'description' => 'Você pode gastar uma ação padrão para energizar seu ataque. Se você fizer isso e atacar até a próxima rodada, seu ataque causa +2d8 pontos de dano. <br><br>No APP, o dano será adicionado automaticamente, mas você deve gastar a ação padrão manualmente.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '2d8'],
            ],

        ]);

        Power::create([
            'id' => 127,
            'name' => 'Distante',
            'description' => 'Aumenta o alcance em um passo (de corpo a corpo para curto, médio e longo). Outras características não mudam (um ataque corpo a corpo com alcance curto continua usando Luta e somando sua Força no dano).',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 128,
            'name' => 'Impactante',
            'description' => 'Empurra o alvo 1,5m para cada 10 pontos de dano causado (arredondado para baixo). Por exemplo, 3m para 22 pontos de dano.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [
                ['tag' => 'push_distance', 'op' => 'add', 'value' => '1.5m/10damage'],
            ],
        ]);

        Power::create([
            'id' => 129,
            'name' => 'Preciso',
            'description' => 'Quando faz o teste de ataque, você rola dois dados e usa o melhor resultado.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [

                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'hit'],
            ],
        ]);

        Power::create([
            'id' => 130,
            'name' => 'Qualquer Arma',
            'description' => 'Você pode usar seu Golpe Pessoal com qualquer tipo de arma.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 131,
            'name' => 'Ricocheteante',
            'description' => 'A arma volta pra você após o ataque. Só pode ser usado com armas de arremesso.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 132,
            'name' => 'Teleguiado',
            'description' => 'Ignora penalidades por camuflagem ou cobertura leves.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 133,
            'name' => 'Brando',
            'description' => 'Seu golpe causa dano não letal.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 0,
        ]);

        Power::create([
            'id' => 134,
            'name' => 'Golpe de Abertura',
            'description' => 'Seu golpe só pode ser usado em seu primeiro turno do combate.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 135,
            'name' => 'Truque Secreto',
            'description' => 'Seu golpe só pode ser usado uma vez contra cada alvo por cena.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 136,
            'name' => 'Lento',
            'description' => 'Seu ataque exige uma ação completa para ser usado.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 137,
            'name' => 'Perto da Morte',
            'description' => 'O ataque só pode ser usado se você estiver com um quarto de seus PV ou menos. <br><br>No APP, sem efeitos automáticos além da redução de custo de PM. Siga a regra dos 1/4 PV manualmente.',

            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 138,
            'name' => 'Sacrifício',
            'description' => 'Sempre que usa seu Golpe Pessoal, você perde 10 PV.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
            'effects' => [
                ['tag' => 'self_damage', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 139,
            'name' => 'Penetrante',
            'description' => 'Ignora 10 pontos de RD.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
            'effects' => [
                ['tag' => 'ignore_dr', 'op' => 'add', 'value' => 10],
            ],
        ]);    }
}
