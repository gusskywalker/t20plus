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

            // Where this power originates in a character's build (renamed
            // from "type" 2026-09-04). See claude-stuff/tag-library.md for
            // the value list, tag-system.md for the full reasoning.
            $table->enum('source', ['general', 'class', 'class_granted', 'divine_granted', 'races', 'tormenta', 'group', 'item_granted', 'consumable_granted', 'complication_granted', 'age_granted', 'origin_granted', 'specific']);

            // Which roll/screen resolves this power's effect. See
            // claude-stuff/tag-library.md for the value list, tag-system.md
            // for the full decision procedure — don't pattern-match against
            // the nearest example, this has been gotten wrong more than
            // once.
            $table->enum('usability', ['passive', 'active', 'roll_active', 'roleplay', 'resting', 'dc_active']);

            // Only meaningful for roll_active powers shown in a roll
            // screen's checklist. Pure UX default (starts the checkbox
            // checked or not), never a correctness mechanism — the player
            // can always flip it either way per roll.
            $table->boolean('default_checked')->default(false);

            // Which action-economy resource using this power costs (see
            // claude-stuff/t20-rules-summary.md). "none" covers passive/
            // roll_active powers, which never spend a separate action.
            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none'])->default('none');

            $table->integer('pm_cost')->default(0);

            // Only meaningful when usability = 'active': how long the
            // activated effect lasts. Null = resolves instantly (Medicina);
            // set = persists until manually turned off (Percepção
            // Temporal) — tracked via a future "currently active" list, not
            // auto-expired by the app yet.
            $table->enum('duration', ['turn', 'scene', 'day'])->nullable();

            // Rounds. Only meaningful for a cumulative effect (see
            // `damage_reduction` under effects) whose stacks build up each
            // time the triggering condition happens — this many rounds
            // without it happening again resets it. Null = doesn't decay
            // (almost everything). Stored for a future combat engine,
            // purely self-reported today.
            $table->integer('decay_after')->nullable();

            // Meters. Null = personal (affects only the character holding
            // it — almost everything). Set when the effect reaches beyond
            // the character (e.g. an aura). Not used for automated distance
            // math — no board/grid — purely a flag so a roll screen can
            // surface "this might apply," self-reported from there.
            $table->decimal('range', 4, 1)->nullable();

            // JSON array of typed prerequisite entries, e.g.:
            // [
            //   { "type": "attribute", "attribute": "str", "min": 1 },
            //   { "type": "power", "power_id": 5 },
            //   { "type": "class", "class_ids": [1], "min_level": 2 },
            //   { "type": "skill_trained", "skill_id": 3 },
            //   { "type": "god", "god_id": 1 },
            //   { "type": "character_level", "min": 5 },
            //   { "type": "race", "race_ids": [1] }
            // ]
            // See claude-stuff/tag-library.md for each type's fields.
            // Null/empty = no prerequisites.
            $table->json('prerequisites')->nullable();

            // JSON array of typed effect entries, e.g.:
            // [
            //   { "tag": "mod_hit", "op": "add", "value": 2 },
            //   { "tag": "mod_dmg", "op": "add", "value": 2 },
            //   { "tag": "mod_max_pm", "op": "add_per_level", "value": 1, "per_levels": 2 }
            // ]
            // See claude-stuff/tag-library.md for the tag/op vocabulary.
            // Powers with a player choice at activation time (e.g.
            // splitting a bonus between mod_hit/mod_dmg) don't fit this
            // shape and are handled as special cases, not generically.
            // Null/empty = no mechanical effect.
            $table->json('effects')->nullable();

            // Separate from `effects` on purpose — gates whether the power
            // is even relevant to SURFACE in a self-report checklist,
            // independent of whether its mechanic is numerically modeled at
            // all. See claude-stuff/tag-library.md for the key shape. Null
            // = always relevant (most powers).
            $table->json('visibility_reqs')->nullable();

            // The icon file's path under public/images/icons — matched by
            // eye and hand-linked in the seeder, not an FK to an icons
            // table (a scanned-folder id would shift every time a new icon
            // is added earlier in sort order; a filename doesn't).
            $table->string('icon_file_name')->nullable();
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
