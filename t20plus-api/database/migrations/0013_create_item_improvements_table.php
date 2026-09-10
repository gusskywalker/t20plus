<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('item_improvements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->boolean('is_material')->default(false);
            $table->json('extra_cost')->nullable();
            $table->json('categories');
            $table->json('restrictions')->nullable();
            $table->json('effects')->nullable();
            $table->json('prerequisites')->nullable();
            $table->json('incompatible_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_improvements');
    }
};
