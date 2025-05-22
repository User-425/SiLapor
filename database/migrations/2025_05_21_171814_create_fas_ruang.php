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
        Schema::create('fasilitas_ruang', function (Blueprint $table) {
            $table->id('id_fas_ruang');
            $table->unsignedBigInteger('id_fasilitas');
            $table->unsignedBigInteger('id_ruang');
            $table->string('kode_fasilitas', 100);
            $table->timestamps();

            $table->foreign('id_fasilitas')->references('id_fasilitas')->on('fasilitas');
            $table->foreign('id_ruang')->references('id_ruang')->on('ruang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_ruang');
    }
};