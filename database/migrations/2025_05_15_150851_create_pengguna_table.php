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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('id_pengguna');
            $table->string('nama_pengguna', 50)->unique();
            $table->string('kata_sandi', 255);
            $table->enum('peran', ['admin', 'mahasiswa', 'dosen','tendik', 'sarpras', 'teknisi']);
            $table->string('nama_lengkap', 100);
            $table->string('email', 100)->unique();
            $table->string('nomor_telepon', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna        php artisan make:model Pengguna');
    }
};
