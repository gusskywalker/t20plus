<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('price');

            // Who can use this without the -5 non-proficiency penalty —
            // linked straight to the specific "Proficiência - ..." power id
            // (see PowerSeeder), not a broad category enum, since that's
            // what actually matters for exotic weapons (e.g. Arco de
            // Guerra needs power 44 specifically, not just "some exotic").
            // Null for simple weapons — armas simples need no proficiency
            // power at all, everyone has them by default.
            $table->foreignId('proficiency_id')->nullable()->constrained('powers');

            // Melee (tests Luta, adds Força to damage), thrown (tests
            // Pontaria, drawing is ação de movimento, adds Força to
            // damage), or fired (tests Pontaria, no attribute added to
            // damage). See claude-stuff/rules/weapon-rules.md.
            $table->enum('purpose', ['melee', 'thrown', 'fired']);

            // Empunhadura: light (benefits from Acuidade com Arma), one
            // hand (leaves the other free), two hand.
            $table->enum('grip', ['light', 'one_hand', 'two_hand']);

            $table->string('base_dmg'); // dice notation, e.g. "1d6"
            $table->integer('base_margin')->default(20); // crit threat, e.g. 19 = threat on 19-20
            $table->integer('base_multiplier')->default(2); // crit damage multiplier

            // Meters. 0 = melee/adjacent; ranged bands are curto=9,
            // médio=30, longo=90; some melee weapons have their own
            // non-adjacent reach (e.g. chicote/whip = 4.5) — one field
            // covers both cases instead of a separate ranged-only alcance
            // column. Decimal, not integer, because of cases like the whip.
            $table->decimal('base_reach', 4, 1);

            $table->enum('damage_type', ['slashing', 'bludgeoning', 'piercing']);
            $table->integer('space'); // espaço — inventory slots for carry capacity

            // JSON array of ids into weapon_abilities (Adaptável, Ágil,
            // Alongada, etc. — see weapon-rules.md and
            // WeaponAbilitySeeder). Not a real enum column since a weapon
            // can have more than one at once (e.g. chicote is both ágil e
            // versátil) — an enum column can only hold a single value per
            // row. Purely user-reported for now, same as everything else
            // self-reported today — no mechanical resolution built for
            // these yet.
            $table->json('ability_ids')->nullable();

            // Same {tag, op, value} shape as everywhere else (see
            // claude-stuff/tag-system.md) — e.g. Cajado Arcano's +1 arcane
            // PM limit/CD while wielded. Null for ordinary weapons; mainly
            // exists for exotéricos, but not exclusive to them.
            $table->json('effects')->nullable();

            // Marks a unique named item exotérico (e.g. Cajado Arcano)
            // rather than an ordinary weapon. Not a separate category/table
            // — exotéricos are just weapons/armors/accessories with unusual
            // effects, so they live in whichever of these three tables
            // matches their actual nature, flagged by this bool.
            $table->boolean('is_exoteric')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapons');
    }
};
