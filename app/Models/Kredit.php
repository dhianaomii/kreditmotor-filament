<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Kredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_kredit_id',
        'metode_pembayaran_id',
        'tgl_mulai_kredit',
        'tgl_selesai_kredit',
        'status_kredit',
        'url_bukti_bayar',
        'sisa_kredit',
        'keterangan_status_kredit',      
    ];

    // Jumlah yang sudah dibayar
    public function getJumlahSudahDibayarAttribute()
    {
        $total = $this->angsuran()->sum('total_bayar');
        Log::info('Jumlah Sudah Dibayar', [
            'pengajuan_id' => $this->id,
            'total' => $total,
            'angsuran_count' => $this->angsuran()->count()
        ]);
        return $total;
    }
    
    // Sisa kredit
    public function getSisaKreditAttribute()
    {
        $sisa = max(0, $this->PengajuanKredit->harga_kredit - $this->PengajuanKredit->dp - $this->jumlah_sudah_dibayar);
        Log::info('Sisa Kredit', [
            'pengajuan_id' => $this->id,
            'harga_kredit' => $this->harga_kredit,
            'jumlah_sudah_dibayar' => $this->jumlah_sudah_dibayar,
            'sisa' => $sisa
        ]);
        return $sisa;
    }

    public function PengajuanKredit(): BelongsTo
    {
        return $this->belongsTo(PengajuanKredit::class);
    }
    public function MetodePembayaran(): BelongsTo
    {
        return $this->belongsTo(MetodePembayaran::class);
    }
    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'kredit_id');
    }
}
