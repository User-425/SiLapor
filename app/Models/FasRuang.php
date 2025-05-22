<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasRuang extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fasilitas_ruang';
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_fas_ruang';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_fasilitas',
        'id_ruang',
        'kode_fasilitas',
    ];
    
    /**
     * Get the facility associated with this facility-room relationship.
     */
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
    
    /**
     * Get the room associated with this facility-room relationship.
     */
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'id_ruang', 'id_ruang');
    }
    
    /**
     * Get the damage reports for this facility-room.
     */
    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_fas_ruang', 'id_fas_ruang');
    }
}