<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kategori')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('kategori')->insert([
            [
                'nama_kategori' => 'Komputer Desktop',
                'keterangan' => 'PC desktop untuk kebutuhan kantor dan pengolahan data',
                'durasi_pinjam_default' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Laptop',
                'keterangan' => 'Laptop/notebook untuk mobilitas pegawai',
                'durasi_pinjam_default' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Printer',
                'keterangan' => 'Printer dan mesin cetak dokumen',
                'durasi_pinjam_default' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Scanner',
                'keterangan' => 'Scanner dokumen dan peralatan digitalisasi',
                'durasi_pinjam_default' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Proyektor',
                'keterangan' => 'Proyektor untuk presentasi dan rapat',
                'durasi_pinjam_default' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Peralatan Jaringan',
                'keterangan' => 'Router, switch, access point, dan peralatan jaringan lainnya',
                'durasi_pinjam_default' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
