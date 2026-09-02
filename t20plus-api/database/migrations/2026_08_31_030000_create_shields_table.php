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
        Schema::create('shields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['light', 'heavy']);
            $table->integer('mod_def');
            $table->integer('armor_penalty');
            $table->integer('cost');
            $table->integer('slots');

            // Same {tag, op, value} shape as everywhere else (see
            // claude-stuff/tag-system.md).
            $table->json('effects')->nullable();

            // Marks a unique named item exotérico rather than an ordinary
            // shield. Not a separate category/table — exotéricos are just
            // weapons/armors/accessories/shields with unusual effects, so
            // they live in whichever of these tables matches their actual
            // nature, flagged by this bool.
            $table->boolean('is_exoteric')->default(false);
            $table->foreignId('icon_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shields');
    }
};
