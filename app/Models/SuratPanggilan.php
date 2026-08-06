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
        'poin_pemicu',
        'nomor_surat',
        'tanggal_surat',
        'alasan_pemanggilan',
        'daftar_kejadian',
        'total_poin',
        'tindakan_direkomendasikan',
        'status',
        'catatan',
        'dibuat_oleh', 'diajukan_oleh', 'disetujui_oleh', 'diajukan_pada',
        'disetujui_pada', 'dicetak_pada', 'dikirim_pada', 'selesai_pada', 'catatan_revisi',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'diajukan_pada' => 'datetime', 'disetujui_pada' => 'datetime', 'dicetak_pada' => 'datetime',
        'dikirim_pada' => 'datetime', 'selesai_pada' => 'datetime',
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

    public function histories()
    {
        return $this->hasMany(SuratPanggilanHistory::class)->latest();
    }
}
