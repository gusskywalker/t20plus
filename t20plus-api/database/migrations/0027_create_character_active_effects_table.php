<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('character_active_effects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            $table->foreignId('power_id')->constrained();

            $table->boolean('is_active')->default(false);
            $table->boolean('is_favorite')->default(false);

            // Per-character customization for this specific granted-power
            // instance, shaped exactly like Power.effects — same tag/op/
            // value vocabulary, folded into getActiveEffects() alongside the
            // power's own effects. For open-ended player choices a shared
            // catalog Power row can't represent (e.g. Espião's "escolha uma
            // perícia... use Carisma" — every Espião character could pick a
            // different skill, so it can't live on the one shared Power row
            // everyone references).
            $table->json('custom_effect')->nullable();

            $table->timestamps();

            $table->unique(['character_id', 'power_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_active_effects');
    }
};
