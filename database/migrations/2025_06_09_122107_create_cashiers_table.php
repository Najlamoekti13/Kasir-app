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
        Schema::create('cashiers', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');          // tanggal transaksi
            $table->string('nama_produk');    // nama produk
            $table->string('kode_produk');    // kode produk
            $table->integer('harga');         // harga produk
            $table->integer('quantity');      // jumlah produk dibeli
            $table->integer('total');         // total belanja (harga * quantity)
            $table->timestamps();             // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashiers');
    }
};
