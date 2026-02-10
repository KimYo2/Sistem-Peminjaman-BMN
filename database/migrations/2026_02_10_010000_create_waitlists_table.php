<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20);
            $table->integer('nup');
            $table->string('nip_peminjam', 20);
            $table->string('status', 20)->default('aktif');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('fulfilled_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->index(['kode_barang', 'nup', 'status']);
            $table->index('nip_peminjam');
            $table->foreign(['kode_barang', 'nup'])
                ->references(['kode_barang', 'nup'])
                ->on('barang')
                ->onDelete('cascade');
            $table->foreign('nip_peminjam')
                ->references('nip')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlists');
    }
};
