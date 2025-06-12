<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kode_barang',
        'nama_produk', // ✅ tambahkan ini agar bisa diisi secara mass-assignment
        'harga',
        'jumlah',
        'deskripsi',
    ];

    // Relasi ke tabel productions
    public function production()
    {
        return $this->belongsTo(Production::class, 'kode_barang', 'kode_barang');
    }
}


