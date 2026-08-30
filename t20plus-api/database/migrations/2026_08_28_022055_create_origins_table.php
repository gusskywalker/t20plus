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

            // JSON array of entries, mixing two shapes:
            //
            // 1) Plain typed effect entries, same {tag, op, value} shape used
            //    everywhere else (powers, accessories, armors, classes' own
            //    entries). "op" isn't strictly arithmetical — "add"/"set" are
            //    numeric, "grant" marks a boolean capability with no value,
            //    and "trains" (skill entries only) marks a skill as trained
            //    rather than adding a bonus. These always apply
            //    unconditionally, e.g. Acólito's starting items:
            //    { "tag": "accessory", "op": "grant", "accessory_id": 1 }
            //    { "tag": "armor", "op": "grant", "armor_id": 1 }
            //
            // 2) A choice group — { "type": "choice", "count": N, "options": [...] }
            //    — same {count, options} shape as classes.skills, reused here
            //    for the origin's benefit pool (see
            //    claude-stuff/t20-rules-summary.md, "How Origins Work"): the
            //    player picks exactly `count` of the listed options, and only
            //    those get applied. "options" holds plain effect entries like
            //    the ones above, e.g.:
            //    { "type": "choice", "count": 2, "options": [
            //      { "tag": "skill", "skill_id": 7, "op": "trains" },
            //      { "tag": "power", "op": "grant", "power_id": 6 }
            //    ] }
            //
            // Each tag names the target table directly and carries its own
            // "<tag>_id" field (skill_id, power_id, accessory_id, armor_id,
            // ...) rather than a generic "item"/"item_type" indirection — all
            // reference fixed, already-seeded reference tables by id (unlike
            // prerequisites' "power" entries, which stay name-referenced
            // since those are hand-written from sourcebook text with no
            // guaranteed order). Null/empty = no mechanical effect.
            $table->json('effects')->nullable();
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
