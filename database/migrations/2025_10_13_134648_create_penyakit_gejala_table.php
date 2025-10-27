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
        Schema::create('penyakit_gejala', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyakit_id')->constrained('penyakit')->cascadeOnDelete();
            $table->foreignId('gejala_id')->constrained('gejala')->cascadeOnDelete();
            $table->decimal('bobot', 5, 2); // contoh: 1.00, 0.91, dst
            $table->timestamps();
            $table->unique(['penyakit_id', 'gejala_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyakit_gejala');
    }
};
