<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_pengguna',
        'id_fas_ruang',
        'deskripsi',
        'url_foto',
        'status',
        'ranking',
        'id_batch',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function fasilitasRuang()
    {
        return $this->belongsTo(FasRuang::class, 'id_fas_ruang', 'id_fas_ruang');
    }

    public function getRouteKeyName()
    {
        return 'id_laporan';
    }

    public function tugas()
    {
        return $this->hasOne(\App\Models\Tugas::class, 'id_laporan');
    }

    public function umpanBaliks()
    {
        return $this->hasMany(UmpanBalik::class, 'id_laporan', 'id_laporan');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'id_batch', 'id_batch');
    }

    public function kriteria()
    {
        return $this->hasOne(KriteriaLaporan::class, 'id_laporan', 'id_laporan');
    }
}
