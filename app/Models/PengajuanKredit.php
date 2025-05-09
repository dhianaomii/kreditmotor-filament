<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Events\KreditBaruDiajukan;

class PengajuanKredit extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::created(function ($pengajuanKredit) {
            if ($pengajuanKredit->status_pengajuan === 'Menunggu Konfirmasi') {
                event(new KreditBaruDiajukan($pengajuanKredit));
            }
        });
    }
    protected $fillable = [
        'tgl_pengajuan_kredit',
        'pelanggan_id',
        'motor_id',
        'harga_cash',
        'dp',
        'jenis_cicilan_id',
        'harga_kredit',
        'asuransi_id',
        'biaya_asuransi_perbulan',
        'cicilan_perbulan',
        'url_kk',
        'url_ktp',
        'url_npwp',
        'url_slip_gaji',
        'url_foto',
        'status_pengajuan',
        'keterangan_status_pengajuan'
    ];

     // Relasi: Dimiliki oleh satu user
   public function Pelanggan()
   {
       return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
   }

   // Relasi: Punya satu motor
   public function Motor()
   {
       return $this->belongsTo(Motor::class, 'motor_id', 'id');
   }

   // Relasi: Punya satu jenis cicilan
   public function JenisCicilan()
   {
       return $this->belongsTo(JenisCicilan::class, 'jenis_cicilan_id', 'id');
   }

   // Relasi: Punya satu kredit
   public function Kredit()
   {
       return $this->hasOne(Kredit::class, 'pengajuan_kredit_id', 'id');
   }

    public function Asuransi(): BelongsTo
    {
        return $this->belongsTo(Asuransi::class);
    }   

}

