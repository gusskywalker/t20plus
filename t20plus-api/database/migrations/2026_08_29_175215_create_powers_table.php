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

            // Sourcebook power category (Poderes Gerais/de Classe/
            // Concedidos/Raciais/da Tormenta/de Grupo).
            $table->enum('type', ['general', 'class', 'divine_granted', 'races', 'tormenta', 'group', 'resting']);

            // "passive": always-on, no player interaction. "toggle": a
            // modifier the player switches on for a roll they're already
            // making, riding along that roll — not a standalone activation
            // (e.g. Ataque Especial: decided at the moment of the attack,
            // costs no action of its own). "trigger": fires (or, before
            // combat is automated, is offered) based on an external
            // condition rather than player choice alone — see trigger_on
            // below for which condition; test is "would a rational player
            // ever decline this," not "whose roll does it touch" (a
            // conditional bonus with no cost, like Rejeição Divina or
            // Afinidade com a Tormenta, is trigger even though it modifies
            // the character's own roll). "action": a standalone thing the
            // player does — activating it isn't part of another roll, even
            // if it then affects future rolls (e.g. Medicina; Percepção
            // Temporal, which activates on its own and then lasts for a
            // duration) — see action_cost below for which action it costs.
            $table->enum('usability', ['passive', 'toggle', 'trigger', 'action']);

            // Which action-economy resource using this power costs, per the
            // ação padrão/de movimento/completa/extra/livre categories (see
            // claude-stuff/t20-rules-summary.md). "none" covers passive,
            // toggle, and trigger powers (none of them spend a separate
            // action of their own).
            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none']);

            $table->integer('pm_cost')->default(0);

            // How long an activated effect lasts once turned on — null means
            // "just this one roll" (e.g. Ataque Especial). When set, the
            // player manually turns it off later (tracked via a future
            // "currently active" list on the character, not auto-expired by
            // the app yet). A real closed enum, same reasoning as
            // action_cost: T20 draws durations from a small, system-defined
            // list, not an open vocabulary like tag/trigger_on — expand it
            // if a duration category we haven't seen yet shows up.
            $table->enum('duration', ['turn', 'scene', 'day'])->nullable();

            // Only meaningful when usability = 'trigger': names the external
            // condition that makes the power relevant (e.g. Êxtase da
            // Loucura fires when an enemy fails a save; Rejeição Divina
            // applies when targeted by a divine spell). Before combat is
            // automated, this is what a future roll screen would use to
            // filter "which of this character's powers could apply to the
            // roll I'm making" — the player still decides whether to
            // include it, same as any toggle, but the condition is what
            // makes it show up as an option at all. Plain string, not an
            // enum — like effects' "tag", this is an open, ever-growing
            // vocabulary discovered as more powers get seeded, not a small
            // fixed set like action_cost. Documented in
            // claude-stuff/tag-system.md as new values show up. Null for
            // every other usability.
            $table->string('trigger_on')->nullable();

            // JSON array of typed prerequisite entries, e.g.:
            // [
            //   { "type": "attribute", "attribute": "str", "min": 1 },
            //   { "type": "power", "power_id": 5 },
            //   { "type": "class", "class_ids": [1], "min_level": 2 },
            //   { "type": "skill", "skill_id": 3 }
            // ]
            // "power"/"class"/"skill" entries all reference their target by
            // id (fixed, already-seeded reference tables — same convention
            // as everywhere else, e.g. origins.grants). "class" entries list
            // every class id that qualifies (OR within the entry) so a power
            // shared by multiple classes still needs only one entry. Null/
            // empty = no prerequisites.
            $table->json('prerequisites')->nullable();

            // JSON array of typed effect entries, e.g.:
            // [
            //   { "tag": "mod_hit", "op": "add", "value": 2 },
            //   { "tag": "mod_dmg", "op": "add", "value": 2 },
            //   { "tag": "mod_pm", "op": "add_per_level", "value": 1, "per_levels": 2 }
            // ]
            // Same {tag, op, value} shape planned for race/item effects, so one
            // resolver can sum mod_* tags across every source. "add_per_level"
            // scales with the character's current total level instead of a
            // flat value: total bonus = floor(level / per_levels) * value
            // (e.g. Vontade de Ferro's "+1 PM a cada dois níveis" above).
            // Passive powers'
            // effects always apply; active powers' effects only count while the
            // player has that power toggled on for the roll in question (that
            // toggle state is a runtime/character-sheet concern, not stored
            // here). Powers with a player choice at activation time (e.g.
            // splitting a bonus between mod_hit/mod_dmg) don't fit this shape
            // cleanly and are handled as special cases, not generically.
            // Null/empty = no mechanical effect (e.g. purely narrative powers).
            $table->json('effects')->nullable();
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
