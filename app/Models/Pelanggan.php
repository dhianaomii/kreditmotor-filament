<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Pengirimans;

class Pelanggan extends Authenticatable
{
    use HasFactory;
    protected $guard = 'pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'email',
        'password',
        'no_hp',
        'alamat1',
        'kota1',
        'provinsi1',
        'kode_pos1',
        'alamat2',
        'kota2',
        'provinsi2',
        'kode_pos2',
        'alamat3',
        'kota3',
        'provinsi3',
        'kode_pos3',
        'foto',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        // 'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function pengirimans()
    {
        return $this->hasManyThrough(Pengirimans::class, Kredit::class, 'pengajuan_kredit_id', 'kredit_id');
    }

    // Cek apakah pelanggan punya alamat utama (alamat1)
    public function hasAlamat()
    {
        return !empty($this->alamat1) && !empty($this->kota1) && !empty($this->provinsi1) && !empty($this->kode_pos1);
    }

    // Mendapatkan alamat utama dalam format lengkap
    public function getAlamatUtamaAttribute()
    {
        if (!$this->hasAlamat()) {
            return null;
        }
        return "{$this->alamat1}, {$this->kota1}, {$this->provinsi1}, {$this->kode_pos1}";
    }
}
