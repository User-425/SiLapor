<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_batch';
    
    protected $fillable = [
        'nama_batch',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'catatan'
    ];

    /**
     * Get the reports associated with this batch
     */
    public function laporans()
    {
        return $this->hasMany(LaporanKerusakan::class, 'id_batch', 'id_batch');
    }
    
    /**
     * Get count of reports by status
     */
    public function getReportCountByStatusAttribute()
    {
        $counts = [
            'total' => $this->laporans->count(),
            'menunggu_verifikasi' => 0,
            'diproses' => 0,
            'diperbaiki' => 0,
            'selesai' => 0,
            'ditolak' => 0
        ];
        
        foreach ($this->laporans as $laporan) {
            $counts[$laporan->status]++;
        }
        
        return $counts;
    }
    
    /**
     * Calculate progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        $counts = $this->report_count_by_status;
        
        if ($counts['total'] === 0) {
            return 0;
        }
        
        return round(($counts['selesai'] / $counts['total']) * 100);
    }
}