<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPanggilan extends Model
{
    protected $table = 'surat_panggilan';

    protected $fillable = [
        'siswa_id',
        'laporan_kesiswaan_id',
        'aturan_threshold_id',
        'nomor_surat',
        'tanggal_surat',
        'alasan_pemanggilan',
        'daftar_kejadian',
        'total_poin',
        'tindakan_direkomendasikan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function laporanKesiswaan()
    {
        return $this->belongsTo(LaporanKesiswaan::class);
    }

    public function aturanThreshold()
    {
        return $this->belongsTo(AturanThreshold::class);
    }
}
