<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->string('kode_barang', 20);
            $table->integer('nup');
            $table->string('status', 20)->default('missing');
            $table->string('expected_kondisi', 30)->nullable();
            $table->string('actual_kondisi', 30)->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->unsignedBigInteger('scanned_by')->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'kode_barang', 'nup']);
            $table->index(['session_id', 'status']);
            $table->index('scanned_by');

            $table->foreign('session_id')
                ->references('id')
                ->on('stock_opname_sessions')
                ->cascadeOnDelete();

            $table->foreign(['kode_barang', 'nup'])
                ->references(['kode_barang', 'nup'])
                ->on('barang')
                ->cascadeOnDelete();

            $table->foreign('scanned_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname_items');
    }
};
