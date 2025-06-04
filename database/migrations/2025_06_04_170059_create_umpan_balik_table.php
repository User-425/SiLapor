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
        Schema::create('umpan_balik', function (Blueprint $table) {
            Schema::create('umpan_baliks', function (Blueprint $table) {
            $table->id('id_umpan_balik');
            $table->foreignId('id_laporan')->constrained('laporan_kerusakan', 'id_laporan')->onDelete('cascade');
            $table->foreignId('id_pengguna')->constrained('pengguna', 'id_pengguna')->onDelete('cascade');
            $table->unsignedTinyInteger('rating')->between(1, 5);
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umpan_baliks');
    }
};
