<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_obat', 'stok', 'kategori', 'harga', 'expired_date'
    ];

    public function resepObats()
    {
        return $this->hasMany(ResepObat::class);
    }
}