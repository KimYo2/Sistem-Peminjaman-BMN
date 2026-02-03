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
        Schema::table('histori_peminjaman', function (Blueprint $table) {
            $table->string('kondisi_awal', 30)->nullable()->after('nama_peminjam');
            $table->string('kondisi_kembali', 30)->nullable()->after('waktu_kembali');
            $table->string('catatan_kondisi', 255)->nullable()->after('kondisi_kembali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histori_peminjaman', function (Blueprint $table) {
            $table->dropColumn(['kondisi_awal', 'kondisi_kembali', 'catatan_kondisi']);
        });
    }
};
