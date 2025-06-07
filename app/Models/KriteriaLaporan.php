<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaLaporan extends Model
{
    use HasFactory;

    protected $table = 'kriteria_laporan';
    protected $primaryKey = 'id_kriteria';
    
    protected $fillable = [
        'id_laporan',
        'tingkat_kerusakan_pelapor',
        'dampak_akademik_pelapor',
        'kebutuhan_pelapor',
        'tingkat_kerusakan_sarpras',
        'dampak_akademik_sarpras',
        'kebutuhan_sarpras',
        'skor_prioritas'
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan', 'id_laporan');
    }
}