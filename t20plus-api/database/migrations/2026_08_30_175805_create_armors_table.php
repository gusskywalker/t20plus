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
        Schema::create('armors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('type', ['light', 'heavy']);
            $table->integer('mod_def');
            $table->integer('armor_penalty');
            $table->integer('cost');
            $table->integer('slots');

            // JSON array of typed effect entries, same {tag, op, value} shape
            // used everywhere else (powers, accessories, planned race/item
            // effects), e.g.:
            // [
            //   { "tag": "mod_con", "op": "add", "value": 1 },
            //   { "tag": "skill", "skill_id": 12, "op": "add", "value": 1 }
            // ]
            // Attribute-mod entries use the universal "mod_*" tag convention.
            // Skill-mod entries use tag "skill" plus a "skill_id" referencing
            // skills.id (skills are a fixed, already-seeded reference table,
            // so referencing by id is safe). Null/empty = no extra mechanical
            // effect beyond the armor's own mod_def/armor_penalty above.
            $table->json('effects')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armors');
    }
};
