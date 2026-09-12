<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');

            // Case-sensitive, drawn from a fixed 57-glyph alphabet (see
            // Campaign::generateSecretCode) — the invite code a player
            // types into "Entrar em Campanha" to find this campaign.
            $table->string('secret_code', 5)->unique();
            // Plain text on purpose — this gates campaign membership among
            // people the master already knows, not a real auth boundary
            // (Google login already handles actual identity).
            $table->string('password');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
