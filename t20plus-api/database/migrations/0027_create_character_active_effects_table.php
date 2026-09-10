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

            $table->timestamps();

            $table->unique(['character_id', 'power_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_active_effects');
    }
};
