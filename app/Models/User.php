<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'nama_lengkap',
        'role_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    public function catatanPoin()
    {
        return $this->hasMany(CatatanPoin::class, 'pencatat_id');
    }

    public function hasRole($role)
    {
        return $this->role->nama_role === $role;
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role->nama_role, $roles);
    }
}
