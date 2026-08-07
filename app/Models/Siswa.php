<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nisn',
        'jenis_kelamin',
        'status',
        'dinonaktifkan_pada',
        'total_poin_pelanggaran',
        'total_poin_apresiasi',
    ];

    protected function casts(): array
    {
        return [
            'dinonaktifkan_pada' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function catatanPoin()
    {
        return $this->hasMany(CatatanPoin::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function laporanKesiswaan()
    {
        return $this->hasMany(LaporanKesiswaan::class);
    }

    public function suratPanggilan()
    {
        return $this->hasMany(SuratPanggilan::class);
    }

    public function getSaldoPoinAttribute()
    {
        return $this->total_poin_apresiasi - $this->total_poin_pelanggaran;
    }
}
