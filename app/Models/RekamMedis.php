<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $fillable = [
        'kunjungan_id', 'pasien_id', 'dokter_id', 
        'keluhan', 'riwayat_penyakit', 'diagnosa', 
        'resep', 'status'
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function resepObats()
    {
        return $this->hasMany(ResepObat::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
}