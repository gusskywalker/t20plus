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

        // Roleplay: the app has no help/flanking system, the extra bonus is
        // added by hand on top of the final number.
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

        // Vessel: the amo is chosen by hand (roleplay). One child shifts
        // Desejos' PM discount, the other self-reports the per-roll penalty.
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

        // The chosen weapon is self-reported: check the power on the attack
        // (+2 damage) or on a Luta roll for a maneuver (+5).
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

        // Race list = every race whose race_granted power has a
        // grants_natural_weapon effect (Chifres, Mordida, Cascos, Pés
        // Rapinantes, Garras, Cauda, Linguarudo, Marrada). The chosen natural
        // weapon is self-reported: check the power when attacking with it.
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

        // Same race list as Arma Natural Aprimorada. The chosen natural weapon
        // is self-reported: check the power when attacking with it.
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

        // Vessel: the Defesa child only counts while Armadura de Allihanna
        // (16007) is active — applies_when.active_power_id. +1 base plus +1
        // per patamar on top of Armadura's own +2 (+3 iniciante, +4 veterano...).
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

        // Vessel: one child per Furtividade bonus tier, the player activates
        // whichever matches what they are wearing.
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
        // applies_when only gates the mod_cd (spells granted by Canção dos Mares, 16056),
        // the Atuação bonus is a plain skill effect and always counts.
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

        // Only shows on the Desarmado weapon (id 4). The "cannot choose non-lethal" clause is not modeled.
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

        // +2 on every Carisma skill except Adestramento (2).
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

        // An enhancement checkbox in the cast modal, only on the spell Comandar
        // (3003, granted by general power 11032): +2 PM for +1 more on the buff.
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
    }
}
