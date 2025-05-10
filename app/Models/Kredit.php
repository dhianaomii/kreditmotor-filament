<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Kredit extends Model
{
    use HasFactory;

    protected $table = 'kredits';
    
    protected $fillable = [
        'pengajuan_kredit_id',
        'metode_pembayaran_id',
        'tgl_mulai_kredit',
        'tgl_selesai_kredit',
        'status_kredit',
        'url_bukti_bayar',
        'sisa_kredit',
        'keterangan_status_kredit',      
        'order_id',
        'payment_status',
        'snap_token'
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

    // Relasi: Dimiliki oleh satu pengajuan kredit
    public function PengajuanKredit()
    {
        return $this->belongsTo(PengajuanKredit::class, 'pengajuan_kredit_id', 'id');
    }

    // Relasi: Punya banyak angsuran
    public function Angsuran()
    {
        return $this->hasMany(Angsuran::class, 'kredit_id', 'id');
    }

    // Relasi: Punya satu pengiriman
    public function Pengiriman()
    {
        return $this->hasOne(Pengirimans::class, 'kredit_id', 'id');
    }

    // Relasi: Punya satu metode pembayaran
    public function MetodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id', 'id');
    }
}
