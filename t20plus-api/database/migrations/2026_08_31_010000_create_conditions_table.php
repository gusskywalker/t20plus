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
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            // Sourcebook condition category ("Efeito de X" on the card).
            // Used for immunities (e.g. a race immune to "fear" effects).
            // Real closed enum, same reasoning as powers.duration — T20
            // draws these from a small system-defined list. Only seed a
            // value once it's actually confirmed by a condition's card text.
            $table->enum('type', ['fear', 'metabolism', 'movement', 'senses', 'mental', 'tired', 'metamorphosis']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conditions');
    }
};
