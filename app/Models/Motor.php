<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 

class Motor extends Model
{
    protected $fillable = [
        'nama_motor',
        'jenis_motor_id',
        'harga_jual',
        'deskripsi_motor',
        'warna',
        'kapasitas_mesin',
        'tahun_produksi',
        'foto1',
        'foto2',
        'foto3',
        'stok',
        'jenis',
        'deskripsi_jenis',
        'image',
    ];
    public function JenisMotor(): BelongsTo
    {
        return $this->belongsTo(JenisMotor::class);
    }
    
}
