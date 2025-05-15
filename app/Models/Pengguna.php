<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama_pengguna',
        'kata_sandi',
        'peran',
        'nama_lengkap',
        'email',
        'nomor_telepon',
    ];

    protected $hidden = [
        'kata_sandi',
    ];

    protected $casts = [
        'kata_sandi' => 'hashed',
    ];
}
