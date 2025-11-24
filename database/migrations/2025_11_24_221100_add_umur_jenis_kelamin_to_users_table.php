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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom umur (integer)
            $table->integer('umur')->after('email');
            // Menambahkan kolom jenis_kelamin (string)
            $table->string('jenis_kelamin')->after('umur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika rollback
            $table->dropColumn(['umur', 'jenis_kelamin']);
        });
    }
};
