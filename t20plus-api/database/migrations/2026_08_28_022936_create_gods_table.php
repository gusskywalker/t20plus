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
        Schema::create('gods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('energy_type')->nullable();

            // A god doesn't have effects of its own — it grants other things
            // (its "Poderes Concedidos"), which themselves may have effects.
            // Hence "grants", not "effects" (unlike powers/accessories/
            // armors, which really do carry effects). Same shape/column as
            // origins.grants — a JSON array of choice groups,
            // { "type": "choice", "label": "...", "picks": N, "options": [...] }.
            // A god's granted-powers list is exactly the same "pick N of a
            // list" mechanic as an origin's benefit pool, so it reuses the
            // identical shape. "picks" is usually 1, but some classes grant
            // 2 — that class-conditional case is deferred (not modeled yet).
            // See claude-stuff/t20-rules-summary.md.
            $table->json('grants')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gods');
    }
};
