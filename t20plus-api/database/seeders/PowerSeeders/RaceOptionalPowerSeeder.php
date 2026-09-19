<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

//This file must use IDs between 17000 and 17999. Older powers kept their ids, new ones follow this rule. If you are reading this comment it means you are adding a new power.
class RaceOptionalPowerSeeder extends Seeder
{

    public function run(): void
    {
        //TODO add all weapon ids for arcos when items are fully added
        Power::create([
            'id' => 320,
            'name' => 'Arquearia Élfica',
            'description' => 'Para você, todos os arcos são armas simples. Além disso, você recebe +2 nas rolagens de dano com arcos.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => 'arquearia_elfica_01.webp',
            'applies_when' => ['weapon_ids' => [5, 6]],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [7, 22]],
            ],
            'effects' => [
                ['tag' => 'waive_weapon_proficiency', 'op' => 'grant', 'weapon_ids' => [5]],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17000,
            'name' => 'Afinidade Elemental (Água)',
            'description' => 'Você possui ligação com um tipo de elemento do mundo natural. Seu atributo-chave para as magias a seguir é Carisma. Caso aprenda novamente qualquer uma delas, seu custo diminui em –1 PM. Você é ligado a lagos e rios. Provavelmente tem a pele e/ou os cabelos azulados. Você recebe deslocamento de natação igual ao seu deslocamento base e pode lançar as magias Criar Elementos (apenas água) e Névoa.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 1002],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 2005],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17001,
            'name' => 'Afinidade Elemental (Fogo)',
            'description' => 'Você possui ligação com um tipo de elemento do mundo natural. Seu atributo-chave para as magias a seguir é Carisma. Caso aprenda novamente qualquer uma delas, seu custo diminui em –1 PM. Você é ligado a calor e chamas. Provavelmente tem a pele e/ou os cabelos avermelhados. Você recebe redução de fogo 5 e pode lançar as magias Criar Elementos (apenas fogo) e Explosão de Chamas.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'fire'],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 1002],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 19],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17002,
            'name' => 'Afinidade Elemental (Vegetação)',
            'description' => 'Você possui ligação com um tipo de elemento do mundo natural. Seu atributo-chave para as magias a seguir é Carisma. Caso aprenda novamente qualquer uma delas, seu custo diminui em –1 PM. Você é ligado a bosques e florestas. Provavelmente tem a pele e/ou os cabelos esverdeados. Você pode atravessar terrenos difíceis naturais sem sofrer redução em seu deslocamento e pode lançar as magias Armamento da Natureza e Controlar Plantas.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 1003],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 1000],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17003,
            'name' => 'Encantar Objetos',
            'description' => 'Você pode gastar uma ação de movimento e 3 PM para tocar um item e colocar nele um encanto pertinente a sua escolha (por exemplo, se tocar uma arma, pode colocar um encanto de arma nela). O encanto não pode ter pré-requisitos e dura até o fim da cena ou até você usar este poder novamente. <br><br>No APP, adicione o encanto manualmente, depois adicione os tibares gastos.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'movement',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17004,
            'name' => 'Enfeitiçar',
            'description' => 'Suas feições feéricas podem deslumbrar outros seres. Você pode lançar Enfeitiçar e usar seus aprimoramentos como se tivesse acesso aos mesmos círculos de magia que um arcanista de seu nível.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 27],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['tag' => 'spell_circle_as_class', 'op' => 'set', 'spell_id' => 27, 'class_id' => 3],
            ],
        ]);

        Power::create([
            'id' => 17005,
            'name' => 'Língua da Natureza',
            'description' => 'Você recebe +2 em Adestramento e Sobrevivência, e pode falar com animais e plantas (como o efeito da magia Voz Divina).',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 2, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17006,
            'name' => 'Invisibilidade',
            'description' => 'Um poder comum entre duendes e responsável por boa parte do folclore confuso sobre eles (“Como assim você não viu aquela tartaruga alada falante que estava aqui agora há pouco?”). Você pode lançar Invisibilidade e usar seus aprimoramentos como se tivesse acesso aos mesmos círculos de magia que um arcanista de seu nível.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 31],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['tag' => 'spell_circle_as_class', 'op' => 'set', 'spell_id' => 31, 'class_id' => 3],
            ],
        ]);

        Power::create([
            'id' => 17007,
            'name' => 'Maldição (Apatia Profunda)',
            'description' => 'Nem todos os poderes feéricos são fofos ou engraçados… Você pode gastar uma ação padrão e 3 PM para amaldiçoar uma criatura em alcance curto. A criatura tem direito a um teste de resistência. Se falhar, sofre o efeito da maldição. Se passar, fica imune a este presente por um dia. Para construir sua maldição, escolha qual resistência ela permite (entre Fortitude ou Vontade) e seu efeito (da lista abaixo). Uma vez feitas, essas escolhas não podem ser mudadas. A maldição é permanente, mas você pode cancelá-la como uma ação livre, e ela pode ser curada por Purificação com o aprimoramento de +7 PM. Além disso, você só pode manter uma maldição por vez (se quiser amaldiçoar uma nova criatura, precisa cancelar a maldição anterior). Com a permissão do mestre, você pode criar uma maldição personalizada, usando os efeitos abaixo como referência. <br><br>Apatia Profunda. A criatura fica alquebrada e frustrada.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'standard',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17008,
            'name' => 'Maldição (Coração de Geleia)',
            'description' => 'Nem todos os poderes feéricos são fofos ou engraçados… Você pode gastar uma ação padrão e 3 PM para amaldiçoar uma criatura em alcance curto. A criatura tem direito a um teste de resistência. Se falhar, sofre o efeito da maldição. Se passar, fica imune a este presente por um dia. Para construir sua maldição, escolha qual resistência ela permite (entre Fortitude ou Vontade) e seu efeito (da lista abaixo). Uma vez feitas, essas escolhas não podem ser mudadas. A maldição é permanente, mas você pode cancelá-la como uma ação livre, e ela pode ser curada por Purificação com o aprimoramento de +7 PM. Além disso, você só pode manter uma maldição por vez (se quiser amaldiçoar uma nova criatura, precisa cancelar a maldição anterior). Com a permissão do mestre, você pode criar uma maldição personalizada, usando os efeitos abaixo como referência. <br><br>Coração de Geleia. A criatura fica abalada e, na primeira vez que tentar fazer uma ação hostil em cada cena, deve repetir o teste de resistência. Se falhar, não consegue e perde sua ação.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'standard',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17009,
            'name' => 'Maldição (Envelhecimento Súbito)',
            'description' => 'Nem todos os poderes feéricos são fofos ou engraçados… Você pode gastar uma ação padrão e 3 PM para amaldiçoar uma criatura em alcance curto. A criatura tem direito a um teste de resistência. Se falhar, sofre o efeito da maldição. Se passar, fica imune a este presente por um dia. Para construir sua maldição, escolha qual resistência ela permite (entre Fortitude ou Vontade) e seu efeito (da lista abaixo). Uma vez feitas, essas escolhas não podem ser mudadas. A maldição é permanente, mas você pode cancelá-la como uma ação livre, e ela pode ser curada por Purificação com o aprimoramento de +7 PM. Além disso, você só pode manter uma maldição por vez (se quiser amaldiçoar uma nova criatura, precisa cancelar a maldição anterior). Com a permissão do mestre, você pode criar uma maldição personalizada, usando os efeitos abaixo como referência. <br><br>Envelhecimento Súbito. A criatura fica fraca e lenta.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'standard',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17010,
            'name' => 'Maldição (Loucura do Verão)',
            'description' => 'Nem todos os poderes feéricos são fofos ou engraçados… Você pode gastar uma ação padrão e 3 PM para amaldiçoar uma criatura em alcance curto. A criatura tem direito a um teste de resistência. Se falhar, sofre o efeito da maldição. Se passar, fica imune a este presente por um dia. Para construir sua maldição, escolha qual resistência ela permite (entre Fortitude ou Vontade) e seu efeito (da lista abaixo). Uma vez feitas, essas escolhas não podem ser mudadas. A maldição é permanente, mas você pode cancelá-la como uma ação livre, e ela pode ser curada por Purificação com o aprimoramento de +7 PM. Além disso, você só pode manter uma maldição por vez (se quiser amaldiçoar uma nova criatura, precisa cancelar a maldição anterior). Com a permissão do mestre, você pode criar uma maldição personalizada, usando os efeitos abaixo como referência. <br><br>Loucura do Verão. No início de cada cena, a criatura deve repetir o teste de resistência. Se falhar, fica confusa.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'standard',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17011,
            'name' => 'Maldição (Mil Verrugas)',
            'description' => 'Nem todos os poderes feéricos são fofos ou engraçados… Você pode gastar uma ação padrão e 3 PM para amaldiçoar uma criatura em alcance curto. A criatura tem direito a um teste de resistência. Se falhar, sofre o efeito da maldição. Se passar, fica imune a este presente por um dia. Para construir sua maldição, escolha qual resistência ela permite (entre Fortitude ou Vontade) e seu efeito (da lista abaixo). Uma vez feitas, essas escolhas não podem ser mudadas. A maldição é permanente, mas você pode cancelá-la como uma ação livre, e ela pode ser curada por Purificação com o aprimoramento de +7 PM. Além disso, você só pode manter uma maldição por vez (se quiser amaldiçoar uma nova criatura, precisa cancelar a maldição anterior). Com a permissão do mestre, você pode criar uma maldição personalizada, usando os efeitos abaixo como referência. <br><br>Mil Verrugas. A vítima sofre –2 em Carisma e, na primeira vez em cada cena que outra criatura inicie seu turno em alcance curto dela, essa segunda criatura deve fazer um teste de Vontade. Se falhar, a atitude dela em relação à criatura amaldiçoada piora em uma categoria. Ser feio pode matar!',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'standard',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17012,
            'name' => 'Maldição (Ruína do Corpo)',
            'description' => 'Nem todos os poderes feéricos são fofos ou engraçados… Você pode gastar uma ação padrão e 3 PM para amaldiçoar uma criatura em alcance curto. A criatura tem direito a um teste de resistência. Se falhar, sofre o efeito da maldição. Se passar, fica imune a este presente por um dia. Para construir sua maldição, escolha qual resistência ela permite (entre Fortitude ou Vontade) e seu efeito (da lista abaixo). Uma vez feitas, essas escolhas não podem ser mudadas. A maldição é permanente, mas você pode cancelá-la como uma ação livre, e ela pode ser curada por Purificação com o aprimoramento de +7 PM. Além disso, você só pode manter uma maldição por vez (se quiser amaldiçoar uma nova criatura, precisa cancelar a maldição anterior). Com a permissão do mestre, você pode criar uma maldição personalizada, usando os efeitos abaixo como referência. <br><br>Ruína do Corpo. A criatura fica fatigada e vulnerável.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'standard',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);
        
        //TODO fix this when we add conditions correctly. Make it inflic camuflagem leve on self
        Power::create([
            'id' => 17013,
            'name' => 'Mais Lá do que Aqui',
            'description' => 'Você pode gastar uma ação padrão e 2 PM para fazer seu corpo, exceto por uma parte (como a cabeça ou uma cauda), desaparecer pela cena. Nesse estado, recebe camuflagem leve e +5 em Furtividade.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'standard',
            'duration' => 'scene',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 20],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 5],
            ],
        ]);

        //TODO fix this once we add druida
        Power::create([
            'id' => 17014,
            'name' => 'Metamorfose Animal',
            'description' => 'Você pode se transformar em um tipo de animal. Escolha uma forma selvagem do druida, como ágil ou veloz (veja Tormenta20, p. 63). Você pode gastar uma ação completa e 3 PM para assumir essa forma, recebendo todos seus modificadores. Ao contrário de um druida, você pode falar e lançar magias mesmo em sua forma selvagem. Porém, você pode assumir apenas a forma escolhida, e apenas em sua versão básica.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'complete',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17015,
            'name' => 'Sonhos Proféticos',
            'description' => 'Uma vez por cena, você pode gastar 3 PM para ter uma visão que, graças a sua natureza feérica, provavelmente se concretizará. Role 1d20. Até o fim da cena, você pode substituir o resultado do d20 de um teste realizado por uma criatura em alcance curto pelo resultado do dado que você rolou ao usar este presente.',
            'source' => 'race_optional',
            'usability' => 'active',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17016,
            'name' => 'Velocidade do Pensamento',
            'description' => 'Em seu primeiro turno em cada cena, você pode gastar 2 PM para realizar uma ação padrão adicional. Se fizer isso, você pula seu turno na segunda rodada.',
            'source' => 'race_optional',
            'usability' => 'active',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
        ]);

        Power::create([
            'id' => 17017,
            'name' => 'Visão Feérica',
            'description' => 'Você recebe visão na penumbra e está permanentemente sob efeito da magia Visão Mística com o aprimoramento de enxergar criaturas e objetos invisíveis.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 16011],
            ],
        ]);

        Power::create([
            'id' => 17018,
            'name' => 'Voo',
            'description' => 'Você possui asas ou alguma fonte de voo mágico. Consegue flutuar 1,5m acima do nível do chão com deslocamento igual ao seu deslocamento base +3m, o que permite que ignore terreno difícil e torna-o imune a dano por queda (a menos que esteja inconsciente). Você também pode voar, mas isso é cansativo: você gasta 1 PM por rodada para voar com deslocamento igual ao seu deslocamento base +6m.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'scene',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'add', 'value' => 6],
            ],
        ]);
    }
}
