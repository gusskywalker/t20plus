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
            // (Anunciado has you shout the golpe's own name). Null = this
            // golpe's slot exists (earned by picking Golpe Pessoal at
            // character creation/level-up) but hasn't been built yet — the
            // character-sheet build modal shows it as an empty card.
            $table->string('name')->nullable();

            // Guerreiro's own class-relative level this golpe was last
            // (re)built at — set only when the player actually saves a
            // build, not at slot-creation time. Used to gate rebuilding:
            // "Quando sobe de nível, você pode reconstruir seu Golpe
            // Pessoal" means only one (re)build per level, so if this
            // matches the character's CURRENT Guerreiro level, the modal
            // is view-only until they level up again. Null = never built.
            $table->integer('guerreiro_level_picked')->nullable();

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
            // Null = not built yet.
            $table->json('power_ids')->nullable();

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
