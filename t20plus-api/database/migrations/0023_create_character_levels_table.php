<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('character_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('level');

            $table->foreignId('class_id')->constrained();

            $table->unsignedTinyInteger('class_level');

            $table->foreignId('power_id')->nullable()->constrained();

            // Which spell(s) were learned at this specific level — plural
            // since the starting level (or a caster's own growth cadence)
            // can grant more than one at once. Ties each pick to its own
            // acquisition level, same provenance calculateSpellSlotCircleCaps
            // already relies on (a slot's max círculo depends on which
            // level it was gained at, not the character's current level).
            $table->json('spell_ids')->nullable();

            $table->timestamps();

            $table->unique(['character_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_levels');
    }
};
