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
        Schema::create('character_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            // Character-relative level this row represents (1-indexed,
            // matches orderedClassIds' index+1 on the frontend draft) —
            // without this, "which row is level 5" only exists as
            // insertion order, which breaks the moment a level needs
            // inserting/editing later (e.g. a retroactive multiclass
            // change).
            $table->unsignedTinyInteger('level');

            $table->foreignId('class_id')->constrained();

            // This class's own relative level at this row (e.g. a
            // Guerreiro/Bárbaro multiclass's 2nd Guerreiro level is
            // class_level 2 even though it might be character level 5) —
            // same class-relative vs. character-relative distinction as
            // step 9's LevelPowerRow.classLevel and the "class" prerequisite
            // type's min_level (see tag-system.md).
            $table->unsignedTinyInteger('class_level');

            // Null on a class's own first level (class_level 1) — no power
            // choice there, just baseline class features. Every level after
            // that (for that class) picks a power.
            $table->foreignId('power_id')->nullable()->constrained();

            $table->timestamps();

            $table->unique(['character_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_levels');
    }
};
