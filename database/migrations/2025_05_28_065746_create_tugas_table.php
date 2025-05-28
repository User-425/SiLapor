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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_laporan');   // relasi ke laporan_kerusakan
            $table->unsignedBigInteger('id_pengguna');  // teknisi (pengguna dengan role teknisi)
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi']);
            $table->date('batas_waktu')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['ditugaskan', 'dikerjakan', 'selesai'])->default('ditugaskan');
            $table->timestamps();

            // Relasi antar tabel
            $table->foreign('id_laporan')->references('id_laporan')->on('laporan_kerusakan')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};