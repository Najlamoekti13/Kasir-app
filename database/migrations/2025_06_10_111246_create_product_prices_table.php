<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');               // Tanggal penentuan harga jual
            $table->string('nama_produk');         // Nama produk (diambil dari production)
            $table->string('kode_barang');         // Kode barang (diambil dari production)
            $table->integer('harga');              // Harga jual
            $table->integer('jumlah');             // Jumlah yang akan dijual (tidak boleh lebih dari production)
            $table->text('deskripsi')->nullable(); // Deskripsi tambahan
            $table->timestamps();                  // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
