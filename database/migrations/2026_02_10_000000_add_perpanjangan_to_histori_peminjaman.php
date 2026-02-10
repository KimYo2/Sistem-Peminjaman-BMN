<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('histori_peminjaman', function (Blueprint $table) {
            $table->string('perpanjangan_status', 20)->nullable()->after('rejection_reason');
            $table->unsignedSmallInteger('perpanjangan_hari')->nullable()->after('perpanjangan_status');
            $table->timestamp('perpanjangan_diminta_at')->nullable()->after('perpanjangan_hari');
            $table->unsignedBigInteger('perpanjangan_disetujui_by')->nullable()->after('perpanjangan_diminta_at');
            $table->timestamp('perpanjangan_disetujui_at')->nullable()->after('perpanjangan_disetujui_by');
            $table->timestamp('perpanjangan_ditolak_at')->nullable()->after('perpanjangan_disetujui_at');
            $table->string('perpanjangan_alasan', 255)->nullable()->after('perpanjangan_ditolak_at');
            $table->string('perpanjangan_reject_reason', 255)->nullable()->after('perpanjangan_alasan');

            $table->index('perpanjangan_status');
            $table->index('perpanjangan_disetujui_by');
            $table->foreign('perpanjangan_disetujui_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('histori_peminjaman', function (Blueprint $table) {
            $table->dropForeign(['perpanjangan_disetujui_by']);
            $table->dropIndex(['perpanjangan_status']);
            $table->dropIndex(['perpanjangan_disetujui_by']);
            $table->dropColumn([
                'perpanjangan_status',
                'perpanjangan_hari',
                'perpanjangan_diminta_at',
                'perpanjangan_disetujui_by',
                'perpanjangan_disetujui_at',
                'perpanjangan_ditolak_at',
                'perpanjangan_alasan',
                'perpanjangan_reject_reason',
            ]);
        });
    }
};
