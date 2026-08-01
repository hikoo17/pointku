<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKesiswaan extends Model
{
    protected $table = 'laporan_kesiswaan';

    protected $fillable = [
        'siswa_id',
        'bk_id',
        'kesiswaan_id',
        'jenis_tindakan',
        'status',
        'catatan_kesiswaan',
        'diajukan_pada',
        'selesai_pada',
    ];

    protected $casts = [
        'diajukan_pada' => 'datetime',
        'selesai_pada' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function bk()
    {
        return $this->belongsTo(User::class, 'bk_id');
    }

    public function kesiswaan()
    {
        return $this->belongsTo(User::class, 'kesiswaan_id');
    }

    public function approvalLaporan()
    {
        return $this->hasMany(ApprovalLaporan::class);
    }

    public function suratPanggilan()
    {
        return $this->hasOne(SuratPanggilan::class);
    }
}
