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
            // Concedidos/Raciais/da Tormenta/de Grupo). "resting",
            // "item_granted", "complication_granted", "age_granted", and
            // "class_granted" are app-specific buckets, not sourcebook
            // categories: each is a synthetic power that only exists so
            // something else can `grant`/reference it — the player never
            // picks it directly, it's excluded from any "choose your
            // powers" list, and it exists purely to carry
            // usability/trigger_on/effects for something else's effect.
            // "item_granted" is referenced from an item_improvements effect
            // (e.g. Farpada granting "Causar Sangramento");
            // "complication_granted" is referenced from
            // complications.power_ids (e.g. Chato granting its -5
            // Diplomacia); "age_granted" is referenced from the frontend's
            // hardcoded AGE_BRACKETS.powerIds (character-creation-step-7.ts
            // — no age_brackets DB table, e.g. Criança's For -2/Con -1/Sab
            // -1);
            // "class_granted" is a power a class hands you automatically at a
            // given level with no choice involved (e.g. every Ataque
            // Especial tier — its prerequisites.min_level alone decides
            // when a Guerreiro has it, nothing is ever picked). "class"
            // stays reserved for the actual choosable pool a class picks
            // from at level-up (step 9's "Níveis e Poderes" dropdowns) —
            // that's the distinction: "class" = pickable, "class_granted" =
            // automatic fact, same relationship as "class" vs.
            // "divine_granted" already has for the pickable/automatic
            // divine split.
            $table->enum('type', ['general', 'class', 'class_granted', 'divine_granted', 'races', 'tormenta', 'group', 'resting', 'item_granted', 'complication_granted', 'age_granted']);

            // "passive": always-on, no player interaction, no decision ever
            // — still a fact our resolver scans (even if it happens to add
            // no numeric effect). "active": a standalone activation — not
            // riding on another roll — that the player deliberately uses
            // (e.g. Medicina, Percepção Temporal, Aura Sagrada). Whether it
            // resolves immediately or persists afterward is entirely
            // answered by `duration` below (null = resolves immediately,
            // like Medicina; set = persists until turned off, like
            // Percepção Temporal) — deliberately not a separate usability
            // value, since `duration` already carries that distinction and
            // encoding it twice would just be redundant. "roll_toggle":
            // rides along a roll the player is already making, decided
            // fresh every time, never persists (e.g. Ataque Especial,
            // Ataque Poderoso). "trigger": fires (or, before combat is
            // automated, is offered) based on an external condition rather
            // than player choice alone — see trigger_on below for which
            // condition; test is "would a rational player ever decline
            // this," not "whose roll does it
            // touch" (a conditional bonus with no cost, like Rejeição Divina
            // or Afinidade com a Tormenta, is trigger even though it
            // modifies the character's own roll). "roleplay": a capability
            // the player actively chooses to invoke, like "active" — but
            // unlike every other value, nothing about it is ever mechanical
            // resolver-facing: no effects, no pm_cost/duration/trigger_on
            // that matter, not even a self-reported roll-screen toggle. The
            // roll it describes (if any) and its consequences are resolved
            // entirely in narrative between player and master (e.g.
            // Espalhar a Corrupção). Distinct from "passive": passive
            // things are just true about the character, even with zero
            // numeric effect; roleplay things are chosen actions whose
            // resolution never touches the app at all.
            // See claude-stuff/tag-system.md for the full decision
            // procedure — don't pattern-match against the nearest example,
            // this has been gotten wrong more than once.
            $table->enum('usability', ['passive', 'active', 'roll_toggle', 'trigger', 'roleplay']);

            // Which action-economy resource using this power costs, per the
            // ação padrão/de movimento/completa/extra/livre categories (see
            // claude-stuff/t20-rules-summary.md). "none" covers passive,
            // roll_toggle, and trigger powers (none of them spend a separate
            // action of their own); "active" powers may or may not, per the
            // power's own text (e.g. Medicina costs an ação completa,
            // Percepção Temporal doesn't state a cost at all).
            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none'])->default('none');

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

            // Only meaningful when usability = 'trigger': names the
            // external condition(s) that make the power relevant (e.g.
            // Êxtase da Loucura fires when an enemy fails a save; Rejeição
            // Divina applies when targeted by a divine spell). JSON array
            // of strings, not one bare string — a power can care about more
            // than one atomic condition (e.g. Júbilo na Dor: both
            // `enemy_is_hit` and `you_take_damage`), and keeping each value
            // atomic (rather than inventing compound one-offs like
            // "you_deal_or_take_damage") matters because the intended
            // resolver works by action-type dictionary lookup — "rolling an
            // attack? check for these trigger_on values across all of the
            // character's powers" — a compound value would be invisible to
            // that lookup from either direction. Values aren't an enum —
            // like effects' "tag", this is an open, ever-growing vocabulary
            // discovered as more powers get seeded. Documented in
            // claude-stuff/tag-system.md as new values show up. Null for
            // every other usability.
            $table->json('trigger_on')->nullable();

            // Rounds. Only meaningful for a cumulative effect (see e.g.
            // `damage_reduction` under effects) whose stacks build up each
            // time trigger_on fires: if this many rounds pass without that
            // event happening again, the accumulated effect ends/resets.
            // Null = doesn't decay (almost everything). Same treatment as
            // `range` below — stored now for a future combat engine, purely
            // self-reported today since there's no live round-tracking.
            $table->integer('decay_after')->nullable();

            // Meters. Null = personal (affects only the character holding
            // the power — true for almost everything). Set when the effect
            // reaches beyond the character (e.g. an aura affecting nearby
            // enemies). Always meters, never a curto/médio/longo enum —
            // same reasoning as weapons.base_reach. NOT used for automated
            // distance math — there's no board/grid (see
            // combat-engine-plans.md) — it's purely a flag so a roll screen
            // can surface "this power might apply" to whoever's rolling
            // (e.g. a master rolling a save for a nearby NPC), who then
            // self-reports whether the target is actually in range. Same
            // permanent self-report trust model as movement-conditional
            // powers.
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
            // "power"/"class"/"skill_trained"/"god" entries all reference
            // their target by id (fixed, already-seeded reference tables —
            // same convention as everywhere else, e.g. origins.grants).
            // "skill_trained" — not just "skill" — since this only ever
            // checks "is the character trained in this skill," never a
            // numeric bonus threshold; a future power needing the latter
            // gets its own distinct type instead of overloading this one.
            // "class" entries list every class id that qualifies (OR within the
            // entry) so a power shared by multiple classes still needs only
            // one entry. "god" is how a Poder Concedido ties to its deity —
            // gods don't have their own "grants" list; a divine_granted
            // power just requires the matching god, so filtering "which
            // powers can this Aharadak devotee choose from" is a query
            // against powers, reusable at character creation AND every
            // future level-up, not a one-time grant step (see
            // claude-stuff/tag-system.md). "character_level" gates on the
            // character's total level (draft.totalLevel/orderedClassIds,
            // summed across every class) — distinct from "class"'s
            // min_level, which is that ONE class's own relative level, not
            // the character's overall level. Used for patamar-gated powers
            // (e.g. Aumento de Atributo's 4 tiers, chained via a "power"
            // prerequisite on the previous tier plus a "character_level"
            // floor — see PowerSeeder.php). "race" gates on the character's
            // race the same way "class" gates on class — race_ids lists
            // every race id that qualifies (OR within the entry), for a
            // "races" typed power's level-up pool entry (step 9). Null/empty
            // = no prerequisites.
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
