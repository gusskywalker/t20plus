<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('powers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            $table->enum('source', ['general', 'class', 'class_granted', 'divine_granted', 'race_granted', 'race_optional', 'tormenta', 'group', 'item_granted', 'consumable_granted', 'complication_granted', 'age_granted', 'origin_granted', 'specific', 'power_granted', 'general_action']);

            $table->enum('usability', ['passive', 'active', 'roll_active', 'roleplay', 'resting', 'dc_active', 'vessel']);

            $table->boolean('default_checked')->default(false);

            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none'])->default('none');

            $table->integer('pm_cost')->default(0);

            $table->enum('duration', ['turn', 'scene', 'day'])->nullable();

            $table->integer('decay_after')->nullable();

            $table->decimal('range', 4, 1)->nullable();

            $table->json('prerequisites')->nullable();

            $table->json('effects')->nullable();

            $table->json('applies_when')->nullable();

            $table->string('icon_file_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('powers');
    }
};
