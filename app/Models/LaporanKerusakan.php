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
}
