<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\Armor;
use App\Models\GeneralItem;
use App\Models\Power;
use App\Models\Weapon;
use Illuminate\Database\Seeder;

//TODO delete this seeder and its line in DatabaseSeeder once the item power rows are verified in the app
class ItemPowerTestSeeder extends Seeder
{

    public function run(): void
    {
        Power::create([
            'id' => 14900,
            'name' => 'TESTE Defesa +1',
            'description' => 'Fornece +1 na Defesa.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14901,
            'name' => 'TESTE Deslocamento +3',
            'description' => 'Fornece +3m de deslocamento.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_movement', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 14902,
            'name' => 'TESTE Acerto +2',
            'description' => 'Fornece +2 nos testes de ataque.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 2],
            ],
        ]);

        Power::create([
            'id' => 14903,
            'name' => 'TESTE Dano +3',
            'description' => 'Fornece +3 nas rolagens de dano.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 14904,
            'name' => 'TESTE Acrobacia +5',
            'description' => 'Fornece +5 em testes de Acrobacia.',
            'source' => 'item_granted',
            'usability' => 'roll_active',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'skill', 'op' => 'add', 'skill_id' => 1, 'value' => 5],
            ],
        ]);

        Power::create([
            'id' => 14905,
            'name' => 'TESTE Defesa +3 (Ativável)',
            'description' => 'Enquanto ativo, fornece +3 na Defesa.',
            'source' => 'item_granted',
            'usability' => 'active',
            'icon_file_name' => null,
            'action_cost' => 'movement',
            'duration' => 'scene',
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 3],
            ],
        ]);

        Power::create([
            'id' => 14907,
            'name' => 'TESTE Acerto +1 (Compartilhado)',
            'description' => 'Fornece +1 nos testes de ataque.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 1],
            ],
        ]);

        Power::create([
            'id' => 14908,
            'name' => 'TESTE Defesa +10 (Consumível)',
            'description' => 'Fornece +10 na Defesa.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_def', 'op' => 'add', 'value' => 10],
            ],
        ]);

        Power::create([
            'id' => 14910,
            'name' => 'TESTE Acerto +5 (Munição)',
            'description' => 'Fornece +5 nos testes de ataque.',
            'source' => 'item_granted',
            'usability' => 'passive',
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => 5],
            ],
        ]);

        Weapon::create([
            'id' => 900,
            'name' => 'TESTE Adaga A',
            'description' => 'Concede TESTE Acerto +2, TESTE Defesa +1 e TESTE Acerto +1 (Compartilhado).',
            'cost' => 0,
            'purpose' => 'melee',
            'grip' => 'light',
            'base_dmg' => '1d4',
            'base_margin' => 19,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'slots' => 1,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14902],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14900],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14907],
            ],
        ]);

        Weapon::create([
            'id' => 901,
            'name' => 'TESTE Adaga B',
            'description' => 'Concede TESTE Dano +3 e TESTE Acerto +1 (Compartilhado).',
            'cost' => 0,
            'purpose' => 'melee',
            'grip' => 'light',
            'base_dmg' => '1d4',
            'base_margin' => 19,
            'base_reach' => 0,
            'damage_type' => 'piercing',
            'slots' => 1,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14903],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14907],
            ],
        ]);

        Armor::create([
            'id' => 900,
            'name' => 'TESTE Armadura',
            'description' => 'Concede TESTE Defesa +1 e TESTE Deslocamento +3.',
            'type' => 'light',
            'mod_def' => 0,
            'armor_penalty' => 0,
            'cost' => 0,
            'slots' => 1,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14900],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14901],
            ],
        ]);

        Accessory::create([
            'id' => 900,
            'name' => 'TESTE Amuleto',
            'description' => 'Concede TESTE Acrobacia +5 e TESTE Defesa +3 (Ativável).',
            'cost' => 0,
            'slots' => 1,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14904],
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14905],
            ],
            'mp_cost' => 0,
            'icon_file_name' => null,
        ]);

        GeneralItem::create([
            'id' => 900,
            'name' => 'TESTE Poção (consumível)',
            'description' => 'Não deve conceder TESTE Defesa +10 (Consumível) apenas por ser possuída.',
            'type' => 'potion',
            'cost' => 0,
            'slots' => 1,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14908],
            ],
            'consumable' => true,
        ]);

        GeneralItem::create([
            'id' => 901,
            'name' => 'TESTE Flecha',
            'description' => 'Munição. Concede TESTE Acerto +5 (Munição) só quando disparada, nunca ao ser possuída.',
            'type' => 'ammo',
            'cost' => 0,
            'slots' => 1,
            'icon_file_name' => null,
            'effects' => [
                ['tag' => 'power', 'op' => 'grant', 'power_id' => 14910],
            ],
            'consumable' => false,
        ]);
    }
}
