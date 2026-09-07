<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class ClassSharedPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'id' is hardcoded on every row in this and every other seeder so
        // other seeders/files can reference it directly instead of looking
        // it up.

        Power::create([
            'id' => 45,
            'name' => 'Ímpeto',
            'description' => 'Você pode gastar 1 PM para aumentar seu deslocamento em +6m por uma rodada.',
            'source' => 'class',
            'usability' => 'active',
            'icon_file_name' => 'impeto_01.webp',
            'pm_cost' => 1,
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
            ],
        ]);

        // One power per attribute PER PATAMAR (ids 46-69, 4 tiers x 6
        // attributes) instead of one repeatable "Aumentar Atributo" power —
        // "apenas uma vez por patamar para um mesmo atributo" is encoded
        // directly as data via chained prerequisites (each tier requires
        // having the previous tier's power id, plus the patamar's min
        // character level) rather than as bespoke "count how many times
        // this was picked" validation code somewhere else. Whatever already
        // resolves prerequisites generically (power/character_level) is
        // then the only logic needed — the cap enforces itself, since tier
        // N simply isn't choosable without tier N-1, and tier N-1 is a
        // fact you either have or don't. "type": "character_level" is
        // {min: total character level}, NOT tied to a specific class the
        // way "type": "class"'s min_level is — see create_powers_table.php.
        // Unrolled from a nested $attributeLabels/$patamares foreach loop
        // 2026-09-08, same reasoning as every other loop in this file —
        // class_ids stays [1, 2] on every one of these 24; append the new
        // class's id here too whenever a new class gets seeded, since
        // Aumentar Atributo is a Poder de Classe every class gets (not a
        // Poder Geral). mod_base_X (not mod_X) — a permanent base-score
        // increase, not a live buff resolveTag/calculateStatBonus should
        // sum on top of base_*. Character creation bakes this straight
        // into CharacterDraft's finalBaseStr/etc (see character-draft.ts);
        // the character-sheet/level-up side (PATCHing an existing
        // character's base_str) is separate follow-up work, not built yet.

        Power::create([
            'id' => 46,
            'name' => 'Aumentar Atributo (Força)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Força em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
            ],
        ]);

        // Iniciante

        Power::create([
            'id' => 47,
            'name' => 'Aumentar Atributo (Força)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Força em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 46],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        // Veterano

        Power::create([
            'id' => 48,
            'name' => 'Aumentar Atributo (Força)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Força em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 47],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        // Campeão

        Power::create([
            'id' => 49,
            'name' => 'Aumentar Atributo (Força)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Força em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_forca_01.webp',
            'effects' => [
                ['tag' => 'mod_base_str', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 48],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        // Lenda

        Power::create([
            'id' => 50,
            'name' => 'Aumentar Atributo (Destreza)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Destreza em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_destreza_01.webp',
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
            ],
        ]);

        // Iniciante

        Power::create([
            'id' => 51,
            'name' => 'Aumentar Atributo (Destreza)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Destreza em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_destreza_01.webp',
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 50],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        // Veterano

        Power::create([
            'id' => 52,
            'name' => 'Aumentar Atributo (Destreza)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Destreza em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_destreza_01.webp',
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 51],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        // Campeão

        Power::create([
            'id' => 53,
            'name' => 'Aumentar Atributo (Destreza)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Destreza em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_destreza_01.webp',
            'effects' => [
                ['tag' => 'mod_base_dex', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 52],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        // Lenda

        Power::create([
            'id' => 54,
            'name' => 'Aumentar Atributo (Constituição)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Constituição em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_con_01.webp',
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
            ],
        ]);

        // Iniciante

        Power::create([
            'id' => 55,
            'name' => 'Aumentar Atributo (Constituição)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Constituição em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_con_01.webp',
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 54],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        // Veterano

        Power::create([
            'id' => 56,
            'name' => 'Aumentar Atributo (Constituição)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Constituição em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_con_01.webp',
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 55],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        // Campeão

        Power::create([
            'id' => 57,
            'name' => 'Aumentar Atributo (Constituição)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Constituição em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_con_01.webp',
            'effects' => [
                ['tag' => 'mod_base_con', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 56],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        // Lenda

        Power::create([
            'id' => 58,
            'name' => 'Aumentar Atributo (Inteligência)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Inteligência em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_int_01.webp',
            'effects' => [
                ['tag' => 'mod_base_int', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
            ],
        ]);

        // Iniciante

        Power::create([
            'id' => 59,
            'name' => 'Aumentar Atributo (Inteligência)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Inteligência em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_int_01.webp',
            'effects' => [
                ['tag' => 'mod_base_int', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 58],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        // Veterano

        Power::create([
            'id' => 60,
            'name' => 'Aumentar Atributo (Inteligência)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Inteligência em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_int_01.webp',
            'effects' => [
                ['tag' => 'mod_base_int', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 59],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        // Campeão

        Power::create([
            'id' => 61,
            'name' => 'Aumentar Atributo (Inteligência)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Inteligência em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_int_01.webp',
            'effects' => [
                ['tag' => 'mod_base_int', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 60],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        // Lenda

        Power::create([
            'id' => 62,
            'name' => 'Aumentar Atributo (Sabedoria)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar sua Sabedoria em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_sabedoria_01.webp',
            'effects' => [
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
            ],
        ]);

        // Iniciante

        Power::create([
            'id' => 63,
            'name' => 'Aumentar Atributo (Sabedoria)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar sua Sabedoria em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_sabedoria_01.webp',
            'effects' => [
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 62],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        // Veterano

        Power::create([
            'id' => 64,
            'name' => 'Aumentar Atributo (Sabedoria)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar sua Sabedoria em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_sabedoria_01.webp',
            'effects' => [
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 63],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        // Campeão

        Power::create([
            'id' => 65,
            'name' => 'Aumentar Atributo (Sabedoria)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar sua Sabedoria em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_sabedoria_01.webp',
            'effects' => [
                ['tag' => 'mod_base_knw', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 64],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        // Lenda

        Power::create([
            'id' => 66,
            'name' => 'Aumentar Atributo (Carisma)',
            'description' => 'Você pode escolher esse poder no patamar Novato para aumentar seu Carisma em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_carisma_01.webp',
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
            ],
        ]);

        // Iniciante

        Power::create([
            'id' => 67,
            'name' => 'Aumentar Atributo (Carisma)',
            'description' => 'Você pode escolher esse poder no patamar Veterano para aumentar seu Carisma em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_carisma_01.webp',
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 66],
                ['type' => 'character_level', 'min' => 5],
            ],
        ]);

        // Veterano

        Power::create([
            'id' => 68,
            'name' => 'Aumentar Atributo (Carisma)',
            'description' => 'Você pode escolher esse poder no patamar Campeão para aumentar seu Carisma em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_carisma_01.webp',
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 67],
                ['type' => 'character_level', 'min' => 11],
            ],
        ]);

        // Campeão

        Power::create([
            'id' => 69,
            'name' => 'Aumentar Atributo (Carisma)',
            'description' => 'Você pode escolher esse poder no patamar Lenda para aumentar seu Carisma em +1. <br><br>No APP, esse atributo é adicionado à base do seu personagem automaticamente.',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'aumentar_carisma_01.webp',
            'effects' => [
                ['tag' => 'mod_base_car', 'op' => 'add', 'value' => 1],
            ],
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'power', 'power_id' => 68],
                ['type' => 'character_level', 'min' => 17],
            ],
        ]);

        Power::create([
            'id' => 77,
            'name' => 'Ambidestria',
            'description' => 'Se estiver empunhando duas armas (e pelo menos uma delas for leve) e fizer a ação agredir, você pode fazer dois ataques, um com cada arma. Se fizer isso, sofre –2 em todos os testes de ataque até o seu próximo turno.',
            'source' => 'class',
            // Rides the attack roll itself — same shape as Ataque
            // Especial/Ataque Poderoso, decided fresh every time, no
            // pm_cost. Checking the box in the attack roll's power
            // checklist IS the self-report ("I'm using Ambidestria on
            // this attack") — once checked, both effects below apply
            // automatically via resolveTag, same as any other checked
            // power.
            'usability' => 'roll_active',
            'icon_file_name' => 'ambidestria_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'attribute', 'attribute' => 'dex', 'min' => 2],
            ],
            'effects' => [
                // Only the -2 mod_hit is modeled. extra_attack removed
                // 2026-09-04 (see Ataque Extra) — the second attack itself,
                // same as the dual-wielding requirement, is self-reported;
                // checking this box just applies the penalty automatically.
                ['tag' => 'mod_hit', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 78,
            'name' => 'Arqueiro',
            'description' => 'Se estiver usando uma arma de ataque à distância, você soma sua Sabedoria em rolagens de dano (limitado pelo seu nível).',
            'source' => 'class',
            'usability' => 'passive',
            'icon_file_name' => 'arqueiro_01.webp',
            'prerequisites' => [
                ['type' => 'class', 'class_ids' => [1, 2]], // Guerreiro, Caçador
                ['type' => 'attribute', 'attribute' => 'knw', 'min' => 1],
            ],
            // weapon_purpose is real, checkable data
            // (weapons.purpose) — gates whether this power is even
            // relevant to surface in a self-report checklist (e.g. the
            // planned attack-mode picker), separate from the effect's own
            // numeric value below.
            'visibility_reqs' => ['weapon_purpose' => ['thrown', 'fired']],
            'effects' => [
                // Same "attribute-code value + level cap" shape as
                // Percepção Temporal, minus its stack_group — that one
                // exists because Percepção Temporal's text explicitly says
                // "não cumulativo com efeitos que somam este atributo";
                // Arqueiro's text has no such clause.
                ['tag' => 'mod_dmg', 'op' => 'add', 'value' => 'knw', 'limit' => 'character_level'],
            ],
        ]);
    }
}
