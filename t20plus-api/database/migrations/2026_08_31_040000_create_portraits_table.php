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
        Schema::create('portraits', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');

            // Race ids this portrait shows as a default option for. Null
            // for a user-uploaded portrait, which has no race association.
            $table->json('race_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portraits');
    }
};
