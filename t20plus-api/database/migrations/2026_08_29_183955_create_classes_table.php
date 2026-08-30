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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('initial_pv');
            $table->integer('initial_pm');
            $table->integer('level_pv');
            $table->integer('level_pm');

            // JSON array of typed skill-grant entries, e.g.:
            // [
            //   { "picks": 1, "options": [12, 27] },
            //   { "picks": 1, "options": [9] },
            //   { "picks": 2, "options": [1, 3, 5, 8, 11, 14, 16, 18, 21, 27, 29] }
            // ]
            // "options" holds skill ids (not names) since skills are a fixed,
            // already-seeded reference table with stable ids — unlike powers,
            // which reference each other by name because they're hand-written
            // from sourcebook text with no guaranteed seed order. A guaranteed
            // skill is just a "choice" with one option and picks 1; an "A or B"
            // grant is picks 1 with multiple options; "N a sua escolha entre..."
            // is picks N. No separate fixed/choice type needed. Null/empty = no
            // skills granted at level 1 (shouldn't happen in practice).
            $table->json('skills')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
