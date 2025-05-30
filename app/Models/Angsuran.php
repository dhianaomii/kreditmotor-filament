<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Angsuran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kredit_id',
        'tgl_bayar',
        'angsuran_ke',
        'total_bayar',
        'bukti_angsuran',
        'keterangan',
    ];

    // Relasi: Dimiliki oleh satu kredit
    public function Kredit()
    {
        return $this->belongsTo(Kredit::class, 'kredit_id', 'id');
    }

}
