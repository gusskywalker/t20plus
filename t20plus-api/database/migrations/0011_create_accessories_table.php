<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            $table->integer('cost');
            $table->integer('slots');

            $table->json('effects')->nullable();

            $table->integer('mp_cost')->default(0);

            $table->boolean('is_exoteric')->default(false);

            $table->string('icon_file_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
