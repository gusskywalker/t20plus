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
