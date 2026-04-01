<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tiket_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_bmn', 30)->nullable();
            $table->string('kode_barang', 20)->nullable();
            $table->integer('nup')->nullable();
            $table->foreignId('histori_id')->nullable()->constrained('histori_peminjaman')->nullOnDelete();
            $table->foreignId('dilaporkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('deskripsi');
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['open', 'diproses', 'selesai'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('resolusi', ['diperbaiki', 'dihapuskan', 'hilang', 'diabaikan'])->nullable();
            $table->text('catatan_resolusi')->nullable();
            $table->foreignId('diselesaikan_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_lapor')->nullable();
            $table->timestamp('target_selesai_at')->nullable();
            $table->timestamp('diselesaikan_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index('nomor_bmn');
            $table->index('kode_barang');
            $table->index('status');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_kerusakan');
    }
};
