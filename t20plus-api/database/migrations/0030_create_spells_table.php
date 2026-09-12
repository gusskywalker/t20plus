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

            // Only meaningful when usability is 'damage' — which type the
            // base_spell_dmg roll deals, needed so a power like Explosão
            // Fulgente ("só pode ser aplicado em magias que causam dano de
            // fogo") can gate itself generically instead of a hardcoded
            // spell id list.
            $table->enum('damage_type', ['acid', 'electricity', 'fire', 'cold', 'light', 'darkness', 'essence', 'magic', 'psychic'])->nullable();

            $table->enum('action_cost', ['standard', 'movement', 'complete', 'extra', 'free', 'none', 'reaction'])->default('standard');

            // Free text, not an enum — range/info_affects/info_affected_area/
            // duration wording varies per spell (Pessoal/Toque/Curto/etc.)
            // rather than following one fixed tier list.
            $table->string('range')->nullable();

            // Purely informative — WHO/WHAT the spell's own text names as
            // its target (e.g. "1 humanoide", "aliados", "1 arma"). Never
            // read by any calculator or the ally-buff picker; that's
            // buff_affects's job below. `info_` prefix makes that split
            // explicit — nothing should ever branch logic off this field.
            $table->string('info_affects')->nullable();

            // Purely informative — the spatial shape/size a spell's area
            // covers (e.g. "cone de 4,5m"). Still read by one thing:
            // buff_affected_area-style generic powers like Magia Ampliada
            // ("dobra a área de efeito") check whether it's null to know if
            // they even apply to a given spell — display-oriented content,
            // mechanically-relevant presence check.
            $table->string('info_affected_area')->nullable();

            // Only meaningful for usability: 'buff' — who the ally-buff
            // picker (spell-casting-modal.ts) actually lets you choose
            // from. A fixed set of standardized tokens (currently just
            // 'caster'/'allies'), never free text — a spell with just
            // ['caster'] skips the picker entirely and self-applies, same
            // as before this field existed; ['allies'] shows every OTHER
            // campaign character; both together shows everyone including
            // the caster. Null for every non-buff spell — there's no enemy
            // roster to pick from, so this genuinely doesn't apply to
            // damage/debuff/utility.
            $table->json('buff_affects')->nullable();

            // Only meaningful alongside `buff_affects` — the picker's
            // starting cap on how many characters can be selected at once
            // (e.g. Arma de Jade's "1 arma" caps at 1; Bênção has no stated
            // cap, so null = unlimited). A checked enhancement can raise
            // this later (mod_max_targets, once a spell actually needs one)
            // without any schema change.
            $table->unsignedInteger('buff_base_max_targets')->nullable();

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
