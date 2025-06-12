<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_masuk',
        'nama_produk',
        'kode_barang',
        'harga_per_pcs',
        'jumlah_barang',
        'suplier',
        'deskripsi',
    ];

    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class, 'kode_barang', 'kode_barang');
    }
}
