<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('cost');

            $table->foreignId('proficiency_id')->nullable()->constrained('powers');

            $table->enum('purpose', ['melee', 'thrown', 'fired']);

            $table->boolean('is_firearm')->default(false);

            $table->enum('grip', ['light', 'one_hand', 'two_hand']);

            $table->string('base_dmg');
            $table->integer('base_margin')->default(20);
            $table->integer('base_multiplier')->default(2);

            $table->decimal('base_reach', 4, 1);

            $table->enum('damage_type', ['slashing', 'bludgeoning', 'piercing']);
            $table->integer('slots');

            $table->json('ability_ids')->nullable();

            $table->json('effects')->nullable();

            $table->boolean('is_exoteric')->default(false);

            $table->string('icon_file_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapons');
    }
};
