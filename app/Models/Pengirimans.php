<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengirimans extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_invoice',
        'tgl_kirim',
        'tgl_tiba',
        'status_kirim',
        'nama_kurir',
        'telpon_kurir',
        'bukti_foto',
        'keterangan',
        'kredit_id'
    ];
    // Relasi: Dimiliki oleh satu kredit
    public function Kredit()
    {
        return $this->belongsTo(Kredit::class, 'kredit_id', 'id');
    }
}
