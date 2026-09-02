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
        Schema::create('character_hands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained();

            // Every character gets all 4 rows (hand_1..hand_4) regardless
            // of how many hands they actually have — see `enabled` below.
            // Fixed enum instead of an open int/label since 4 is the
            // practical ceiling for hands in T20 today.
            $table->enum('name', ['hand_1', 'hand_2', 'hand_3', 'hand_4']);

            // Whether this hand actually exists on the character right now
            // — a standard 2-armed character has hand_1/hand_2 enabled and
            // hand_3/hand_4 disabled. A future add_arm/remove_arm power
            // effect just flips this, no row insert/delete needed.
            $table->boolean('enabled')->default(false);

            // JSON array of character_inventory.id — not {item_type,
            // item_id} — so two identical owned weapons (e.g. two Espada
            // Curta) can each be tracked in a specific hand instead of
            // being indistinguishable. More than one entry covers e.g. a
            // fistful of daggers held in the same hand. Independent of
            // character_inventory.worn — worn still drives whether an
            // item's effects are active, this table only tracks which hand
            // holds what.
            $table->json('inventory_ids')->nullable();

            $table->timestamps();

            $table->unique(['character_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_hands');
    }
};
