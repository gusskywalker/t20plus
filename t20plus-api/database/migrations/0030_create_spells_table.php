<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('spells', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            $table->enum('type', ['arcana', 'divina', 'universal']);

            $table->unsignedTinyInteger('circle');

            $table->enum('school', ['abjuracao', 'adivinhacao', 'convocacao', 'encantamento', 'evocacao', 'ilusao', 'necromancia', 'transmutacao']);

            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none', 'reaction'])->default('standard');

            // Range/effect/duration stay plain strings for now — the actual
            // tier vocabulary (Pessoal/Toque/Curto/etc.) isn't pinned down
            // yet (see claude-stuff/rules/spells/spell-characteristics.md,
            // "Veja Regras do Jogo para detalhes"). Enum candidates once
            // that's known.
            $table->string('range')->nullable();
            $table->string('effect')->nullable();
            $table->string('duration')->nullable();

            // Display-only — which save applies, nothing more. The anula/
            // parcial nuance is frontend text, never computed here.
            $table->enum('resistance', ['fortitude', 'reflexos', 'vontade'])->nullable();

            // References the reagents table (not yet created) — display-
            // only, never enforced (same "just turns red" treatment as
            // going negative on tibares).
            $table->json('reagent_ids')->nullable();

            // Each entry: its own description, PM cost, and a type
            // (add/cumulative, muda/changes-text, truque/free-simplified),
            // plus an optional tag+value pair for the ones that are
            // actually mechanically resolvable — same role `effects` plays
            // on the powers table.
            $table->json('aprimoramentos')->nullable();

            $table->string('icon_file_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spells');
    }
};
