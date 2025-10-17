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
        Schema::create('track_circuits', function (Blueprint $table) {
            $table->id();
            $table->integer('circuit_id')->unique();  // Matches API CircuitId
            $table->integer('track');                 // 0, 1, 2, 3
            $table->json('neighbors')->nullable();    // Stores left/right neighbor IDs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_circuits');
    }
};
