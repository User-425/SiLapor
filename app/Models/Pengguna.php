<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes ;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama_pengguna',
        'kata_sandi',
        'peran',
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'img_url',
    ];

    protected $hidden = [
        'kata_sandi',
    ];

    protected $dates = ['deleted_at'];

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    /**
     * Get the laporan for the pengguna.
     */
    public function laporan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_pengguna', 'id_pengguna');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_pengguna');
    }
}
