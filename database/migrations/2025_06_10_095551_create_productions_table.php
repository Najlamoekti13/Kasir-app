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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_masuk');         // Tanggal masuk barang
            $table->string('nama_produk');         // Nama produk
            $table->string('kode_barang');         // Kode barang
            $table->integer('harga_per_pcs');      // Harga per pcs
            $table->integer('jumlah_barang');      // Jumlah barang
            $table->string('suplier');             // Nama suplier
            $table->text('deskripsi')->nullable(); // Deskripsi tambahan (opsional)
            $table->timestamps();                  // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
