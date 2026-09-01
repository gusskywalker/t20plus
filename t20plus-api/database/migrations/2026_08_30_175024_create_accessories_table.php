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
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            // Tibares — same convention as armors/shields.cost. -1 = not
            // purchasable (e.g. Símbolo Sagrado — a devotion item from a
            // specific source, not something bought in a shop); the shop
            // UI filters these out rather than treating -1 as a literal
            // price.
            $table->integer('cost');
            $table->integer('slots');

            // JSON array of typed effect entries, same {tag, op, value} shape
            // planned for race/power/item effects everywhere else, e.g.:
            // [
            //   { "tag": "mod_con", "op": "add", "value": 1 },
            //   { "tag": "skill", "skill_id": 12, "op": "add", "value": 1 }
            // ]
            // Attribute-mod entries use the universal "mod_*" tag convention.
            // Skill-mod entries use tag "skill" plus a "skill_id" referencing
            // skills.id (skills are a fixed, already-seeded reference table,
            // so referencing by id is safe here — same reasoning as
            // classes.skills). Null/empty = no mechanical effect.
            $table->json('effects')->nullable();

            // Some accessories are activated (spend PM) rather than passive;
            // 0 = purely passive, always-on effect.
            $table->integer('mp_cost')->default(0);

            // Marks a unique named item exotérico (e.g. Bolsa de Pó) rather
            // than an ordinary accessory. Not a separate category/table —
            // exotéricos are just weapons/armors/accessories with unusual
            // effects, so they live in whichever of these three tables
            // matches their actual nature, flagged by this bool. See
            // claude-stuff/tag-system.md.
            $table->boolean('is_exoteric')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
