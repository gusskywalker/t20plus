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
        Schema::create('origins', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // An origin doesn't have effects of its own — it grants other
            // things (skills, powers, items) that themselves may have
            // effects. Hence "grants", not "effects" (unlike powers/
            // accessories/armors, which really do carry effects).
            //
            // JSON array of choice groups — every grant an origin makes lives
            // inside one, even when there's no real choice involved. Shape:
            // { "type": "choice", "label": "...", "picks": N, "options": [...] }
            // — same {picks, options} shape as classes.skills, wrapped with a
            // "label" (section heading for the frontend, e.g. "Itens") and a
            // "type" (currently always "choice"). The player picks exactly
            // `picks` of the listed options, and only those get applied.
            // "picks" can equal options.length — that just means every option
            // is mandatory (no real choice) while keeping the exact same
            // shape as a real choice, so the frontend renders every group the
            // same way (checkboxes capped at `picks`) instead of needing a
            // separate code path for "always granted". E.g. Acólito:
            // [
            //   { "type": "choice", "label": "Itens", "picks": 2, "options": [
            //     { "tag": "accessory", "op": "grant", "accessory_id": 1 },
            //     { "tag": "armor", "op": "grant", "armor_id": 1 }
            //   ] },
            //   { "type": "choice", "label": "Perícias e Poderes", "picks": 2, "options": [
            //     { "tag": "skill", "op": "trains", "skill_id": 7 },
            //     { "tag": "power", "op": "grant", "power_id": 6 }
            //   ] }
            // ]
            // Each option's tag names the target table directly and carries
            // its own "<tag>_id" field (skill_id, power_id, accessory_id,
            // armor_id, ...) rather than a generic "item"/"item_type"
            // indirection — all reference fixed, already-seeded reference
            // tables by id (unlike prerequisites' "power" entries, which stay
            // name-referenced since those are hand-written from sourcebook
            // text with no guaranteed order). See
            // claude-stuff/t20-rules-summary.md, "How Origins Work". Null/
            // empty = no mechanical effect.
            $table->json('grants')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('origins');
    }
};
