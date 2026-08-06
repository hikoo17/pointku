<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanPoin extends Model
{
    protected $table = 'catatan_poin';

    protected $fillable = [
        'siswa_id',
        'kategori_poin_id',
        'pencatat_id',
        'guru_id',
        'tanggal',
        'keterangan',
        'bukti_foto',
        'status_validasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function getBuktiFotoListAttribute(): array
    {
        if (! $this->bukti_foto) {
            return [];
        }

        $paths = json_decode($this->bukti_foto, true);

        return is_array($paths) ? $paths : [$this->bukti_foto];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kategoriPoin()
    {
        return $this->belongsTo(KategoriPoin::class);
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'pencatat_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
