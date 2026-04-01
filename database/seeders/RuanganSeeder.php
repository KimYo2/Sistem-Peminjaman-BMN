<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ruangan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('ruangan')->insert([
            ['kode_ruangan' => 'R-101', 'nama_ruangan' => 'Ruang Kabag TU',                   'lantai' => 'Lantai 1',         'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'R-102', 'nama_ruangan' => 'Ruang Subag',                       'lantai' => 'Lantai 1',         'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'R-103', 'nama_ruangan' => 'Frontdesk (Resepsionis & Pengaduan)', 'lantai' => 'Lantai 1',       'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'R-201', 'nama_ruangan' => 'Ruang Teknis 1',                    'lantai' => 'Lantai 2',         'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'R-202', 'nama_ruangan' => 'Ruang Teknis 2',                    'lantai' => 'Lantai 2',         'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'R-GDG', 'nama_ruangan' => 'Gudang',                            'lantai' => 'Gedung Terpisah',  'created_at' => now(), 'updated_at' => now()],
            ['kode_ruangan' => 'R-AUL', 'nama_ruangan' => 'Aula',                              'lantai' => 'Gedung Terpisah',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
