<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id('id_batch');
            $table->string('nama_batch');
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Add batch_id to laporan_kerusakan table
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_batch')->nullable();
            $table->foreign('id_batch')->references('id_batch')->on('batches')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropForeign(['id_batch']);
            $table->dropColumn('id_batch');
        });
        Schema::dropIfExists('batches');
    }
};