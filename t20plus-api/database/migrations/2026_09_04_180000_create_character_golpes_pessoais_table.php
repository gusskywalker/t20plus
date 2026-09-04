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
        Schema::create('character_golpes_pessoais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            // Player-chosen — lets the checklist show "Fúria do Trovão" /
            // "Golpe Sombrio" as distinct rows instead of two identical
            // "Golpe Pessoal" checkboxes. Also matches the rulebook itself
            // (Anunciado has you shout the golpe's own name).
            $table->string('name');

            // Guerreiro's own class-relative level this golpe was picked
            // at (row.classLevel in character-creation-step-9.ts, not
            // character level) — Golpe Pessoal can be picked again at a
            // later level for a different golpe ("outras vezes para golpes
            // diferentes"), so this is what ties a build back to which
            // pick it came from.
            $table->integer('guerreiro_level_picked');

            // JSON array of powers.id — each id points at a
            // 'golpe_pessoal_option' power (see PowerSeeder), one per menu
            // item picked (Elemental, Brutal, Letal, etc.). PM cost and
            // effects are never cached here — both are resolved live by
            // summing/merging whatever these ids currently point at, same
            // reasoning as ability_ids/class_ids elsewhere: if a menu
            // item's own balance changes later, every character's golpe
            // picks it up automatically instead of going stale. No
            // weapon-restriction field — self-reported, same treatment as
            // Especialização em Arma (see claude-stuff/tag-system.md).
            $table->json('power_ids');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_golpes_pessoais');
    }
};
