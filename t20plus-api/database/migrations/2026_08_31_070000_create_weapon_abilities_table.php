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
        Schema::create('weapon_abilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            // Same power_ids pattern as complications/age_brackets, for
            // consistency — but expect this to stay null/empty for every
            // row here: these are weapon mechanical traits (how the weapon
            // itself behaves), not something that grants a power. Kept for
            // the rare case one someday does.
            $table->json('power_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapon_abilities');
    }
};
