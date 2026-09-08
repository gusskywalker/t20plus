<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('character_hands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            $table->enum('name', ['hand_1', 'hand_2', 'hand_3', 'hand_4']);

            $table->boolean('enabled')->default(false);

            $table->json('inventory_ids')->nullable();

            $table->timestamps();

            $table->unique(['character_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_hands');
    }
};
