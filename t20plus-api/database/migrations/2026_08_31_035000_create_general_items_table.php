<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('general_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            $table->enum('type', ['tools', 'alchemic', 'food', 'potion', 'ammo']);

            $table->integer('cost');

            $table->decimal('slots', 4, 1);

            $table->string('icon_file_name')->nullable();

            $table->json('effects')->nullable();

            $table->boolean('consumable')->default(false);

            $table->string('base_dmg')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_items');
    }
};
