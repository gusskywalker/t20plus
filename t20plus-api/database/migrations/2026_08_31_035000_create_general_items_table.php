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
        Schema::create('general_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            // Everything that isn't a weapon/armor/shield/accessory —
            // tools, alchemic items, food, potions, ammunition. A single
            // catalog with a type enum instead of one table per category,
            // since none of these need enough type-specific columns to
            // justify a separate table the way weapons/armors do.
            $table->enum('type', ['tools', 'alchemic', 'food', 'potion', 'ammunition']);

            $table->integer('cost'); // -1 = not purchasable, same convention as every other catalog

            // Decimal, not integer like every other catalog's slots column
            // — alchemic items/potions/scrolls take HALF a space each per
            // claude-stuff/rules/inventory-slots.md ("dois desses itens
            // ocupam 1 espaço"), unlike weapons/armors/shields/accessories,
            // which are always whole numbers.
            $table->decimal('slots', 4, 1);
            // See powers_table's icon_file_name comment — a stable string
            // path, not an FK to an icons table.
            $table->string('icon_file_name')->nullable();

            // Same {tag, op, value} shape as everywhere else (see
            // claude-stuff/tag-system.md).
            $table->json('effects')->nullable();

            // Used up on use (potions, food, ammunition) vs. a tool you
            // keep using (most alchemic items are one-shot too, but not
            // universally — e.g. a lockpick set isn't consumed).
            $table->boolean('consumable')->default(false);

            // Dice notation, e.g. "1d6" — nullable since most of these
            // (tools, food, most ammunition) deal no damage on their own;
            // only thrown alchemic items like Frasco de Ácido need this.
            $table->string('base_dmg')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_items');
    }
};
