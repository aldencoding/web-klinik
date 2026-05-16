<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kunjungan_id', 'total_biaya', 'metode_pembayaran', 
        'status_pembayaran'
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}