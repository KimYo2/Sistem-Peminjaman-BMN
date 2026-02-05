<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Temporarily relax to string to avoid ENUM duplicate issues across collations
        DB::statement("ALTER TABLE barang MODIFY kondisi_terakhir VARCHAR(30) NOT NULL DEFAULT ''");

        DB::statement("UPDATE barang SET kondisi_terakhir = 'baik' WHERE kondisi_terakhir IS NULL");
        DB::statement("UPDATE barang SET kondisi_terakhir = 'baik' WHERE kondisi_terakhir IN ('Baik','baik','')");
        DB::statement("UPDATE barang SET kondisi_terakhir = 'rusak_ringan' WHERE kondisi_terakhir IN ('Rusak Ringan','rusak_ringan')");
        DB::statement("UPDATE barang SET kondisi_terakhir = 'rusak_berat' WHERE kondisi_terakhir IN ('Rusak Berat','rusak_berat')");

        DB::statement("ALTER TABLE barang MODIFY kondisi_terakhir ENUM('baik','rusak_ringan','rusak_berat') NOT NULL DEFAULT 'baik'");
        DB::statement("ALTER TABLE barang MODIFY ketersediaan ENUM('tersedia','dipinjam','hilang','reparasi') NOT NULL DEFAULT 'tersedia'");
    }

    public function down(): void
    {
        // Temporarily relax to string to avoid ENUM duplicate issues across collations
        DB::statement("ALTER TABLE barang MODIFY kondisi_terakhir VARCHAR(30) NOT NULL DEFAULT ''");

        DB::statement("UPDATE barang SET kondisi_terakhir = 'Baik' WHERE kondisi_terakhir = 'baik'");
        DB::statement("UPDATE barang SET kondisi_terakhir = 'Rusak Ringan' WHERE kondisi_terakhir = 'rusak_ringan'");
        DB::statement("UPDATE barang SET kondisi_terakhir = 'Rusak Berat' WHERE kondisi_terakhir = 'rusak_berat'");
        DB::statement("UPDATE barang SET ketersediaan = 'tersedia' WHERE ketersediaan IN ('hilang','reparasi')");

        DB::statement("ALTER TABLE barang MODIFY kondisi_terakhir ENUM('Baik','Rusak Ringan','Rusak Berat','') NOT NULL DEFAULT ''");
        DB::statement("ALTER TABLE barang MODIFY ketersediaan ENUM('tersedia','dipinjam') NOT NULL DEFAULT 'tersedia'");
    }
};
