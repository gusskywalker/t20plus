<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('complications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            $table->enum('type', ['general', 'class', 'age']);

            $table->json('power_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complications');
    }
};
