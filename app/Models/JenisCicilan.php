<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JenisCicilan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'jenis_cicilan',
        'lama_cicilan',
        'margin_kredit',
        'dp',
    ];

    // Relasi: Punya banyak pengajuan kredit
    public function PengajuanKredit()
    {
        return $this->hasMany(PengajuanKredit::class, 'jenis_cicilan_id', 'id');
    }
    
}
