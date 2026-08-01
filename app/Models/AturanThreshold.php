<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AturanThreshold extends Model
{
    protected $table = 'aturan_threshold';

    protected $fillable = [
        'poin_batas',
        'level',
        'judul_notifikasi',
        'deskripsi',
        'template_surat',
        'has_surat_panggilan',
        'is_active',
    ];

    protected $casts = [
        'has_surat_panggilan' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function suratPanggilan()
    {
        return $this->hasMany(SuratPanggilan::class);
    }
}
