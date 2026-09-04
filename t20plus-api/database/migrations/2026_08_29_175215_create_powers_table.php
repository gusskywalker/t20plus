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
            // "item_granted", "consumable_granted", "complication_granted",
            // "age_granted", and "class_granted" are app-specific buckets,
            // not sourcebook categories: each is a synthetic power that
            // only exists so something else can `grant`/reference it — the
            // player never picks it directly, it's excluded from any
            // "choose your powers" list, and it exists purely to carry
            // usability/effects for something else's effect.
            // "item_granted" is referenced from an item_improvements effect
            // (e.g. Farpada granting "Causar Sangramento") — always a
            // passive/trigger effect from gear you're wearing/wielding.
            // "consumable_granted" is its active counterpart: referenced
            // from a general_items effect the same way (e.g. Essência de
            // Mana granting its "drink for 1d4 PM" power) — usability:
            // active, since using it is a deliberate one-shot action, not
            // an always-on property of holding the item.
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
            $table->enum('type', ['general', 'class', 'class_granted', 'divine_granted', 'races', 'tormenta', 'group', 'resting', 'item_granted', 'consumable_granted', 'complication_granted', 'age_granted']);

            // Down to four values (dropped `trigger`/`trigger_active`
            // 2026-09-04 — no combat engine planned, and both collapsed
            // cleanly into the remaining ones once `trigger_on` was gone:
            // an automatic proc became `passive`, a per-roll self-judgment
            // call became `roll_active`, a PM-costed reactive activation
            // became `active`. See claude-stuff/tag-system.md for the
            // full history and decision procedure).
            //
            // "passive": always-on, no decision ever, even when the effect
            // is conditional — either a flat standing fact (Vontade de
            // Ferro's +2 Vontade) or an automatic proc whose condition is
            // just named in the effect's own tag (Farpada's
            // `on_critical_strike`, Arqueiro/Destruidor's
            // `visibility_reqs`) — the common thread is a rational
            // player never has anything to actively judge or decide, it
            // just applies whenever its own narrow condition is met.
            // "active": a standalone, deliberate activation — not riding
            // on another roll — whether resolving instantly or persisting
            // is `duration`'s job (null = instant, like Medicina; set =
            // persists until turned off, like Percepção Temporal), not a
            // separate usability value. Also covers a PM-costed reactive
            // use in response to something just happening (Ataque
            // Reflexo: a target went unprepared/fleeing; Golpe de Raspão:
            // you just missed) — the activation is still a separate
            // moment from the roll that prompted it, not a modifier
            // riding that same roll. "roll_active": decided fresh at the
            // moment of one specific roll, self-reported via a checkbox
            // on whichever roll-type screen it belongs to — either riding
            // the player's own roll they're already making (Ataque
            // Especial, Ataque Poderoso — the original "roll_toggle"
            // rename reasoning: "toggle" reads like a persistent on/off
            // state, which this never is) or judging an external
            // circumstance against that specific roll (Rejeição Divina:
            // "was I just targeted by divine magic on THIS Fortitude/
            // Reflexos/Vontade roll?"). Unlike `passive`, this always
            // needs an active per-roll judgment call, even when free.
            // "roleplay": a capability the player actively chooses to
            // invoke, like "active" — but unlike every other value,
            // nothing about it is ever mechanical resolver-facing: no
            // effects, no pm_cost/duration that matter, not even a self-
            // reported roll-screen toggle. The roll it describes (if any)
            // and its consequences are resolved entirely in narrative
            // between player and master (e.g. Espalhar a Corrupção).
            // Distinct from "passive": passive things are just true about
            // the character, even with zero numeric effect; roleplay
            // things are chosen actions whose resolution never touches
            // the app at all.
            // See claude-stuff/tag-system.md for the full decision
            // procedure — don't pattern-match against the nearest
            // example, this has been gotten wrong more than once.
            $table->enum('usability', ['passive', 'active', 'roll_active', 'roleplay']);

            // Only meaningful for roll_active powers shown in a roll
            // screen's checklist (attack/damage/skill). Pure UX default,
            // not a correctness mechanism — true means "usually wanted,
            // start checked" (a no-downside bonus like Mestre em Arma's
            // weapon bonus), false (the default) means "a real cost/
            // benefit call each time, start unchecked" (Ambidestria's -2
            // to hit tradeoff). The player can always flip it either way
            // per roll; this only saves a tap on the common case.
            $table->boolean('default_checked')->default(false);

            // Which action-economy resource using this power costs, per the
            // ação padrão/de movimento/completa/extra/livre categories (see
            // claude-stuff/t20-rules-summary.md). "none" covers passive and
            // roll_active powers (neither spends a separate action of its
            // own — they're either always-on or ride a roll already being
            // made); "active" powers may or may not, per the power's own
            // text (e.g. Medicina costs an ação completa, Percepção
            // Temporal doesn't state a cost at all).
            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none'])->default('none');

            $table->integer('pm_cost')->default(0);

            // Only meaningful when usability = 'active': how long the
            // activated effect lasts once used. Null means it resolves
            // immediately (Medicina); set means it persists until the
            // player manually turns it off (Percepção Temporal, Aura
            // Sagrada) — tracked via a future "currently active" list on the
            // character, not auto-expired by the app yet. Null for every
            // other usability (roll_active never persists past the roll it
            // rides; passive isn't "activated" at all). A real
            // closed enum, same reasoning as action_cost: T20 draws
            // durations from a small, system-defined list (turn/scene/day/
            // sustentada...), not an open vocabulary like effects' tag —
            // expand it if a duration category we haven't seen yet shows up
            // (e.g. "sustentada" — Aura Sagrada — not added yet, no source
            // text confirming the full list).
            $table->enum('duration', ['turn', 'scene', 'day'])->nullable();

            // Rounds. Only meaningful for a cumulative effect (see e.g.
            // `damage_reduction` under effects) whose stacks build up each
            // time the triggering condition happens again: if this many
            // rounds pass without it happening again, the accumulated
            // effect ends/resets.
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
            //   { "tag": "mod_max_pm", "op": "add_per_level", "value": 1, "per_levels": 2 }
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

            // Separate from `effects` on purpose: this gates whether the
            // power is even relevant to SURFACE in a self-report checklist
            // UI (e.g. the planned attack-mode picker — atacar com mão
            // direita/esquerda/duas mãos — which will know the character's
            // current loadout), independent of whether the power's own
            // mechanic is numerically modeled at all. Works for a power
            // with zero `effects` (e.g. Inércia do Aço's unmodeled splash
            // damage still needs to show up only when attacking two-
            // handed). Same {weapon_grip/purpose/ability/any} shape
            // previously nested inside individual effect entries as
            // `requires_weapon_*` — moved here 2026-09-04 and dropped the
            // `requires_` prefix on each key (redundant once it's already
            // inside a column literally named `visibility_reqs`), since
            // it's a property of the power's
            // relevance, not of any one numeric effect. A condition that
            // instead gates whether a specific EFFECT counts toward a
            // standing/summed total (e.g. requires_hp_at_or_below on
            // Determinação Inabalável's resistance bonus) stays on that
            // effect entry — different concern, resolver-level not UI-
            // level. Null = always relevant (most powers).
            $table->json('visibility_reqs')->nullable();

            // The icon file's path under public/images/icons (e.g.
            // "items/weapons_01.webp", "durao_01.webp") — matched by eye
            // and hand-linked in the seeder, not an FK to an icons table.
            // Deliberately a string, not a foreignId: an id assigned by
            // scanning the icons folder alphabetically shifts every time a
            // new icon file is added anywhere before it in sort order,
            // silently invalidating every already-seeded reference. A
            // filename is stable regardless of what else gets added later.
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
