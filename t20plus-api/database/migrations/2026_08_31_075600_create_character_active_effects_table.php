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
        Schema::create('character_active_effects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            // Which power is currently in effect on this character —
            // conditions (Sangrando, Indefeso, etc.) are a different
            // mechanic entirely and get their own table, not folded in
            // here. Every row here always has a power — this table's
            // whole purpose is "which powers currently apply," so
            // power_id is never nullable.
            $table->foreignId('power_id')->constrained();

            // Whether this row is actually contributing right now —
            // separate fact from "the character has this power" (the row's
            // own existence). Set once at insert time (wherever a row gets
            // created, not just here): true for usability 'passive' (always
            // on, nothing to toggle), false for everything else —
            // 'roll_active' stays false forever (nothing ever flips it,
            // same as .worn stays false forever on a
            // general_item row), 'active' powers start false and the
            // sheet's Ativar button flips this on/off. getActiveEffects
            // (the frontend resolver behind Defesa/PV/PM/skill totals)
            // reads is_active directly instead of re-deriving it from
            // usability, so toggling an 'active' power like Percepção
            // Temporal on correctly folds its bonuses into those totals
            // for free, no extra resolver logic needed.
            $table->boolean('is_active')->default(false);

            $table->timestamps();

            $table->unique(['character_id', 'power_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_active_effects');
    }
};
