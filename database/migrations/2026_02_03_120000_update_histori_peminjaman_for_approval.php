<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('histori_peminjaman', function (Blueprint $table) {
            $table->timestamp('waktu_pengajuan')->nullable()->after('nama_peminjam');
            $table->timestamp('tanggal_jatuh_tempo')->nullable()->after('waktu_kembali');
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->string('rejection_reason', 255)->nullable()->after('rejected_at');

            $table->index('tanggal_jatuh_tempo');
            $table->index('approved_by');
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });

        // Convert enum to string to allow additional statuses (menunggu/ditolak)
        DB::statement("ALTER TABLE histori_peminjaman MODIFY status VARCHAR(20) NOT NULL DEFAULT 'menunggu'");

        // Allow pending requests without waktu_pinjam
        DB::statement('ALTER TABLE histori_peminjaman MODIFY waktu_pinjam TIMESTAMP NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histori_peminjaman', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'waktu_pengajuan',
                'tanggal_jatuh_tempo',
                'approved_by',
                'approved_at',
                'rejected_at',
                'rejection_reason',
            ]);
        });

        DB::statement("ALTER TABLE histori_peminjaman MODIFY status ENUM('dipinjam','dikembalikan') NOT NULL DEFAULT 'dipinjam'");
        DB::statement('ALTER TABLE histori_peminjaman MODIFY waktu_pinjam TIMESTAMP NOT NULL');
    }
};
