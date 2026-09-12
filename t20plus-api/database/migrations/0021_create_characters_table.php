<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Deleting the campaign detaches its characters instead of
            // taking them down too.
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->integer('base_str');
            $table->integer('base_dex');
            $table->integer('base_con');
            $table->integer('base_int');
            $table->integer('base_knw');
            $table->integer('base_car');

            $table->integer('current_size');

            $table->foreignId('race_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('origin_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('god_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('portrait_id')->nullable()->constrained()->nullOnDelete();

            $table->json('trained_skill_ids')->nullable();

            $table->integer('age')->nullable();
            $table->enum('age_bracket', ['criança', 'adolescente', 'jovem', 'adulto', 'maduro', 'velho', 'anciao'])->nullable();

            $table->json('complication_ids')->nullable();

            $table->boolean('is_dead')->default(false);

            $table->integer('xp')->default(0);
            $table->integer('tibares')->default(0);

            $table->integer('current_pv')->nullable();
            $table->integer('current_pm')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
