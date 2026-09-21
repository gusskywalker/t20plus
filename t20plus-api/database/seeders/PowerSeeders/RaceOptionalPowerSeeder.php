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
            'icon_file_name' => 'afinidade_agua_01.webp',
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
            'icon_file_name' => 'afinidade_fogo_01.webp',
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
            'icon_file_name' => 'afinidade_vegetacao_01.webp',
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
            'icon_file_name' => 'duende_encantar_objetos_01.webp',
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
            'icon_file_name' => 'duende_encantar_01.webp',
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
            'icon_file_name' => 'lingua_da_natureza_01.webp',
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
            'icon_file_name' => 'duende_invisibilidade_01.webp',
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
            'icon_file_name' => 'maldicao_apatia_profunda_01.webp',
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
            'icon_file_name' => 'maldicao_coracao_de_geleia_01.webp',
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
            'icon_file_name' => 'maldicao_envelhecimento_subito_01.webp',
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
            'icon_file_name' => 'maldicao_loucura_de_verao_01.webp',
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
            'icon_file_name' => 'maldicao_mil_verrugas_01.webp',
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
            'icon_file_name' => 'maldicao_ruina_do_corpo_01.webp',
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
            'icon_file_name' => 'mais_la_do_que_aqui_01.webp',
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
            'icon_file_name' => 'metamorfose_animal_01.webp',
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
            'icon_file_name' => 'sonhos_profeticos_01.webp',
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
            'icon_file_name' => 'velocidade_do_pensamento_01.webp',
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
            'icon_file_name' => 'visao_feerica_01.webp',
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
            'icon_file_name' => 'duende_voo_01.webp',
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60]],
            ],
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'add', 'value' => 6],
            ],
        ]);

        Power::create([
            'id' => 17019,
            'name' => 'Ajudante Nato',
            'description' => 'Quando você passa em um teste para ajudar, o bônus fornecido aumenta em +1. Além disso, se você estiver flanqueando um inimigo, o bônus que seus aliados recebem em testes de ataque contra esse inimigo por flanquear aumenta em +1 (para um total de +3). <br><br>No APP, adicione manualmente o bônus de flanqueado no valor final do acerto. Tanto seu bônus quanto o valor normal de +2.',
            'source' => 'race_optional',
            'usability' => 'roleplay',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
        ]);

        Power::create([
            'id' => 17020,
            'name' => 'Amo',
            'description' => 'Escolha um personagem (jogador ou NPC). Você adotou esse personagem como seu amo. Quando usa a habilidade Desejos a pedido do seu amo, o custo da magia diminui em –2 PM (em vez de apenas –1). Contudo, sempre que seu amo estiver presente em uma situação de perigo (como um combate) ou sob efeito de uma condição, você sofre –2 em testes de perícias. Se o amo morrer, essa penalidade permanece até o fim da aventura (quando então você pode escolher um novo amo).',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17021],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17022],
            ],
        ]);

        Power::create([
            'id' => 17021,
            'name' => 'Amo (Desejos)',
            'description' => 'Quando usa a habilidade Desejos a pedido do seu amo, o custo da magia diminui em –2 PM (em vez de apenas –1). <br><br>No APP, ative o poder para que Desejos reduza –2 PM.',
            'source' => 'power_granted',
            'usability' => 'active',
            'icon_file_name' => null,
            'duration' => 'day',
            'effects' => [
                ['tag' => 'mod_enhancement_power_pm_cost', 'op' => 'add', 'value' => -1, 'power_id' => 16048],
            ],
        ]);

        Power::create([
            'id' => 17022,
            'name' => 'Amo (Em Perigo)',
            'description' => 'Sempre que seu amo estiver presente em uma situação de perigo (como um combate) ou sob efeito de uma condição, você sofre –2 em testes de perícias. Se o amo morrer, essa penalidade permanece até o fim da aventura (quando então você pode escolher um novo amo). <br><br>No APP, ative o poder enquanto a penalidade estiver valendo. <br><br> Ative o poder quando seu amo estiver em perigo.',
            'source' => 'power_granted',
            'usability' => 'active',
            'icon_file_name' => null,
            'duration' => 'scene',
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 17023,
            'name' => 'Arma Amada',
            'description' => 'Escolha uma arma. Com essa arma, você recebe +2 em rolagens de dano e +5 em testes de manobra para resistir a desarmar e quebrar. <br><br>No APP, marque o poder ao rolar o ataque com a arma escolhida, ou ao rolar Luta para resistir a desarmar ou quebrar.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1, 14]],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 19, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 17024,
            'name' => 'Arma Natural Aprimorada',
            'description' => 'Escolha uma de suas armas naturais fornecidas por raça. O dano dessa arma aumenta em um passo e sua margem de ameaça aumenta em +1. Você pode escolher este poder outras vezes para armas naturais diferentes. <br><br>No APP, marque o poder ao rolar o ataque com a arma natural escolhida.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_grip' => 'natural'],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25, 4, 26, 52, 58, 11, 31, 29, 32, 33, 36, 39, 3, 13, 43, 28, 30, 54, 37, 38, 50, 45, 48, 49]],
            ],
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 17025,
            'name' => 'Arma Natural Hábil',
            'description' => 'Escolha uma arma natural fornecida por raça com a qual você possa gastar 1 PM para fazer um ataque corpo a corpo extra quando faz a ação agredir. Você não precisa gastar pontos de mana para isso. Você continua só podendo fazer isso uma vez por rodada. <br><br>No APP, marque o poder ao rolar o ataque com a arma natural escolhida.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_grip' => 'natural'],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25, 4, 26, 52, 58, 11, 31, 29, 32, 33, 36, 39, 3, 13, 43, 28, 30, 54, 37, 38, 50, 45, 48, 49]],
            ],
            'effects' => [
                ['tag' => 'mod_natural_weapon_pm_cost', 'op' => 'add', 'value' => -1],
            ],
        ]);

        Power::create([
            'id' => 17026,
            'name' => 'Arsenal de Allihanna',
            'description' => 'Você aprende e pode lançar Armamento da Natureza. Caso aprenda novamente essa magia, seu custo diminui em –1 PM. Além disso, ao usar sua habilidade Armadura de Allihanna, você recebe um bônus na Defesa adicional de +1 por patamar (ou seja, Defesa +3 no patamar iniciante, +4 no veterano e assim por diante).',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [5]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17027],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17028],
            ],
        ]);

        Power::create([
            'id' => 17027,
            'name' => 'Arsenal de Allihanna',
            'description' => 'Você aprende e pode lançar Armamento da Natureza. Caso aprenda novamente essa magia, seu custo diminui em –1 PM.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => 1003],
            ],
        ]);

        Power::create([
            'id' => 17028,
            'name' => 'Arsenal de Allihanna (Defesa)',
            'description' => 'Ao usar sua habilidade Armadura de Allihanna, você recebe um bônus na Defesa adicional de +1 por patamar (ou seja, Defesa +3 no patamar iniciante, +4 no veterano e assim por diante). <br><br>No APP, o bônus só é aplicado enquanto Armadura de Allihanna estiver ativa.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['active_power_id' => 16007],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_def', 'op' => 'add_per_patamar', 'value' => 1],
            ],
        ]);

        //TODO add kallyanach id aqui depois, eles tem asas tbm.
        Power::create([
            'id' => 17029,
            'name' => 'Asas de Aço',
            'description' => 'Suas asas são sobrenaturalmente resistentes. Elas fornecem +2 na Defesa e podem ser usadas como armas naturais (dano 2d4, crítico x2, impacto). Uma vez por rodada, quando usa a ação agredir para atacar com outra arma, você pode gastar 1 PM para fazer um ataque corpo a corpo extra com as asas (exceto se as estiver usando para voar).',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [13, 43, 48, 49]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1007],
            ],
        ]);

        //TODO no effect for now, fix this once we add the Suraggel heritages (flight 12m, vulnerable while flying)
        Power::create([
            'id' => 17030,
            'name' => 'Asas Extraplanares',
            'description' => 'Você desenvolve um par de asas adequado à sua herança planar — normalmente, emplumadas se você for um aggelus ou coriáceas se você for um sulfure, embora isso possa mudar. Você pode gastar 1 PM por rodada para voar com deslocamento de 12m. Enquanto estiver voando dessa forma, você fica vulnerável.',
            'source' => 'race_optional',
            'usability' => 'active',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 49]],
            ],
        ]);

        //TODO fix this when we add ofícios (requires Ofício (alquimista), for now any trained Ofício)
        Power::create([
            'id' => 17031,
            'name' => 'Atração pela Pólvora',
            'description' => 'Entre seu povo, existem aqueles que amam a pólvora. Você é um deles! Você recebe +1 em testes de ataque e +2 em rolagens de dano com armas de fogo. Com bombas e itens similares baseados em pólvora, você causa +1 de dano por dado de dano.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['weapon_is_firearm' => true],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1, 12, 14, 19]],
                ['type' => 'skill_trained', 'skill_id' => 22],
            ],
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17032,
            'name' => 'Camuflagem Mimética',
            'description' => 'Você pode mudar sua cor e textura a ponto de ficar quase invisível. Você pode gastar uma ação de movimento e 2 PM para receber um bônus em Furtividade até o fim da cena. O bônus varia conforme o que você estiver vestindo: +10 se estiver sem armadura e com no máximo um item vestido, +5 se estiver de armadura leve e/ou com até dois itens vestidos, +2 se estiver de armadura pesada e/ou com mais de dois itens vestidos.',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [46, 52, 58]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17033],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17034],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17035],
            ],
        ]);

        Power::create([
            'id' => 17033,
            'name' => 'Camuflagem Mimética (+10)',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para receber +10 em Furtividade até o fim da cena, se estiver sem armadura e com no máximo um item vestido.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 17034,
            'name' => 'Camuflagem Mimética (+5)',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para receber +5 em Furtividade até o fim da cena, se estiver de armadura leve e/ou com até dois itens vestidos.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 17035,
            'name' => 'Camuflagem Mimética (+2)',
            'description' => 'Você pode gastar uma ação de movimento e 2 PM para receber +2 em Furtividade até o fim da cena, se estiver de armadura pesada e/ou com mais de dois itens vestidos.',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 2],
            ],
        ]);

        //TODO the range step-up (curto -> médio, médio -> longo) is not modeled, only the +2 CD
        Power::create([
            'id' => 17036,
            'name' => 'Canto da Sereia',
            'description' => 'Sua voz é melodiosa e encantadora, capaz de fascinar até as pessoas mais cruéis. Você recebe +2 em Atuação. Além disso, o alcance das magias adquiridas por sua Canção dos Mares aumenta em um passo (de curto para médio e de médio para longo) e a CD para resistir a elas aumenta em +2.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_granted_by_power_id' => 16056],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [46]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 4, 'value' => 2],
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17037,
            'name' => 'Cascos Poderosos',
            'description' => 'Quando faz um ataque desarmado, você pode gastar 1 PM para desferir esse ataque usando seus cascos. Se fizer isso, causa +1 dado de dano do mesmo tipo, mas não pode escolher causar dano não letal. <br><br>No APP, marque o poder ao rolar um ataque desarmado.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'applies_when' => ['weapon_ids' => [4]],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [3, 25, 45]],
            ],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => 'weapon_die', 'damage_type' => 'bludgeoning'],
            ],
        ]);

        //TODO Golem doesn't exist yet: add its race id to race_ids (empty for now, so nobody can pick this), and model +2 Diplomacia and no armor penalty from Chassi once the Chassi power exists
        Power::create([
            'id' => 17038,
            'name' => 'Chassi Gracioso',
            'description' => 'Seu corpo artificial foi feito com materiais leves e belos. Você recebe +2 em Diplomacia e não possui a penalidade de armadura da habilidade Chassi.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => []],
            ],
        ]);

        Power::create([
            'id' => 17039,
            'name' => 'Citadino',
            'description' => 'Você foi criado em uma metrópole, onde se acostumou a lidar com várias pessoas — mas também às facilidades da civilização. Você recebe +2 em testes de perícias baseadas em Carisma (exceto Adestramento).',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [15, 16, 19, 22]],
            ],
            'effects' => [
                ['tag' => 'skill_group', 'op' => 'add', 'attribute' => 'car', 'value' => 2, 'exclude_skill_ids' => [2]],
            ],
        ]);

        Power::create([
            'id' => 17040,
            'name' => 'Comandar Aprimorado',
            'description' => 'Quando usa Comandar, você pode gastar +2 PM para aumentar o bônus fornecido pelo poder em +1. <br><br>No APP, marque este aprimoramento ao lançar a magia Comandar.',
            'source' => 'race_optional',
            'usability' => 'spell_enhancement',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'applies_when' => ['spell_ids' => [3003]],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [15]],
                ['type' => 'attribute', 'attribute' => 'car', 'min' => 2],
                ['type' => 'power', 'power_id' => 11032],
            ],
            'effects' => [
                ['tag' => 'all_skills', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 17041,
            'name' => 'Conforto do Aço',
            'description' => 'Seu povo veste metal como se fosse algodão. Você não sofre penalidade de armadura por usar armaduras (mas ainda sofre por escudos). Além disso, se estiver usando armadura pesada, recebe +2 na Defesa. <br><br>No APP, ative o poder enquanto estiver usando armadura; o +2 na Defesa só vale com armadura pesada.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'waive_armor_penalty_for_armors', 'op' => 'grant'],
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17042,
            'name' => 'Constrição Atroz',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para expelir gavinhas espinhosas de seu corpo. No início de cada um de seus turnos, qualquer inimigo em alcance curto deve fazer um teste de Reflexos (CD Sab). Se falhar, fica enredado. Um inimigo enredado pode escapar gastando uma ação padrão e passando em um teste de Acrobacia ou Atletismo (CD Sab), ou afastando-se 9m de você. Este poder tem duração sustentada. <br><br>Os testes ficam por sua conta! Ative o poder para lembrar da característica dele ser sustentado.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [5]],
            ],
        ]);

        //TODO add the petrification immunity (block_condition) once the Petrificado condition exists
        Power::create([
            'id' => 17043,
            'name' => 'Coração de Pedra',
            'description' => 'Você recebe +1 PV por nível e imunidade a petrificação.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1, 21]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pv', 'op' => 'add_per_level', 'value' => 1, 'per_character_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 17044,
            'name' => 'Coro Sibilante',
            'description' => 'Você pode gastar 1 PM para fazer suas serpentes ecoarem suas palavras. Até o fim da cena, você recebe +2 em testes de perícias originalmente baseadas em Carisma (exceto testes de ataque).',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'scene',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [21]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 2, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 4, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 17, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17045,
            'name' => 'Crescimento Feérico',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para aumentar uma categoria de tamanho e receber +1 em Força (máximo Grande). A partir do patamar veterano, você também pode gastar 3 PM para aumentar duas categorias de tamanho; nesse caso, recebe +2 em Força (máximo Enorme). Seu equipamento muda com você. A transformação dura pelo tempo que você quiser, mas você reverte ao tamanho normal se ficar inconsciente ou morrer. Voltar ao seu tamanho original é uma ação livre.',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60, 47]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17046],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17047],
            ],
        ]);

        Power::create([
            'id' => 17046,
            'name' => 'Crescimento Feérico (Uma Categoria)',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para aumentar uma categoria de tamanho e receber +1 em Força (máximo Grande).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_current_size', 'op' => 'add', 'value' => 1, 'max_size' => 1, 'stack_group' => 'crescimento_feerico'],
                ['tag' => 'mod_str', 'op' => 'add', 'value' => 1, 'stack_group' => 'crescimento_feerico'],
            ],
        ]);

        Power::create([
            'id' => 17047,
            'name' => 'Crescimento Feérico (Duas Categorias)',
            'description' => 'A partir do patamar veterano, você pode gastar uma ação de movimento e 3 PM para aumentar duas categorias de tamanho; nesse caso, recebe +2 em Força (máximo Enorme).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_current_size', 'op' => 'add', 'value' => 2, 'max_size' => 2, 'stack_group' => 'crescimento_feerico'],
                ['tag' => 'mod_str', 'op' => 'add', 'value' => 2, 'stack_group' => 'crescimento_feerico'],
            ],
        ]);

        Power::create([
            'id' => 17048,
            'name' => 'Criança da Luz',
            'description' => 'O sangue extraplanar é mais forte em você. Seu bônus racial em Diplomacia e Intuição aumenta para +5 e você recebe redução de eletricidade e frio 5.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 3],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 3],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'electricity'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'cold'],
            ],
        ]);

        Power::create([
            'id' => 17049,
            'name' => 'Criança das Trevas',
            'description' => 'O sangue extraplanar é mais forte em você. Seu bônus racial em Enganação e Furtividade aumenta para +5 e você recebe redução de fogo e trevas 5.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [49]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 9, 'value' => 3],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 3],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'fire'],
                ['tag' => 'damage_reduction', 'op' => 'add', 'value' => 5, 'damage_reduction_type' => 'darkness'],
            ],
        ]);

        Power::create([
            'id' => 17050,
            'name' => 'Devoção Iluminada',
            'description' => 'Você recebe +2 em Vontade, perde a sensibilidade a luz causada por sua raça e não pode mais ser ofuscado.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [9, 41]],
                ['type' => 'god', 'god_ids' => [2, 4, 7, 8, 10, 18, 19, 21, 26, 27, 30, 34, 35, 36, 40, 55, 56, 62, 64, 66, 72, 79, 84]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 29, 'value' => 2],
                ['tag' => 'block_condition', 'op' => 'grant', 'condition_id' => 4],
            ],
        ]);

        Power::create([
            'id' => 17051,
            'name' => 'Duas Cabeças',
            'description' => 'Você nasceu com duas cabeças, ou então uma segunda cabeça brotou de seu corpo em determinado momento. Elas podem ser iguais ou não (por exemplo, uma pode ser menor ou atrofiada) e podem ter personalidades compatíveis ou não — fique à vontade para interpretar diálogos entre elas. Sempre que fizer um teste de Vontade, você pode gastar 1 PM para rolar dois dados e usar o melhor resultado. Além disso, se você tem uma arma natural de mordida, recebe uma arma natural extra do mesmo tipo e com as mesmas estatísticas (se você pode fazer um ataque extra com sua mordida original gastando 1 PM, agora poderá fazer um ataque com cada mordida gastando 2 PM). Se um efeito modificar uma de suas mordidas, escolha qual é afetada.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [11, 17, 20, 41, 52, 58]],
            ],
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'skill_id' => 29],
            ],
        ]);

        Power::create([
            'id' => 17052,
            'name' => 'Dupla Inteligência',
            'description' => 'Quando faz um teste de Inteligência ou de perícias baseadas nesse atributo, você pode gastar 2 PM para rolar dois dados e usar o melhor resultado.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [11, 17, 20, 41, 52, 58]],
                ['type' => 'attribute', 'attribute' => 'int', 'min' => 2],
                ['type' => 'power', 'power_id' => 17051],
            ],
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'skill', 'attribute' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 17053,
            'name' => 'Dupla Conjuração',
            'description' => 'Uma vez por rodada, quando lança uma magia usando uma ação padrão, você pode gastar 5 PM para lançar uma magia adicional (mas somente magias cuja execução seja ação de movimento ou padrão). Você precisa gesticular com duas mãos livres, em vez de uma, para isso. <br><br>No APP, use o poder para gastar os PM e depois lance a magia adicional normalmente.',
            'source' => 'race_optional',
            'usability' => 'active',
            'pm_cost' => 5,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [11, 17, 20, 41, 52, 58]],
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 2],
                ['type' => 'power', 'power_id' => 17052],
            ],
        ]);

        Power::create([
            'id' => 17054,
            'name' => 'Dupla Prontidão',
            'description' => 'Com campo de visão superior e órgãos sensoriais duplicados, você recebe +2 em Percepção, nunca fica desprevenido e não pode ser flanqueado.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [11, 17, 20, 41, 52, 58]],
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 2],
                ['type' => 'power', 'power_id' => 17051],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
                ['tag' => 'block_condition', 'op' => 'grant', 'condition_id' => 3],
                ['tag' => 'block_condition', 'op' => 'grant', 'condition_id' => 29],
            ],
        ]);

        // TODO maybe change this if a power lets heavy armor users add dexterity to their heavy armor def calculations.
        Power::create([
            'id' => 17055,
            'name' => 'Duro Como Aço',
            'description' => 'Se estiver usando armadura pesada, você pode somar sua Constituição na Defesa. Se fizer isso, não pode somar sua Destreza, mesmo que outras habilidades ou efeitos permitam isso. <br><br>No APP, ative o poder enquanto estiver usando armadura pesada.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
                ['type' => 'character_level', 'min' => 11],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 'con'],
            ],
        ]);

        //TODO add Kobold to the races once it exists
        Power::create([
            'id' => 17056,
            'name' => 'Entre as Pernas',
            'description' => 'Você pode ocupar o mesmo espaço de criaturas duas categorias de tamanho maiores que você (em vez de três). Enquanto estiver ocupando o mesmo espaço que uma criatura maior que você, ataques contra você têm 25% de chance de acertar a criatura maior (inclusive ataques feitos pela própria criatura!). <br><br>No APP, ative o poder enquanto estiver ocupando o mesmo espaço que uma criatura maior.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'scene',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12, 16]],
                ['type' => 'skill_trained', 'skill_id' => 1],
            ],
            'effects' => [
                ['tag' => 'dodge_chance', 'op' => 'add', 'value' => 25],
            ],
        ]);

        Power::create([
            'id' => 17057,
            'name' => 'Escapada Criativa',
            'description' => 'Quando faz um teste de resistência, você pode gastar 2 PM para somar sua Inteligência ao resultado do teste. <br><br>No APP, use o poder para gastar os PM e some a Inteligência ao resultado manualmente. <br><br>No APP, use o poder para gastar os PMs. Adicione manualmente sua INT à rolagem do atributo.',
            'source' => 'race_optional',
            'usability' => 'active',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12, 22]],
            ],
        ]);

        //TODO fix this when we add all items: +1 attack with swords, and every sword counts as an agile weapon (weapon ability 2)
        Power::create([
            'id' => 17058,
            'name' => 'Esgrima Élfica',
            'description' => 'Sua raça mescla arte e guerra como nenhuma outra. Você recebe +1 em testes de ataque com espadas e, para você, todas as espadas são consideradas armas ágeis.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [7]],
            ],
        ]);

        Power::create([
            'id' => 17059,
            'name' => 'Estilo Clássico',
            'description' => 'Enquanto estiver empunhando uma espada e um escudo, você recebe +2 nas rolagens de dano com sua arma e +2 na Defesa. <br><br>No APP, ative o poder enquanto estiver empunhando uma espada e um escudo. <br><br>No APP, ative o poder quando estiver usando uma espada e um escudo.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [15]],
            ],
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17060,
            'name' => 'Estirpe Arcana',
            'description' => 'Você recebe +2 pontos de mana por patamar e +2 em Misticismo.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [7, 6, 37]],
            ],
            'effects' => [
                ['tag' => 'mod_max_pm', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_max_pm', 'op' => 'add_per_patamar', 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17061,
            'name' => 'Exaltação do Rejeitado',
            'description' => 'Muitos acreditam que você não merece sua divindade, mas ela prova o contrário. Quando lança uma magia divina, você recebe um bônus em testes de resistência igual ao círculo da magia lançada, até o início do seu próximo turno. <br><br>No APP, adicione o bônus aos testes de resistência manualmente. <br><br>No APP, adicione manualmente o valor ao seu teste de resistência.',
            'source' => 'race_optional',
            'usability' => 'roleplay',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [17, 20]],
            ],
        ]);

        //TODO add real damage reduction if we implement damage intake
        Power::create([
            'id' => 17062,
            'name' => 'Explosão Óssea',
            'description' => 'Sempre que você sofre dano, pode gastar 2 PM para fazer alguns de seus ossos se soltarem, absorvendo o choque. Isso reduz o dano sofrido à Osteon metade, mas o deixa fraco (já que você perde partes de seu esqueleto). Essa condição é cumulativa — se usar este poder duas vezes, você fica debilitado e, se usá-lo três vezes, fica inconsciente. Essas condições duram até você recolher seus ossos (uma ação completa) ou até o fim do dia. <br><br>No APP, use o poder para gastar os PM e adicione as condições manualmente.',
            'source' => 'race_optional',
            'usability' => 'active',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [42]],
            ],
        ]);

        Power::create([
            'id' => 17063,
            'name' => 'Falatório Criativo (Adestramento)',
            'description' => 'Você pode usar Inteligência no lugar de Carisma para Adestramento.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 2, 'value' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 17064,
            'name' => 'Falatório Criativo (Atuação)',
            'description' => 'Você pode usar Inteligência no lugar de Carisma para Atuação.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 4, 'value' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 17065,
            'name' => 'Falatório Criativo (Diplomacia)',
            'description' => 'Você pode usar Inteligência no lugar de Carisma para Diplomacia.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 8, 'value' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 17066,
            'name' => 'Falatório Criativo (Enganação)',
            'description' => 'Você pode usar Inteligência no lugar de Carisma para Enganação.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 9, 'value' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 17067,
            'name' => 'Falatório Criativo (Intimidação)',
            'description' => 'Você pode usar Inteligência no lugar de Carisma para Intimidação.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 14, 'value' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 17068,
            'name' => 'Falatório Criativo (Jogatina)',
            'description' => 'Você pode usar Inteligência no lugar de Carisma para Jogatina.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'day',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 17, 'value' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 17069,
            'name' => 'Familiar de Água',
            'description' => 'Seu familiar se transforma em uma criatura elemental (por exemplo, se você é um qareen do fogo e seu familiar é um corvo, ele passa a ser um corvo feito de chamas). Seu familiar se torna imune a dano do seu elemento e, sempre que você lança uma magia que gere um efeito desse elemento, recebe +1 PM para gastar em aprimoramentos. Ele continua fornecendo seus benefícios originais.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_damage_types' => ['cold']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
                ['type' => 'power', 'power_id' => 16049],
                ['type' => 'power', 'power_ids_any' => [2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040]],
            ],
            'effects' => [
                ['tag' => 'spell_enhancement_free_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 17070,
            'name' => 'Familiar de Ar',
            'description' => 'Seu familiar se transforma em uma criatura elemental (por exemplo, se você é um qareen do fogo e seu familiar é um corvo, ele passa a ser um corvo feito de chamas). Seu familiar se torna imune a dano do seu elemento e, sempre que você lança uma magia que gere um efeito desse elemento, recebe +1 PM para gastar em aprimoramentos. Ele continua fornecendo seus benefícios originais.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_damage_types' => ['electricity']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
                ['type' => 'power', 'power_id' => 16050],
                ['type' => 'power', 'power_ids_any' => [2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040]],
            ],
            'effects' => [
                ['tag' => 'spell_enhancement_free_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 17071,
            'name' => 'Familiar de Fogo',
            'description' => 'Seu familiar se transforma em uma criatura elemental (por exemplo, se você é um qareen do fogo e seu familiar é um corvo, ele passa a ser um corvo feito de chamas). Seu familiar se torna imune a dano do seu elemento e, sempre que você lança uma magia que gere um efeito desse elemento, recebe +1 PM para gastar em aprimoramentos. Ele continua fornecendo seus benefícios originais.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_damage_types' => ['fire']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
                ['type' => 'power', 'power_id' => 16051],
                ['type' => 'power', 'power_ids_any' => [2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040]],
            ],
            'effects' => [
                ['tag' => 'spell_enhancement_free_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 17072,
            'name' => 'Familiar de Terra',
            'description' => 'Seu familiar se transforma em uma criatura elemental (por exemplo, se você é um qareen do fogo e seu familiar é um corvo, ele passa a ser um corvo feito de chamas). Seu familiar se torna imune a dano do seu elemento e, sempre que você lança uma magia que gere um efeito desse elemento, recebe +1 PM para gastar em aprimoramentos. Ele continua fornecendo seus benefícios originais.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_damage_types' => ['acid']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
                ['type' => 'power', 'power_id' => 16052],
                ['type' => 'power', 'power_ids_any' => [2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040]],
            ],
            'effects' => [
                ['tag' => 'spell_enhancement_free_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 17073,
            'name' => 'Familiar de Luz',
            'description' => 'Seu familiar se transforma em uma criatura celestial. Além dos benefícios normais, seu familiar se torna imune a dano de luz e, sempre que lança uma magia que gere um efeito de luz, você recebe +1 PM para gastar em aprimoramentos.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_damage_types' => ['light']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [48, 44]],
                ['type' => 'power', 'power_ids_any' => [16065, 16053]],
                ['type' => 'power', 'power_ids_any' => [2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040]],
            ],
            'effects' => [
                ['tag' => 'spell_enhancement_free_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 17074,
            'name' => 'Familiar de Trevas',
            'description' => 'Seu familiar se transforma em uma criatura abissal. Além dos benefícios normais, seu familiar se torna imune a dano de trevas e, sempre que lança uma magia que gere um efeito de trevas, você recebe +1 PM para gastar em aprimoramentos.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_damage_types' => ['darkness']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [49, 44]],
                ['type' => 'power', 'power_ids_any' => [16066, 16054]],
                ['type' => 'power', 'power_ids_any' => [2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040]],
            ],
            'effects' => [
                ['tag' => 'spell_enhancement_free_pm', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 17075,
            'name' => 'Faro Aprimorado',
            'description' => 'Seu olfato é ainda mais apurado que o padrão de sua raça. Você recebe +2 em Intuição, Investigação e Percepção, +5 em testes de Sobrevivência para rastrear e percebe automaticamente a presença de criaturas em alcance curto (mas não sua localização). De acordo com o mestre, criaturas sem cheiro (como alguns construtos ou seres incorpóreos) podem ser indetectáveis ao seu faro.',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25, 33]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17076],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17077],
            ],
        ]);

        Power::create([
            'id' => 17076,
            'name' => 'Faro Aprimorado (Perícias)',
            'description' => 'Seu olfato é ainda mais apurado que o padrão de sua raça. Você recebe +2 em Intuição, Investigação e Percepção.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 16, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 23, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17077,
            'name' => 'Faro Aprimorado (Rastrear)',
            'description' => 'Você recebe +5 em testes de Sobrevivência para rastrear.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 28, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 17078,
            'name' => 'Fragrância de Rosas',
            'description' => 'Você cheira bem. Você recebe +2 em Diplomacia e pode gastar uma ação padrão e 2 PM para forçar todas as criaturas em alcance curto a fazerem um teste de Fortitude (CD Car). Uma criatura que falhe fica pasma por 1 rodada (apenas uma vez por cena) como um efeito metabólico.',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [5, 60]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17079],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17080],
            ],
        ]);

        Power::create([
            'id' => 17079,
            'name' => 'Fragrância de Rosas (Diplomacia)',
            'description' => 'Você cheira bem. Você recebe +2 em Diplomacia.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 8, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17080,
            'name' => 'Fragrância de Rosas (Fortitude)',
            'description' => 'Você pode gastar uma ação padrão e 2 PM para forçar todas as criaturas em alcance curto a fazerem um teste de Fortitude (CD Car). Uma criatura que falhe fica pasma por 1 rodada (apenas uma vez por cena) como um efeito metabólico.',
            'source' => 'power_granted',
            'usability' => 'active',
            'action_cost' => 'standard',
            'pm_cost' => 2,
            'icon_file_name' => null,
        ]);

        //TODO add [Furia ou Fúria Divina] as _any pre-reqs when these powers are added. Pré-requisito: Fúria ou Fúria Divina.
        Power::create([
            'id' => 17081,
            'name' => 'Fúria do Aterrorizante',
            'description' => 'Quando você entra em fúria, inimigos em alcance curto ficam abalados até o fim da cena (Von CD Con reduz para 1 rodada).',
            'source' => 'race_optional',
            'usability' => 'roleplay',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [10, 40]],
            ],
        ]);

        //TODO add the prerequisite "Fúria" (power_id) and model the effect (natural weapon step +1 and mod_margin -1 while raging) once Fúria exists. The race list is the same as Arma Natural Aprimorada (every race with a race-granted natural weapon)
        Power::create([
            'id' => 17082,
            'name' => 'Fúria Natural',
            'description' => 'Quando você é dominado por seus instintos selvagens, prefere usar suas armas naturais àquelas criadas pela civilização. Quando você está em fúria, o dano de suas armas naturais aumenta em um passo e a margem de ameaça delas aumenta em +1.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25, 4, 26, 52, 58, 11, 31, 29, 32, 33, 36, 39, 3, 13, 43, 28, 30, 54, 37, 38, 50, 45, 48, 49]],
            ],
        ]);

        Power::create([
            'id' => 17083,
            'name' => 'Gavinhas',
            'description' => 'Quando você usa Armadura de Allihanna, duas gavinhas crescem de suas costas. Cada gavinha é uma arma natural (dano 1d4 cada, crítico x2, impacto) com 3m de alcance e versátil, fornecendo +2 em testes para desarmar e derrubar. Uma vez por rodada, quando usa a ação agredir para atacar com uma arma, você pode gastar 1 PM por gavinha para fazer um ataque corpo a corpo extra com ela (desde que ela já não tenha sido usada na rodada).',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['active_power_id' => 16007],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [5]],
                ['type' => 'character_level', 'min' => 5],
            ],
            'effects' => [
                ['tag' => 'grants_natural_weapon', 'op' => 'grant', 'weapon_id' => 1008],
            ],
        ]);

        Power::create([
            'id' => 17084,
            'name' => 'Ginete de Javali',
            'description' => 'Você recebe um javali doherita iniciante, com o qual possui +2 em Adestramento e Cavalgar. Se perder seu javali, você pode obter outro com uma semana de busca e T$ 100.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [1]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 2, 'value' => 2],
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 5, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17085,
            'name' => 'Glamour',
            'description' => 'O mais icônico dos poderes feéricos, glamour é uma ilusão tão poderosa que gera efeitos reais. Escolha três magias de 1º círculo, arcanas ou divinas. Você pode lançar essas magias, com as seguintes mudanças: Elas contam como magias de ilusão além de seus tipos normais (por exemplo, um glamour de Explosão de Chamas conta como uma magia de evocação e de ilusão). Sempre que você lança uma magia de glamour que não pede testes de resistência, precisa rolar 1d6. Se rolar 1, a magia não funciona (mas você gasta os PM mesmo assim) e você não pode mais lançar magias de glamour até o fim da cena. Sempre que você lança uma magia de glamour que permite testes de resistência de Fortitude ou Reflexos, os alvos podem substituir os testes originais por Vontade. Além disso, se um alvo passar no teste de resistência, você não pode mais lançar magias de glamour até o fim da cena. Se você aprender uma magia de glamour novamente, pode lançá-la como uma magia normal ou de glamour — nesse caso, ela sofre as mudanças acima, mas seu custo diminui em –1 PM.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60, 6, 47]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'limit_spell_choices', 'op' => 'set', 'spell_circle' => 1],
                ['tag' => 'add_spell_school', 'op' => 'add', 'value' => 'ilusao'],
            ],
        ]);

        Power::create([
            'id' => 17086,
            'name' => 'Glamour Maior',
            'description' => 'Você aprende mais três magias, de 1º ou 2º círculo, arcanas ou divinas, que pode lançar como magias de glamour.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [60, 6, 47]],
                ['type' => 'power', 'power_id' => 17085],
                ['type' => 'character_level', 'min' => 5],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'limit_spell_choices', 'op' => 'set', 'max_circle' => 2],
                ['tag' => 'add_spell_school', 'op' => 'add', 'value' => 'ilusao'],
            ],
        ]);

        Power::create([
            'id' => 17087,
            'name' => 'Golpe dos Titãs',
            'description' => 'Quando acerta um ataque corpo a corpo ou de arremesso, você pode gastar 1 PM. Se fizer isso, sempre que rolar o resultado máximo, ou um menor do que o máximo, em um dado de dano da arma, role um dado extra, até um limite de dados extras igual à sua Força.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['melee', 'thrown']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [10]],
                ['type' => 'power', 'power_id' => 16208],
            ],
            'effects' => [
                ['tag' => 'extra_die_on_max', 'op' => 'grant', 'value' => 'str', 'margin' => 1],
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 16208],
            ],
        ]);

        //TODO add Kobold to the races once it exists
        Power::create([
            'id' => 17088,
            'name' => 'Golpe no Joelho',
            'description' => 'Se você fizer um ataque corpo a corpo contra uma criatura maior que você enquanto ocupa o mesmo espaço que ela, o dano desse ataque aumenta em um passo e você recebe +2 na margem de ameaça.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_purpose' => ['melee'], 'active_power_id' => 17056],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [12, 16]],
                ['type' => 'power', 'power_id' => 17056],
            ],
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 17089,
            'name' => 'Grande Marca de Wynna',
            'description' => 'Sua tatuagem mística é especialmente grande e chamativa, e contém ainda mais poder mágico que o normal. Você pode lançar uma magia de 1º círculo a sua escolha (atributo-chave Carisma), além daquela fornecida pela habilidade Tatuagem Mística e pode usar os aprimoramentos de ambas como se tivesse acesso aos mesmos círculos de magia que um feiticeiro do seu nível.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [44]],
            ],
            'effects' => [
                ['tag' => 'grant_or_reduce_spell_pm_cost_by_1', 'op' => 'grant', 'spell_id' => null],
                ['tag' => 'limit_spell_choices', 'op' => 'set', 'spell_circle' => 1],
                ['tag' => 'power_granted_spell_key_attribute', 'op' => 'set', 'value' => 'car'],
                ['tag' => 'spell_circle_as_class', 'op' => 'set', 'spell_id' => null, 'class_id' => 3],
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 16055],
                ['tag' => 'spell_circle_as_class', 'op' => 'set', 'spell_id' => null, 'class_id' => 3],
            ],
        ]);

        //TODO add kallyanach id to the races of every Herança Erudita once it exists
        Power::create([
            'id' => 17090,
            'name' => 'Herança Erudita (Conhecimento)',
            'description' => 'Você soma seu Carisma em Conhecimento.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6, 7]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 6, 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17091,
            'name' => 'Herança Erudita (Guerra)',
            'description' => 'Você soma seu Carisma em Guerra.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6, 7]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 12, 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17092,
            'name' => 'Herança Erudita (Investigação)',
            'description' => 'Você soma seu Carisma em Investigação.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6, 7]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 15, 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17093,
            'name' => 'Herança Erudita (Misticismo)',
            'description' => 'Você soma seu Carisma em Misticismo.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6, 7]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 20, 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17094,
            'name' => 'Herança Erudita (Nobreza)',
            'description' => 'Você soma seu Carisma em Nobreza.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6, 7]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 21, 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17095,
            'name' => 'Herança Erudita (Ofício)',
            'description' => 'Você soma seu Carisma em Ofício.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6, 7]],
            ],
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 22, 'value' => 'car'],
            ],
        ]);

        Power::create([
            'id' => 17096,
            'name' => 'Lógica Gnômica',
            'description' => 'Uma vez por cena, quando faz um teste de perícia (exceto testes de ataque), você pode gastar 3 PM para substituir o atributo-chave dessa perícia por Inteligência. Por exemplo, ao fazer um teste de Atletismo você pode gastar 3 PM para somar sua Inteligência em vez de sua Força.',
            'source' => 'race_optional',
            'usability' => 'roll_active',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [19]],
            ],
            'effects' => [
                ['tag' => 'skill_attribute', 'op' => 'override', 'skill_id' => 'all_skills_no_combat', 'value' => 'int'],
            ],
        ]);

        Power::create([
            'id' => 17097,
            'name' => 'Magia Ofídica',
            'description' => 'Para cada círculo de magia que você é capaz de lançar, a CD para resistir a seus efeitos de veneno aumenta em +1 e esses venenos causam +1 ponto de perda de vida por dado.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['spell_damage_types' => ['poison']],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [21, 35, 37, 38]],
            ],
            'effects' => [
                ['tag' => 'mod_cd', 'op' => 'add', 'value' => 1, 'per_available_spell_circle' => 1],
                ['tag' => 'mod_spell_dmg_per_die', 'op' => 'add', 'value' => 1, 'per_available_spell_circle' => 1],
            ],
        ]);

        Power::create([
            'id' => 17098,
            'name' => 'Manipulação Esquelética',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para estender seus braços e aumentar seu alcance natural em +1,5m, para estender suas pernas e aumentar seu deslocamento terrestre em +3m ou para se transformar numa pilha de ossos que fornece +5 em Furtividade (nessa forma você só pode fazer reações ou cancelar esse efeito). A manipulação dura até o fim da cena, até você usar outra manipulação ou até você cancelar o efeito (uma ação livre).',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [42]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17099],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17100],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17101],
            ],
        ]);

        Power::create([
            'id' => 17099,
            'name' => 'Manipulação Esquelética (Braços)',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para estender seus braços e aumentar seu alcance natural em +1,5m até o fim da cena, até usar outra manipulação ou até cancelar o efeito (uma ação livre).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 17100,
            'name' => 'Manipulação Esquelética (Pernas)',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para estender suas pernas e aumentar seu deslocamento terrestre em +3m até o fim da cena, até usar outra manipulação ou até cancelar o efeito (uma ação livre).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 17101,
            'name' => 'Manipulação Esquelética (Pilha de Ossos)',
            'description' => 'Você pode gastar uma ação de movimento e 1 PM para se transformar numa pilha de ossos que fornece +5 em Furtividade (nessa forma você só pode fazer reações ou cancelar esse efeito) até o fim da cena, até usar outra manipulação ou até cancelar o efeito (uma ação livre).',
            'source' => 'power_granted',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 1,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 11, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 17102,
            'name' => 'Marrada Poderosa',
            'description' => 'Quando você faz uma investida com seus chifres e acerta o ataque, o dano deles aumenta em um passo e você pode gastar 1 PM para fazer um ataque corpo a corpo desarmado ou com outra arma que esteja empunhando. Esse ataque também recebe o bônus de +2 por investida.',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25, 45]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17103],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17104],
            ],
        ]);

        Power::create([
            'id' => 17103,
            'name' => 'Marrada Poderosa (Dano)',
            'description' => 'Quando você faz uma investida com seus chifres e acerta o ataque, o dano deles aumenta em um passo.',
            'source' => 'power_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'applies_when' => ['weapon_ids' => [1000, 1006]],
            'effects' => [
                ['tag' => 'weapon_step_increase', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 17104,
            'name' => 'Marrada Poderosa (Ataque Extra)',
            'description' => 'Quando você faz uma investida com seus chifres e acerta o ataque, você pode gastar 1 PM para fazer um ataque corpo a corpo desarmado ou com outra arma que esteja empunhando. Esse ataque também recebe o bônus de +2 por investida.<br><br>No APP, use o poder para gastar o PM.',
            'source' => 'power_granted',
            'usability' => 'active',
            'pm_cost' => 1,
            'icon_file_name' => null,
        ]);

        Power::create([
            'id' => 17105,
            'name' => 'Meditação Mística',
            'description' => 'Uma vez por dia, você pode entrar em um transe místico por 1d4 minutos. Enquanto medita, você fica fascinado. Você pode interromper sua meditação a qualquer momento, mas, se concluí-la, recupera um número de pontos de mana igual ao seu nível.',
            'source' => 'race_optional',
            'usability' => 'active',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [6, 7]],
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
                ['type' => 'skill_trained', 'skill_id' => 29],
            ],
            'effects' => [
                ['tag' => 'restore_pm', 'op' => 'add_per_level', 'value' => 1, 'per_character_level' => 1],
            ],
        ]);

        Power::create([
            'id' => 17106,
            'name' => 'Olhar Petrificante',
            'description' => 'Você pode gastar uma ação padrão e 3 PM para forçar uma criatura em alcance curto a fazer um teste de Fortitude (CD Car). Se a criatura falhar, fica lenta por 1d4 rodadas. Se já estiver lenta por este efeito, em vez disso é petrificada permanentemente — ela e seu equipamento se transformam em uma estátua inerte e sem consciência, com RD 8 e os mesmos PV que ela tinha em vida. Se a estátua for quebrada, a criatura morrerá.',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'standard',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [21]],
                ['type' => 'character_level', 'min' => 9],
            ],
        ]);

        Power::create([
            'id' => 17107,
            'name' => 'Ossos Afiados',
            'description' => 'Você recebe +2 em Intimidação e +2 em rolagens de dano com armas naturais e ataques desarmados.',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [42]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17108],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17109],
            ],
        ]);

        Power::create([
            'id' => 17108,
            'name' => 'Ossos Afiados (Intimidação)',
            'description' => 'Você recebe +2 em Intimidação.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 14, 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 17109,
            'name' => 'Ossos Afiados (Dano)',
            'description' => 'Você recebe +2 em rolagens de dano com armas naturais e ataques desarmados.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['weapon_any' => [
                ['grip' => 'natural'],
                ['weapon_id' => 4],
            ]],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
            ],
        ]);

        //TODO fix this when we add all items: add azagaias and lanças to the weapon ids, and every affected weapon counts as an agile weapon (weapon ability 2)
        Power::create([
            'id' => 17110,
            'name' => 'Pirata Oceânico',
            'description' => 'Sua habilidade Mestre do Tridente passa a afetar também arpões. Além disso, você recebe +2 em testes de ataque com todas as armas afetadas por essa habilidade e as considera armas ágeis.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['weapon_ids' => [12, 13, 14]],
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [46]],
            ],
            'effects' => [
                ['tag' => 'waive_weapon_proficiency', 'op' => 'grant', 'weapon_ids' => [12, 13, 14]],
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 2],
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
                ['tag' => 'replaces_power', 'op' => 'grant', 'power_id' => 16057],
            ],
        ]);

        //TODO Golem doesn't exist yet: add its race id to race_ids (empty for now, so nobody can pick this)
        Power::create([
            'id' => 17111,
            'name' => 'Programação de Combate',
            'description' => 'Você pode gastar uma ação de movimento e 3 PM para ativar um modo de análise de inimigos, com duração sustentada. Enquanto estiver com esse modo ativo, quando faz um ataque você rola dois dados e usa o melhor resultado.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'pm_cost' => 3,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => []],
            ],
            'effects' => [
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'hit'],
            ],
        ]);

        //TODO Golem doesn't exist yet: add its race id to race_ids (empty for now, so nobody can pick this)
        Power::create([
            'id' => 17112,
            'name' => 'Programação Holística',
            'description' => 'Você pode gastar uma ação completa e 2 PM para se tornar treinado em uma perícia a sua escolha até o fim do dia. Sempre que usa este poder, role 1d4. Em um resultado 1, você sobrecarrega seu cérebro artificial — até o fim do dia, você fica frustrado e não pode mais usar este poder. <br><br>No APP, adicione o bônus de treinada manualmente!',
            'source' => 'race_optional',
            'usability' => 'active',
            'action_cost' => 'complete',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => []],
            ],
        ]);

        Power::create([
            'id' => 17113,
            'name' => 'Protetor Táurico',
            'description' => 'Você pode gastar uma ação de movimento para carregar consigo (no colo, sobre os ombros…) um aliado adjacente Médio ou menor. Você precisa de uma mão livre, que fica ocupada enquanto você estiver carregando o aliado. Enquanto está sendo carregado, o aliado é considerado sob cobertura leve (Defesa +5). Além disso, qualquer ataque contra o aliado tem 50% de chance de ter você, e não o aliado, como alvo. <br><br>Decida em quem o ataque acertou manualmente. Seu aliado deve adicionar os 5 de defesa manualmente.',
            'source' => 'race_optional',
            'usability' => 'active',
            'duration' => 'scene',
            'action_cost' => 'movement',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25]],
            ],
        ]);

        Power::create([
            'id' => 17114,
            'name' => 'Protetor Eterno',
            'description' => 'Enquanto tiver pelo menos um aliado adjacente, você continua consciente mesmo se estiver com 0 ou menos pontos de vida. Você ainda morre caso seus PV cheguem no limite negativo, como normal.',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [25]],
                ['type' => 'power', 'power_id' => 17113],
            ],
        ]);

        Power::create([
            'id' => 17115,
            'name' => 'Quatro Braços',
            'description' => 'Você possui um par de braços extras. Isso permite que você empunhe até quatro objetos, mas não fornece ações extras — por exemplo, você continua fazendo apenas um ataque com a ação agredir (mas veja Quadridestria).',
            'source' => 'race_optional',
            'usability' => 'passive',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [17, 20, 37, 38, 41, 52]],
            ],
            'effects' => [
                ['tag' => 'enable_hand', 'op' => 'grant', 'value' => 3],
                ['tag' => 'enable_hand', 'op' => 'grant', 'value' => 4],
            ],
        ]);

        Power::create([
            'id' => 17116,
            'name' => 'Quadridestria',
            'description' => 'Você consegue agir com todos os seus braços ao mesmo tempo. Uma vez por rodada, quando usa o poder Estilo de Duas Armas, você pode gastar 2 PM para fazer um ataque com cada arma que estiver empunhando, desde que todas, com exceção de uma, sejam leves. Assim, se estiver empunhando três armas (e pelo menos duas forem leves), pode fazer três ataques; se estiver empunhando quatro armas (e pelo menos três forem leves), pode fazer quatro ataques. Poderes que modificam Estilo de Duas Armas também se aplicam aos seus braços extras. Por exemplo, Arma Secundária Grande permite que você use armas de uma mão em todos os seus braços e Ambidestria elimina a penalidade de todos os ataques. <br><br>No APP, ative o poder para gastar os PMs. Os ataques são por sua conta!',
            'source' => 'race_optional',
            'usability' => 'active',
            'pm_cost' => 2,
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [17, 20, 37, 38, 41, 52]],
                ['type' => 'power', 'power_id' => 259],
                ['type' => 'power', 'power_id' => 17115],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        Power::create([
            'id' => 17117,
            'name' => 'Saliva Corrosiva',
            'description' => 'Sua mordida causa +1d6 pontos de dano de ácido. Além disso, você pode gastar uma ação de movimento para cobrir de saliva uma arma que esteja usando. A arma causa +1d6 pontos de dano de ácido. O ácido dura até você acertar um ataque ou até o fim da cena (o que acontecer primeiro).',
            'source' => 'race_optional',
            'usability' => 'vessel',
            'icon_file_name' => null,
            'prerequisites' => [
                ['type' => 'race', 'race_ids' => [52]],
            ],
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17118],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 17119],
            ],
        ]);

        Power::create([
            'id' => 17118,
            'name' => 'Saliva Corrosiva (Mordida)',
            'description' => 'Sua mordida causa +1d6 pontos de dano de ácido.',
            'source' => 'power_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'applies_when' => ['weapon_ids' => [1001]],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'acid'],
            ],
        ]);

        Power::create([
            'id' => 17119,
            'name' => 'Saliva Corrosiva (Arma)',
            'description' => 'Você pode gastar uma ação de movimento para cobrir de saliva uma arma que esteja usando. A arma causa +1d6 pontos de dano de ácido. O ácido dura até você acertar um ataque ou até o fim da cena (o que acontecer primeiro).',
            'source' => 'power_granted',
            'usability' => 'item_enhancer',
            'action_cost' => 'movement',
            'icon_file_name' => null,
            'applies_when' => ['categories' => ['weapon']],
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'extra_die', 'value' => '1d6', 'damage_type' => 'acid'],
                ['tag' => 'remaining_uses', 'op' => 'set', 'value' => 1],
            ],
        ]);
    }
}
