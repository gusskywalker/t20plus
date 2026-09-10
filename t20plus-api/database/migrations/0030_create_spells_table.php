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

            // Free text, not an enum — range/effect/duration wording varies
            // per spell (Pessoal/Toque/Curto/etc., "1 alvo", "esfera com
            // 6m de raio") rather than following one fixed tier list.
            $table->string('range')->nullable();
            $table->string('effect')->nullable();
            $table->string('duration')->nullable();

            // Display-only — which save applies, nothing more. The anula/
            // parcial nuance is frontend text, never computed here.
            $table->enum('resistance', ['fortitude', 'reflexos', 'vontade'])->nullable();

            // References the reagents catalog — display-only, never
            // enforced (same "just turns red" treatment as going negative
            // on tibares).
            $table->json('reagent_ids')->nullable();

            // The spell's own base mechanical effect (e.g. Bola de Fogo's
            // 6d6 fire damage) — same shape/role as Power.effects.
            $table->json('effects')->nullable();

            // Each entry: its own description, PM cost, and a type
            // (add/repeatable, muda/changes-text, truque/free-simplified),
            // plus an optional tag+value pair for the ones that are
            // actually mechanically resolvable — same role `effects` plays
            // on the powers table.
            $table->json('enhancements')->nullable();

            $table->string('icon_file_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spells');
    }
};
