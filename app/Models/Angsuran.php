<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Angsuran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kredit_id',
        'tgl_bayar',
        'angsuran_ke',
        'total_bayar',
        'transaction_id',
        'order_id',
        'bukti_angsuran',
        'keterangan',
    ];

    // Relasi: Dimiliki oleh satu kredit
    public function Kredit()
    {
        return $this->belongsTo(Kredit::class, 'kredit_id', 'id');
    }

}
