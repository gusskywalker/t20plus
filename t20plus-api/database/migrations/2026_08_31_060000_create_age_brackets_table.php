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
        Schema::create('age_brackets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            // Attribute-mod side (e.g. Criança's For -2/Con -1/Sab -1) —
            // same power_ids pattern as complications.power_ids, referencing
            // synthetic type: 'age_granted' powers. Null/empty = no
            // mechanical mods (e.g. Jovem).
            $table->json('power_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('age_brackets');
    }
};
