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
        Schema::create('character_accessories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained();

            // Every character gets all 5 rows up front, same convention as
            // character_hands — only accessory_1..accessory_4 start
            // enabled (the default T20 accessory limit), accessory_5 sits
            // disabled until a future power effect unlocks a 5th slot.
            $table->enum('name', ['accessory_1', 'accessory_2', 'accessory_3', 'accessory_4', 'accessory_5']);
            $table->boolean('enabled')->default(false);

            // Unlike character_hands.inventory_ids, this is a single real
            // FK, not a JSON array — an accessory slot can only ever hold
            // one item (no "shares a slot with others" case for
            // accessories), so there's no ambiguity to model. nullOnDelete
            // means destroying the equipped item clears the slot for free,
            // no manual cleanup needed the way hands/destroy has to do it.
            $table->foreignId('inventory_id')->nullable()->constrained('character_inventory')->nullOnDelete();

            $table->timestamps();

            $table->unique(['character_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_accessories');
    }
};
