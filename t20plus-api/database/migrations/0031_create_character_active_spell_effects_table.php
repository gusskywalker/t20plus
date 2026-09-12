<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('character_active_spell_effects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            $table->foreignId('spell_id')->constrained();

            // Who cast the spell that produced this row — display-only, so
            // the Efeitos Ativos card can show "de <caster>" on a buff
            // received from someone else (equal to character_id for a
            // self-cast, e.g. Armadura Arcana). Nullable + nullOnDelete
            // since losing the caster later shouldn't remove the buff
            // that's already active on the target.
            $table->foreignId('caster_character_id')->nullable()->constrained('characters')->nullOnDelete();

            // The final, already-resolved effects for THIS casting (base
            // spell effects with any muda-type enhancement overrides baked
            // in) — every calculator reads this and only this, same as
            // Power.effects everywhere else. Never re-derived from
            // spell_id + chosen_enhancement_indices at read time.
            $table->json('effects');

            // Display-only — which entries in the spell's own enhancements
            // array were picked for this casting (indices, duplicates
            // included for a repeatable pick spent more than once — same
            // convention as golpes_pessoais.power_ids). Never read by any
            // calculator, purely for the Efeitos Ativos card.
            $table->json('chosen_enhancement_indices')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_active_spell_effects');
    }
};
