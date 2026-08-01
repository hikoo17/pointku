<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPoin extends Model
{
    protected $table = 'kategori_poin';

    protected $fillable = [
        'jenis',
        'nama_kategori',
        'bobot_poin',
        'tingkat',
    ];

    public function catatanPoin()
    {
        return $this->hasMany(CatatanPoin::class);
    }
}
