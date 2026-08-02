<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPanggilanHistory extends Model
{
    protected $fillable = [
        'surat_panggilan_id', 'user_id', 'status_sebelumnya', 'status_baru', 'catatan',
    ];

    public function surat()
    {
        return $this->belongsTo(SuratPanggilan::class, 'surat_panggilan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
