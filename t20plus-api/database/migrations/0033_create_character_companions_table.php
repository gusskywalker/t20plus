<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('character_companions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->foreignId('companion_id')->constrained();

            $table->string('name')->nullable();

            $table->json('extra_character_related_effects')->nullable();
            $table->json('companion_related_effects')->nullable();

            $table->integer('current_pv')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_companions');
    }
};
