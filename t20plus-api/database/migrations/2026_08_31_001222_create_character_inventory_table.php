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
        Schema::create('character_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained();

            // Polymorphic-style reference: item_type says which catalog
            // table item_id points into. A real closed enum (small,
            // rarely-changing set of item categories, not an open
            // per-power vocabulary like tag/trigger_on) — only 'accessory'
            // and 'armor' exist as real catalog tables so far; add
            // 'weapon'/'exoteric' here once those tables get built. No real
            // FK on item_id (can't target more than one table at once) —
            // integrity is enforced app-side, same tradeoff every
            // polymorphic relation makes. Chosen over one join table per
            // item type specifically so "show this character's whole
            // inventory" stays a single query instead of a UNION across
            // several near-identical tables (see claude-stuff/tag-system.md
            // discussion, 2026-08-31).
            $table->enum('item_type', ['accessory', 'armor']);
            $table->unsignedBigInteger('item_id');

            $table->boolean('worn')->default(false);

            // Melhorias (improvements) and encantamentos (enchantments)
            // occupy separate slots on an item (per T20 rules), so they're
            // two separate lists, not one — each a JSON array of ids
            // referencing its own (not yet built) catalog table, same
            // "list of ids into a catalog" convention as classes.skills/
            // origins.grants use elsewhere. Those catalog tables aren't
            // built yet (no source text to seed them from) — these columns
            // just commit to the shape now, same as duration/trigger_on did
            // before every value was known. Pricing/crafting-CD data (how
            // much an improvement costs, its CD to craft) lives on the
            // future improvements table itself, not here.
            $table->json('improvement_ids')->nullable();
            $table->json('enchantment_ids')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_inventory');
    }
};
