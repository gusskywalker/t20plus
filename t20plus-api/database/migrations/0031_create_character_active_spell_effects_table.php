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

            // The final, already-resolved effects for THIS casting (base
            // spell effects with any muda-type aprimoramento overrides
            // baked in) — every calculator reads this and only this, same
            // as Power.effects everywhere else. Never re-derived from
            // spell_id + chosen_aprimoramento_indices at read time.
            $table->json('effects');

            // Display-only — which entries in the spell's own
            // aprimoramentos array were picked for this casting (indices,
            // duplicates included for a cumulative pick spent more than
            // once — same convention as golpes_pessoais.power_ids). Never
            // read by any calculator, purely for the Efeitos Ativos card.
            $table->json('chosen_aprimoramento_indices')->nullable();

            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_active_spell_effects');
    }
};
