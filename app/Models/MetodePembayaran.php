<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'metode_pembayaran',
        'tempat_bayar', 
        'no_rekening',
        'logo'
    ];

    
}

