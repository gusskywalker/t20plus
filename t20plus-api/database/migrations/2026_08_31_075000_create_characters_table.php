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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('level');
            $table->string('secret_code', 5)->unique();
            $table->integer('base_str');
            $table->integer('base_dex');
            $table->integer('base_con');
            $table->integer('base_int');
            $table->integer('base_knw');
            $table->integer('base_car');
            $table->foreignId('race_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('origin_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('god_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('portrait_id')->nullable()->constrained()->nullOnDelete();

            // Flat derived facts, not wizard bookkeeping — which origin
            // choice-group option or class-skill-group pick trained a
            // skill doesn't matter once creation is done, only that it's
            // trained. Powers that also train a skill are read straight
            // off the power (frontend concern), not duplicated here.
            $table->json('trained_skill_ids')->nullable();

            $table->integer('age')->nullable();
            $table->enum('age_bracket', ['criança', 'adolescente', 'jovem', 'adulto', 'maduro', 'velho', 'anciao'])->nullable();

            // Flat list regardless of source (general pick, age-bracket
            // requirement, etc.) — same "resulting fact, not provenance"
            // reasoning as trained_skill_ids.
            $table->json('complication_ids')->nullable();

            // Starting powers only — whatever origin/god/race/etc. granted
            // at character creation (general/tormenta/group picks, race
            // powers, divine_granted picks). NOT the character's full
            // currently-active power set (that's a separate, much more
            // frequently mutated table — active_effects — not JSON, since
            // powers get added/removed constantly post-creation) and NOT
            // class level-up picks (character_levels already tracks those
            // with real per-level provenance).
            $table->json('power_ids')->nullable();

            $table->boolean('is_dead')->default(false);

            $table->integer('xp')->default(0);
            $table->integer('tibares')->default(0);

            // Live state, not derivable — max PV/PM IS derivable (class +
            // level + CON, computed frontend-side since too many things
            // can affect it) so it isn't stored here, but current can't be
            // computed from anything else (damage/healing/spending mana
            // change it independently of level/class/attributes). Null,
            // not 0 (0 would be ambiguous with "actually at 0 PV" —
            // unconscious/dying, a real distinct state) — means "never
            // initialized yet." The first time the character sheet loads
            // a null value, it computes max and saves that back as the
            // starting current value.
            $table->integer('current_pv')->nullable();
            $table->integer('current_pm')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
