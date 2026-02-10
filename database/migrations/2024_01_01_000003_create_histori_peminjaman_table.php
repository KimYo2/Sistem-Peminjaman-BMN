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
        Schema::create('histori_peminjaman', function (Blueprint $table) {
            $driver = Schema::getConnection()->getDriverName();
            $table->id();
            $table->string('kode_barang', 20);
            $table->integer('nup');
            $table->string('nip_peminjam', 20);
            $table->string('nama_peminjam', 100);
            if ($driver === 'sqlite') {
                $table->timestamp('waktu_pinjam')->nullable();
            } else {
                $table->timestamp('waktu_pinjam');
            }
            $table->timestamp('waktu_kembali')->nullable();
            if ($driver === 'sqlite') {
                $table->string('status', 20)->default('menunggu');
            } else {
                $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            }
            $table->timestamps();

            // Foreign keys
            $table->foreign(['kode_barang', 'nup'])
                ->references(['kode_barang', 'nup'])
                ->on('barang')
                ->onDelete('cascade');

            $table->foreign('nip_peminjam')
                ->references('nip')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('status');
            $table->index('nip_peminjam');
            $table->index('waktu_pinjam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histori_peminjaman');
    }
};
