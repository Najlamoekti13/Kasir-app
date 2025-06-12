<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal (mass assignment)
    protected $fillable = [
        'tanggal',
        'nama_produk',
        'kode_produk',
        'harga',
        'quantity',
    ];

    // Supaya kolom tanggal otomatis di-handle sebagai tanggal (Carbon)
    protected $dates = ['tanggal'];

    // Accessor untuk menghitung total (harga x quantity) secara otomatis
    public function getTotalAttribute()
    {
        return $this->harga * $this->quantity;
    }
}
