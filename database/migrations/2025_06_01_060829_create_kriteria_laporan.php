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
        Schema::create('kriteria_laporan', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->unsignedBigInteger('id_laporan');
            
            // Evaluation from reporter (pelapor)
            $table->integer('tingkat_kerusakan_pelapor')->nullable();
            $table->integer('dampak_akademik_pelapor')->nullable();
            $table->integer('kebutuhan_pelapor')->nullable();
            
            // Evaluation from sarpras staff
            $table->integer('tingkat_kerusakan_sarpras')->nullable();
            $table->integer('dampak_akademik_sarpras')->nullable();
            $table->integer('kebutuhan_sarpras')->nullable();
            
            // Calculated priority score based on criteria
            $table->integer('skor_prioritas')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
            
            // Define one-to-one relationship with laporan_kerusakan
            $table->foreign('id_laporan')->references('id_laporan')->on('laporan_kerusakan')->onDelete('cascade');
            
            // Ensure one-to-one relationship
            $table->unique('id_laporan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_laporan');
    }
};