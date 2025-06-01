<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_laporan',
        'id_pengguna',
        'prioritas',
        'batas_waktu',
        'catatan',
        'status',
        'tanggal_selesai'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'batas_waktu',
        'tanggal_selesai'
    ];

    protected $casts = [
        'batas_waktu' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'id_laporan');
    }

    public function teknisi()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getPrioritasLabelAttribute()
    {
        return ucfirst($this->prioritas);
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->batas_waktu) return false;
        return !in_array($this->status, ['selesai']) && Carbon::now()->isAfter($this->batas_waktu);
    }

    public function getDurasiPengerjaanAttribute()
    {
        if (!$this->tanggal_selesai) return null;
        return $this->created_at->diffForHumans($this->tanggal_selesai, true);
    }
}