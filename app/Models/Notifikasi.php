<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'siswa_id',
        'aturan_threshold_id',
        'level',
        'judul',
        'pesan',
        'notifikasiable_type',
        'notifikasiable_id',
        'dibaca_pada',
        'is_resolved',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
        'is_resolved' => 'boolean',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function aturanThreshold()
    {
        return $this->belongsTo(AturanThreshold::class);
    }

    public function notifikasiable()
    {
        return $this->morphTo();
    }
}
