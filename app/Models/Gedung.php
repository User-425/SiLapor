<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    protected $table = 'gedung';
    protected $primaryKey = 'id_gedung';
    public $timestamps = true;

    protected $fillable = ['nama_gedung', 'deskripsi_lokasi'];
}
