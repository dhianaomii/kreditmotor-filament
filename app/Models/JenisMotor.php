<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 

class JenisMotor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'merk',
        'jenis',
        'deskripsi_jenis',
        'image',
    ];
    
}
