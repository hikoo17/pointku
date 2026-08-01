<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLaporan extends Model
{
    protected $table = 'approval_laporan';

    protected $fillable = [
        'laporan_kesiswaan_id',
        'approver_id',
        'status',
        'catatan_approval',
        'disetujui_pada',
    ];

    protected $casts = [
        'disetujui_pada' => 'datetime',
    ];

    public function laporanKesiswaan()
    {
        return $this->belongsTo(LaporanKesiswaan::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
