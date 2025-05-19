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
        Schema::create('ruang', function (Blueprint $table) {
            $table->id('id_ruang');
            $table->unsignedInteger('id_gedung');
            $table->string('nama_ruang', 100);
            $table->text('deskripsi_lokasi')->nullable();
            $table->timestamps();

            $table->foreign('id_gedung')->references('id_gedung')->on('gedung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruang');
    }
};
