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
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_fas_ruang');
            $table->text('deskripsi');
            $table->string('url_foto', 255);
            $table->enum('status', ['menunggu_verifikasi', 'diproses', 'diperbaiki', 'selesai', 'ditolak'])->default('menunggu_verifikasi');
            $table->integer('ranking')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            
            // Define foreign keys with explicit column references
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna');
            $table->foreign('id_fas_ruang')->references('id_fas_ruang')->on('fasilitas_ruang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
};