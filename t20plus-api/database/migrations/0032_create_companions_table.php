<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('companions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            $table->enum('type', ['parceiro', 'capanga', 'conjurado', 'familiar', 'montaria']);

            $table->enum('parceiro_type', [
                'aberrante',
                'adepto',
                'ajudante',
                'arauto',
                'artesao',
                'assassino',
                'atirador',
                'besta_de_carga',
                'carregador',
                'combatente',
                'destruidor',
                'emissario',
                'espiao',
                'fortao',
                'guardiao',
                'magivocador',
                'medico',
                'menestrel',
                'mentor',
                'mistico',
                'perseguidor',
                'sabio',
                'vigilante',
            ])->nullable();
            $table->enum('parceiro_tier', ['iniciante', 'veterano', 'mestre'])->nullable();

            $table->json('base_stats')->nullable();
            $table->json('character_related_effects')->nullable();

            $table->foreignId('source_spell_id')->nullable()->constrained('spells');
            $table->foreignId('source_power_id')->nullable()->constrained('powers');

            $table->string('icon_file_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companions');
    }
};
