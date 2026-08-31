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
            // Concedidos/Raciais/da Tormenta/de Grupo). "resting" and
            // "item_granted" are app-specific buckets, not sourcebook
            // categories: "item_granted" is a synthetic power that only
            // exists so an item_improvements effect can `grant` it — the
            // player never picks it directly, it's excluded from any
            // "choose your powers" list, and it exists purely to carry
            // usability/trigger_on/effects for something an item does
            // (e.g. Farpada granting "Causar Sangramento").
            $table->enum('type', ['general', 'class', 'divine_granted', 'races', 'tormenta', 'group', 'resting', 'item_granted']);

            // "passive": always-on, no player interaction, no decision ever.
            // "active": a standalone activation — not riding on another
            // roll — that the player deliberately uses (e.g. Medicina,
            // Percepção Temporal, Aura Sagrada). Whether it resolves
            // immediately or persists afterward is entirely answered by
            // `duration` below (null = resolves immediately, like Medicina;
            // set = persists until turned off, like Percepção Temporal) —
            // deliberately not a separate usability value, since `duration`
            // already carries that distinction and encoding it twice would
            // just be redundant. "roll_toggle": rides along a roll the
            // player is already making, decided fresh every time, never
            // persists (e.g. Ataque Especial, Ataque Poderoso). "trigger":
            // fires (or, before combat is automated, is offered) based on an
            // external condition rather than player choice alone — see
            // trigger_on below for which condition; test is "would a
            // rational player ever decline this," not "whose roll does it
            // touch" (a conditional bonus with no cost, like Rejeição Divina
            // or Afinidade com a Tormenta, is trigger even though it
            // modifies the character's own roll). See
            // claude-stuff/tag-system.md for the full decision procedure —
            // don't pattern-match against the nearest example, this has
            // been gotten wrong more than once.
            $table->enum('usability', ['passive', 'active', 'roll_toggle', 'trigger']);

            // Which action-economy resource using this power costs, per the
            // ação padrão/de movimento/completa/extra/livre categories (see
            // claude-stuff/t20-rules-summary.md). "none" covers passive,
            // roll_toggle, and trigger powers (none of them spend a separate
            // action of their own); "active" powers may or may not, per the
            // power's own text (e.g. Medicina costs an ação completa,
            // Percepção Temporal doesn't state a cost at all).
            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none']);

            $table->integer('pm_cost')->default(0);

            // Only meaningful when usability = 'active': how long the
            // activated effect lasts once used. Null means it resolves
            // immediately (Medicina); set means it persists until the
            // player manually turns it off (Percepção Temporal, Aura
            // Sagrada) — tracked via a future "currently active" list on the
            // character, not auto-expired by the app yet. Null for every
            // other usability (roll_toggle never persists past the roll it
            // rides on; passive/trigger aren't "activated" at all). A real
            // closed enum, same reasoning as action_cost: T20 draws
            // durations from a small, system-defined list (turn/scene/day/
            // sustentada...), not an open vocabulary like tag/trigger_on —
            // expand it if a duration category we haven't seen yet shows up
            // (e.g. "sustentada" — Aura Sagrada — not added yet, no source
            // text confirming the full list).
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
            //   { "type": "skill", "skill_id": 3 },
            //   { "type": "god", "god_id": 1 }
            // ]
            // "power"/"class"/"skill"/"god" entries all reference their
            // target by id (fixed, already-seeded reference tables — same
            // convention as everywhere else, e.g. origins.grants). "class"
            // entries list every class id that qualifies (OR within the
            // entry) so a power shared by multiple classes still needs only
            // one entry. "god" is how a Poder Concedido ties to its deity —
            // gods don't have their own "grants" list; a divine_granted
            // power just requires the matching god, so filtering "which
            // powers can this Aharadak devotee choose from" is a query
            // against powers, reusable at character creation AND every
            // future level-up, not a one-time grant step (see
            // claude-stuff/tag-system.md). Null/empty = no prerequisites.
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
