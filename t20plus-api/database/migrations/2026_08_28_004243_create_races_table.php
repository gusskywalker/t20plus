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
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('mod_str');
            $table->integer('mod_dex');
            $table->integer('mod_con');
            $table->integer('mod_int');
            $table->integer('mod_knw');
            $table->integer('mod_car');
            $table->integer('mod_other');
            $table->integer('base_movement');
            $table->integer('base_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('races');
    }
};
