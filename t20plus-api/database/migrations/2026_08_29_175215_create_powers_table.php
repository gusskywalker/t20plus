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
        Schema::create('powers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('usability', ['active', 'passive']);
            $table->integer('pm_cost')->default(0);

            // JSON array of typed prerequisite entries, e.g.:
            // [
            //   { "type": "attribute", "attribute": "str", "min": 1 },
            //   { "type": "power", "power": "Estilo de Arremesso" },
            //   { "type": "class", "classes": ["guerreiro"], "min_level": 2 },
            //   { "type": "skill", "skill": "furtividade" }
            // ]
            // "power" entries reference another power by name (not id), since
            // these are hand-written from sourcebook text. "class" entries list
            // every class that qualifies (OR within the entry) so a power shared
            // by multiple classes still needs only one entry. Null/empty = no
            // prerequisites.
            $table->json('prerequisites')->nullable();

            // JSON array of typed effect entries, e.g.:
            // [
            //   { "tag": "mod_hit", "op": "add", "value": 2 },
            //   { "tag": "mod_dmg", "op": "add", "value": 2 }
            // ]
            // Same {tag, op, value} shape planned for race/item effects, so one
            // resolver can sum mod_* tags across every source. Passive powers'
            // effects always apply; active powers' effects only count while the
            // player has that power toggled on for the roll in question (that
            // toggle state is a runtime/character-sheet concern, not stored
            // here). Powers with a player choice at activation time (e.g.
            // splitting a bonus between mod_hit/mod_dmg) don't fit this shape
            // cleanly and are handled as special cases, not generically.
            // Null/empty = no mechanical effect (e.g. purely narrative powers).
            $table->json('power_effects')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('powers');
    }
};
