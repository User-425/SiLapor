<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

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

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }
}
