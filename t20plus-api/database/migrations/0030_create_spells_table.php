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

            // Drives which top-level branch resolveCast() (spell-casting-
            // modal.ts) resolves through — damage rolls dice, debuff
            // inflicts a condition, buff persists a character_active_spell_
            // effects row, utility has no mechanical resolution at all
            // (Alarme, Abençoar Alimentos). Purely a dispatch key: a damage
            // spell that also inflicts a condition on success (Adaga
            // Mental) still stays 'damage' — the condition-on-success logic
            // underneath (trigger/tag/condition_id) is completely
            // unaffected by this field either way.
            $table->enum('usability', ['damage', 'buff', 'debuff', 'utility']);

            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none', 'reaction'])->default('standard');

            // Free text, not an enum — range/affects/affected_area/duration
            // wording varies per spell (Pessoal/Toque/Curto/etc.) rather
            // than following one fixed tier list.
            $table->string('range')->nullable();

            // The old single `effect` column ("Alvo/Área/Efeito" in the raw
            // spell block) was too heterogeneous to gate mechanics off of —
            // split into WHO/WHAT it targets (affects, e.g. "1 humanoide")
            // vs. the spatial shape/size it covers (affected_area, e.g.
            // "cone de 4,5m"). Usually only one or the other, occasionally
            // both (Área Escorregadia). Needed so a generic power like
            // Magia Ampliada ("dobra a área de efeito") can tell whether it
            // even applies to a given spell.
            $table->string('affects')->nullable();
            $table->string('affected_area')->nullable();

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
