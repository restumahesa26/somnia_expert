<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add is_admin column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
        });

        // Modify konsultasi table
        Schema::table('konsultasi', function (Blueprint $table) {
            $table->string('nama_pasien')->nullable()->after('user_id');
            $table->integer('umur')->nullable()->after('nama_pasien');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('umur');
            $table->boolean('is_admin_input')->default(false)->after('jenis_kelamin');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });

        Schema::table('konsultasi', function (Blueprint $table) {
            $table->dropColumn(['nama_pasien', 'umur', 'jenis_kelamin', 'is_admin_input']);
        });
    }
};
