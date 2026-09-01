<?php

namespace Database\Seeders;

use App\Models\WeaponAbility;
use Illuminate\Database\Seeder;

class WeaponAbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row, same convention as every other
        // seeder — weapons.ability_ids references these directly.
        WeaponAbility::create([
            'id' => 1,
            'name' => 'Adaptável',
            'description' => 'Uma arma de uma mão com esta habilidade pode ser usada com as duas mãos para aumentar seu dano em um passo.',
        ]);

        WeaponAbility::create([
            'id' => 2,
            'name' => 'Ágil',
            'description' => 'Pode ser usada com Acuidade com Arma, mesmo não sendo uma arma leve.',
        ]);

        WeaponAbility::create([
            'id' => 3,
            'name' => 'Alongada',
            'description' => 'Dobra o alcance natural do atacante, mas não permite atacar um adversário adjacente.',
        ]);

        WeaponAbility::create([
            'id' => 4,
            'name' => 'Desbalanceada',
            'description' => 'Impõe uma penalidade de -2 em testes de ataque.',
        ]);

        WeaponAbility::create([
            'id' => 5,
            'name' => 'Dupla',
            'description' => 'Pode ser usada com Estilo de Duas Armas (e poderes similares) para fazer ataques adicionais, como se fosse uma arma de uma mão e uma arma leve. Cada "ponta" conta como uma arma separada para efeitos de melhorias e encantos.',
        ]);

        WeaponAbility::create([
            'id' => 6,
            'name' => 'Híbrida (AA)',
            'description' => 'Uma arma híbrida possui dois ou mais modos de uso. Quando usa a arma, você considera apenas as características do modo que está usando, e aplica apenas habilidades e efeitos que afetem este modo. Trocar de modo é uma ação de movimento (ou livre, se tiver Saque Rápido). Aplicar melhorias e encantos em uma arma híbrida custa o dobro do preço em tibares.',
        ]);

        WeaponAbility::create([
            'id' => 7,
            'name' => 'Ocultável (DH)',
            'description' => 'O tamanho e/ou formato da arma tornam mais fácil escondê-la. Ela fornece +5 em testes de Ladinagem para ocultá-la. A adaga é uma arma ocultável.',
        ]);

        WeaponAbility::create([
            'id' => 8,
            'name' => 'Surpreendente (DH)',
            'description' => 'Uma vez por cena, se você sacar a arma como ação livre e usá-la para atacar no mesmo turno, o oponente fica desprevenido contra esse ataque.',
        ]);

        WeaponAbility::create([
            'id' => 9,
            'name' => 'Versátil',
            'description' => 'Fornece bônus em uma ou mais manobras (cumulativo com outros bônus de itens), conforme a arma.',
        ]);
    }
}
