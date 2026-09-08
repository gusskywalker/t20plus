<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('character_accessories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            $table->enum('name', ['accessory_1', 'accessory_2', 'accessory_3', 'accessory_4', 'accessory_5']);
            $table->boolean('enabled')->default(false);

            $table->foreignId('inventory_id')->nullable()->constrained('character_inventory')->nullOnDelete();

            $table->timestamps();

            $table->unique(['character_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_accessories');
    }
};
