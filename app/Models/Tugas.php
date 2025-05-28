<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas'; // disesuaikan
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_laporan',
        'id_pengguna',
        'prioritas',
        'batas_waktu',
        'catatan',
        'status',
    ];

    // Relasi ke laporan kerusakan
    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan');
    }

    // Relasi ke teknisi (pengguna)
    public function teknisi()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
