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
        Schema::create('complications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            // Sourcebook complication category (Gerais, de Classe, de Idade).
            $table->enum('type', ['general', 'class', 'age']);

            // A complication's mechanical side is centralized under powers
            // (usability/effects/etc. already live there) rather than
            // duplicating that shape here — a complication just references
            // however many synthetic powers it grants. JSON array of power
            // ids since a complication isn't guaranteed to reduce to
            // exactly one (e.g. Chato above is a single passive -5
            // Diplomacia, but that's not yet confirmed universal). Null/
            // empty = purely narrative, no mechanical effect at all.
            $table->json('power_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complications');
    }
};
