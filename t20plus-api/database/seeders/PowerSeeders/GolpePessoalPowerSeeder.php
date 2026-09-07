<?php

namespace Database\Seeders\PowerSeeders;

use App\Models\Power;
use Illuminate\Database\Seeder;

class GolpePessoalPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Golpe Pessoal menu options (source: 'specific') — never
        // independently held/picked, only ever referenced by id from
        // character_golpes_pessoais.power_ids. usability is required by
        // the schema but unused for this source (nothing resolves these
        // through the normal usability-gated checklist path) — set to
        // 'passive' arbitrarily, same as every other 'specific' power.
        Power::create([
            'id' => 116,
            'name' => 'Conjurador',
            'description' => 'Escolha uma magia de 1º ou 2º círculos que tenha como alvo uma criatura ou que afete uma área. Se acertar seu golpe, você lança a magia como uma ação livre, tendo como alvo a criatura atingida ou como centro de sua área o ponto atingido pelo ataque (atributo-chave é um mental a sua escolha). Considere que a mão da arma está livre para lançar esta magia.',
            'source' => 'specific',
            'usability' => 'passive',
            // pm_cost is only the flat "+1 PM" part of "Custo da Magia +
            // 1 PM" — the variable spell-cost half can't be modeled, no
            // spellcasting system exists in the app at all.
            'pm_cost' => 1,
            // TODO: no effects — needs a full spellcasting system (spell
            // list, circles, casting a spell as a free action on hit) that
            // doesn't exist anywhere in the app yet. Self-reported for now.
        ]);

        Power::create([
            'id' => 117,
            'name' => 'Amplo',
            'description' => 'Seu ataque atinge todas as criaturas em alcance curto (incluindo aliados, mas não você mesmo). Faça um único teste de ataque e compare com a Defesa de cada criatura. <br><br>No APP, sem efeitos automáticos além do custo de PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 3,
            // No effects — multi-target/AoE, same resolution gap as every
            // other AoE power this session (Tornado de Dor etc.). No
            // combat engine planned, so this isn't "not resolved yet,"
            // there's no realistic resolver ever. Just wastes the PM,
            // fully self-reported.
        ]);

        Power::create([
            'id' => 118,
            'name' => 'Atordoante',
            'description' => 'Uma criatura que sofra dano do ataque fica atordoada por uma rodada (apenas uma vez por cena; Fortitude CD For anula). <br><br>No APP, sem efeitos automáticos além do custo de PM.',
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            // No effects — inflicts a condition on the TARGET (not the
            // character), gated by a save DC and a once-per-scene limit.
            // Target/enemy state is never trackable (same reason
            // Atordoante/Paralisante/Desconcertante-style effects go
            // untagged everywhere else) — no realistic resolver, ever.
            // Just wastes the PM, fully self-reported.
        ]);

        Power::create([
            'id' => 119,
            'name' => 'Destruidor',
            'description' => 'Aumenta o multiplicador de crítico em +1.',
            // Same name as the unrelated class power "Destruidor" (id 81,
            // reroll_dice_below) — different power, different mechanic,
            // just a naming collision from the sourcebook itself.
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
            // Repeatable, no stated cap — handled by allowing this id to
            // repeat in a character_golpes_pessoais row's power_ids
            // (frontend-side chain-of-checkboxes UI, no schema change
            // needed). Each occurrence's own extra_die entry and pm_cost
            // both sum naturally from however many times the id repeats.
            // Element type (ácido/eletricidade/fogo/frio) is cosmetic —
            // damage type isn't tracked per roll anywhere in the app.
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
            // Repeatable, capped at twice (frontend-side, same as Elemental
            // — no schema change). PM is cleanly additive (2 PM per pick),
            // but the margin bonus is NOT: two picks total -5, not -2-2=
            // -4 — golpe-pessoal-solver.ts special-cases this id picked
            // twice rather than summing generically. value here is the
            // single-pick amount.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            'effects' => [
                // Negative — a wider threat range means a LOWER base_margin
                // number, and op: 'add' always means "add this signed
                // value directly" (same convention as mod_hit's -2
                // penalties), never a magnitude with an implied direction.
                ['tag' => 'mod_margin', 'op' => 'add', 'value' => -2],
            ],
        ]);

        Power::create([
            'id' => 122,
            'name' => 'Sequencial',
            'description' => 'Seu golpe causa +1d6 pontos de dano. A cada vez que você acerta o golpe na mesma cena, esse bônus aumenta em um passo. <br><br>No APP, sem efeitos automáticos além do custo de PM. Role manualmente o bônus atual de dano extra.',
            // Needs a scene-persistent "how many times hit this scene"
            // counter — nothing in the app tracks that, same genuinely-new
            // gap as Sifão. Not self-report-and-forget either, since the
            // step size actually changes hit to hit — the player just
            // calculates it manually.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            // No effects — self-reported/manually calculated.
        ]);

        Power::create([
            'id' => 123,
            'name' => 'Sifão',
            'description' => 'Você recebe 1 PM temporário para cada 10 pontos da rolagem de dano. Você pode receber um máximo de PM temporários por cena igual ao seu nível e eles desaparecem no fim da cena. No APP, sem efeitos automáticos além do custo de PM. Adicione manualmente o PM temporário.',
            // Deliberately no `temp_pm` tag despite it existing (Êxtase na
            // Loucura) — that tag is a single flat pool with no concept of
            // per-source expiration/caps, and Sifão's own rule (expires
            // end of scene, capped at character level) can't share that
            // pool correctly alongside any other temp_pm source without
            // tracking which portion came from where. Not worth building
            // per-source tracking for one power — plainly self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 2,
            // No effects — self-reported/manually calculated.
        ]);

        Power::create([
            'id' => 124,
            'name' => 'Avanço',
            'description' => 'Você pode percorrer até o seu deslocamento em linha reta antes de desferir o golpe. <br><br>No APP, sem efeitos automáticos além do custo de PM.',
            // No effects — pure positioning, no board/grid exists to move
            // on (same reasoning as powers.range's own comment). Fully
            // self-reported.
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
                // value: 'weapon_die' — new sentinel, not a fixed dice
                // notation like Elemental's '2d6'. Just means "reroll
                // whatever weapon is already being used for this attack"
                // (the same one already rolled as Dados da Arma) — nothing
                // about the golpe's own configuration, since weapon
                // restriction isn't tracked anywhere (self-reported).
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
            // The "spend a standard action to charge, then attack by next
            // round" condition isn't tracked — self-reported, same as
            // every other once-per/cross-turn condition. Only the +2d8
            // itself is modeled.
        ]);

        Power::create([
            'id' => 127,
            'name' => 'Distante',
            'description' => 'Aumenta o alcance em um passo (de corpo a corpo para curto, médio e longo). Outras características não mudam (um ataque corpo a corpo com alcance curto continua usando Luta e somando sua Força no dano).',
            // No effects — no distance/range-band mechanic exists (no
            // board/grid, same reasoning as powers.range). Just wastes the
            // PM, fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 128,
            'name' => 'Impactante',
            'description' => 'Empurra o alvo 1,5m para cada 10 pontos de dano causado (arredondado para baixo). Por exemplo, 3m para 22 pontos de dano.',
            // New tag: push_distance — formula string "<meters>/<damage>",
            // read as floor(damage / <damage>) * <meters>. No board/grid
            // exists to actually move the target on, so this is purely an
            // informational readout after the damage roll (frontend
            // computes and displays "empurra X metros") — the player
            // narrates/applies the shove themselves.
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
                // New tag: advantage — a binary capability (op: grant), not
                // a summed value, since it changes how the die itself is
                // rolled rather than modifying a total. scope says which
                // roll it applies to, reusable across hit/dmg/skill rolls
                // instead of a distinct tag per roll type.
                ['tag' => 'advantage', 'op' => 'grant', 'scope' => 'hit'],
            ],
        ]);

        Power::create([
            'id' => 130,
            'name' => 'Qualquer Arma',
            'description' => 'Você pode usar seu Golpe Pessoal com qualquer tipo de arma.',
            // No effects — weapon restriction isn't tracked anywhere in
            // the first place (self-reported, see Especialização em Arma),
            // so there's nothing here to actually bypass. Just wastes the
            // PM, fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 131,
            'name' => 'Ricocheteante',
            'description' => 'A arma volta pra você após o ataque. Só pode ser usado com armas de arremesso.',
            // No effects — thrown-weapon return/ammo bookkeeping isn't
            // modeled anywhere. Just wastes the PM, fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 132,
            'name' => 'Teleguiado',
            'description' => 'Ignora penalidades por camuflagem ou cobertura leves.',
            // No effects — no camouflage/cover mechanic exists anywhere in
            // the app. Just wastes the PM, fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 1,
        ]);

        Power::create([
            'id' => 133,
            'name' => 'Brando',
            'description' => 'Seu golpe causa dano não letal.',
            // No effects — damage type isn't tracked per roll anywhere in
            // the app (same as Elemental's element type). Costs nothing,
            // fully self-reported.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => 0,
        ]);

        Power::create([
            'id' => 134,
            'name' => 'Golpe de Abertura',
            'description' => 'Seu golpe só pode ser usado em seu primeiro turno do combate.',
            // No effects — a once-per-combat timing restriction, self-
            // reported like every other once-per condition. Negative
            // pm_cost is a real discount toward the golpe's total PM cost
            // (there's a per-round PM spend cap on golpes pessoais equal
            // to Guerreiro level), not a placeholder — sums normally with
            // every other menu item's pm_cost.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 135,
            'name' => 'Truque Secreto',
            'description' => 'Seu golpe só pode ser usado uma vez contra cada alvo por cena.',
            // No effects — once-per-target-per-scene restriction, self-
            // reported. Negative pm_cost is a real discount, same as
            // Golpe de Abertura.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 136,
            'name' => 'Lento',
            'description' => 'Seu ataque exige uma ação completa para ser usado.',
            // No effects — action_cost isn't tracked per-golpe
            // (character_golpes_pessoais has no such field), self-
            // reported. Negative pm_cost is a real discount, same as the
            // other -2 entries.
            'source' => 'specific',
            'usability' => 'passive',
            'pm_cost' => -2,
        ]);

        Power::create([
            'id' => 137,
            'name' => 'Perto da Morte',
            'description' => 'O ataque só pode ser usado se você estiver com um quarto de seus PV ou menos. <br><br>No APP, sem efeitos automáticos além da redução de custo de PM. Siga a regra dos 1/4 PV manualmente.',
            // No effects — requires_hp_at_or_below doesn't apply here,
            // that field gates a specific EFFECT's contribution to a
            // total (Determinação Inabalável), and this power has no
            // numeric effect to attach it to. It's a pure usage
            // precondition on the whole golpe, self-reported like Golpe
            // de Abertura/Truque Secreto/Lento.
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
