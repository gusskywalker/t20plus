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
        Schema::create('item_improvements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            // True for "material especial" entries. T20 rule quirk: an
            // item can only have ONE special material applied at a time,
            // unlike regular melhorias where several different ones can
            // coexist — this flag is what the app checks to enforce that
            // when validating character_inventory.improvement_ids.
            $table->boolean('is_material')->default(false);

            // Regular melhorias don't set this — their price/CD follows a
            // flat by-count table (1 melhoria = +300 preço/+5 CD, 2 =
            // +3000/+10, ...), a general rule tied to how many an item has,
            // not to any specific improvement row. Materials have their own
            // distinct cost instead, hence a column just for them.
            $table->integer('extra_cost')->nullable();

            // Which equipment categories this improvement can be applied
            // to — JSON array since one improvement often covers several
            // at once (e.g. "qualquer das categorias acima" spans all of
            // them). Category strings: weapon, armor, shield, esoteric,
            // tool, clothing.
            $table->json('applies_to');

            // Same {tag, op, value} shape as everywhere else — see
            // claude-stuff/tag-system.md. Nullable since some improvements
            // may end up narrative-only or need mechanics not modeled yet.
            $table->json('effects')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_improvements');
    }
};
