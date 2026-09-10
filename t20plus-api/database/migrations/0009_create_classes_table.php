<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('initial_pv');
            $table->integer('initial_pm');
            $table->integer('level_pv');
            $table->integer('level_pm');

            $table->json('skills')->nullable();

            $table->integer('divine_power_picks')->default(1);

            $table->json('proficiency_ids')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
