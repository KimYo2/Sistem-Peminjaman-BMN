<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20); // e.g. 3100102001
            $table->integer('nup'); // e.g. 10
            $table->string('brand', 100);
            $table->string('tipe', 255);
            $table->enum('kondisi_terakhir', ['Baik', 'Rusak Ringan', 'Rusak Berat', ''])->default('');
            $table->enum('ketersediaan', ['tersedia', 'dipinjam'])->default('tersedia');
            $table->string('peminjam_terakhir', 100)->nullable();
            $table->timestamp('waktu_pinjam')->nullable();
            $table->timestamp('waktu_kembali')->nullable();
            $table->timestamps();

            // Indexes
            $table->unique(['kode_barang', 'nup']); // Combine unique constraint
            $table->index('kode_barang');
            $table->index('ketersediaan');
            $table->index('kondisi_terakhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
