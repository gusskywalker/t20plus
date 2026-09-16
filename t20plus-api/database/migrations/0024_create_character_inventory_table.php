<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('character_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();

            $table->enum('item_type', ['accessory', 'armor', 'weapon', 'shield', 'general_item']);
            $table->unsignedBigInteger('item_id');

            $table->boolean('worn')->default(false);

            $table->integer('quantity')->default(1);

            $table->json('improvement_ids')->nullable();
            $table->json('enchantment_ids')->nullable();

            // Self-applied item-enhancer powers currently on THIS weapon
            // instance — {power_id, remaining_uses?} entries. Separate from
            // improvement_ids/enchantment_ids (permanent, crafted) since
            // these are temporary and cleared by the player or by
            // remaining_uses hitting 0. See tag-system.md.
            $table->json('other_effects_power_ids')->nullable();

            $table->string('custom_name')->nullable();

            $table->integer('weapon_size')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('character_inventory');
    }
};
