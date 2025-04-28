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
        'keterangan',
    ];

    public function Kredit(): BelongsTo
    {
        return $this->belongsTo(Kredit::class);
    }

}
